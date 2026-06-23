<?php

namespace Theme\TLCommerce\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Theme\TLCommerce\Models\QuizAnswer;
use Theme\TLCommerce\Models\QuizFeature;
use Theme\TLCommerce\Models\QuizQuestion;
use Theme\TLCommerce\Models\QuizSubmission;
use Theme\TLCommerce\Services\QuizScoringEngine;
use Plugin\TlcommerceCore\Models\GuestCustomers;
use Theme\TLCommerce\Models\QuizSubmissionAnswer;

class QuizSubmissionRepository
{
    public function __construct(protected QuizScoringEngine $scoringEngine)
    {
    }

    public function findActiveQuizBySlug(string $slug): ?QuizFeature
    {
        return QuizFeature::with([
            'quiz_feature_translations',
            'questions' => fn ($q) => $q->orderBy('sort_order'),
            'questions.quiz_question_translations',
            'questions.answers' => fn ($q) => $q->orderBy('sort_order'),
            'questions.answers.quiz_answer_translations',
        ])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();
    }

    public function submitQuiz(QuizFeature $quiz, Request $request): array
    {
        $this->optionalJwtCustomer($request);

        $validatedAnswers = $this->validateAnswers($quiz, $request->input('answers', []));

        DB::beginTransaction();

        try {
            $submission = new QuizSubmission();
            $submission->quiz_id = $quiz->id;
            $submission->session_token = $request->input('session_token');
            $submission->submitted_at = now();

            if (auth('jwt-customer')->check()) {
                $submission->customer_id = auth('jwt-customer')->id();
            } elseif ($request->filled('guest.name')) {
                $guest = new GuestCustomers();
                $guest->name = $request->input('guest.name') ?: 'Guest Customer';
                $guest->email = $request->input('guest.email') ?: 'guest@default.com';
                $guest->order_id = null;
                $guest->save();
                $submission->guest_customer_id = $guest->id;
            } elseif ($request->filled('guest_customer_id')) {
                $submission->guest_customer_id = (int) $request->input('guest_customer_id');
            }

            $submission->save();

            foreach ($validatedAnswers as $row) {
                QuizSubmissionAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id'   => $row['question_id'],
                    'answer_id'     => $row['answer_id'],
                ]);
            }

            $topN = (int) ($quiz->layout_config['top_n'] ?? 3);
            $topN = $topN > 0 ? $topN : 3;

            $ranked = $this->scoringEngine->score($submission->id, $topN);

            DB::commit();

            return [
                'submission' => $submission->fresh(['results']),
                'ranked'     => $ranked,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSubmissionResults(int $submissionId, ?string $sessionToken = null): ?QuizSubmission
    {
        $this->optionalJwtCustomer(request());

        $submission = QuizSubmission::with(['results.product', 'quiz'])
            ->find($submissionId);

        if (!$submission) {
            return null;
        }

        if (!$this->canAccessSubmission($submission, $sessionToken)) {
            return null;
        }

        return $submission;
    }

    public function canAccessSubmission(QuizSubmission $submission, ?string $sessionToken = null): bool
    {
        if (auth('jwt-customer')->check()) {
            return (int) $submission->customer_id === (int) auth('jwt-customer')->id();
        }

        if ($sessionToken && $submission->session_token === $sessionToken) {
            return true;
        }

        return false;
    }

    /**
     * @param  array<int, array{question_id: mixed, answer_id: mixed}>  $answers
     * @return array<int, array{question_id: int, answer_id: int}>
     */
    protected function validateAnswers(QuizFeature $quiz, array $answers): array
    {
        $quiz->loadMissing(['questions.answers']);

        $questionMap = $quiz->questions->keyBy('id');
        $validated = [];
        $answersByQuestion = [];

        foreach ($answers as $row) {
            $questionId = (int) ($row['question_id'] ?? 0);
            $answerId = (int) ($row['answer_id'] ?? 0);

            if (!$questionId || !$answerId) {
                continue;
            }

            $question = $questionMap->get($questionId);
            if (!$question) {
                throw new \InvalidArgumentException(translate('Invalid question selected'));
            }

            $validAnswer = $question->answers->firstWhere('id', $answerId);
            if (!$validAnswer) {
                throw new \InvalidArgumentException(translate('Invalid answer selected'));
            }

            $answersByQuestion[$questionId][] = $answerId;
            $validated[] = [
                'question_id' => $questionId,
                'answer_id'   => $answerId,
            ];
        }

        foreach ($quiz->questions as $question) {
            if (!$question->is_required) {
                continue;
            }

            if (empty($answersByQuestion[$question->id])) {
                throw new \InvalidArgumentException(translate('Please answer all required questions'));
            }

            if (in_array($question->question_type, ['radio', 'dropdown', 'image_select'], true)) {
                if (count($answersByQuestion[$question->id]) > 1) {
                    throw new \InvalidArgumentException(translate('Only one answer allowed for this question'));
                }
            }
        }

        if (empty($validated)) {
            throw new \InvalidArgumentException(translate('Please select at least one answer'));
        }

        return $validated;
    }

    protected function optionalJwtCustomer(Request $request): void
    {
        if (!$request->bearerToken()) {
            return;
        }

        try {
            auth('jwt-customer')->setToken($request->bearerToken())->authenticate();
        } catch (\Throwable $e) {
            // Continue as guest when token is invalid or expired.
        }
    }
}
