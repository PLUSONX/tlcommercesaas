<?php

namespace Theme\TLCommerce\Services;

use Illuminate\Support\Collection;
use Theme\TLCommerce\Models\QuizResult;
use Theme\TLCommerce\Models\QuizSubmissionAnswer;
use Theme\TLCommerce\Models\QuizAnswerProductScore;

class QuizScoringEngine
{
    /**
     * Score a submission and persist ranked results.
     *
     * @return array<int, array{product_id: int, total_score: float, match_pct: int, rank: int}>
     */
    public function score(int $submissionId, int $topN = 3): array
    {
        $answerIds = QuizSubmissionAnswer::where('submission_id', $submissionId)
            ->pluck('answer_id')
            ->all();

        if (empty($answerIds)) {
            QuizResult::where('submission_id', $submissionId)->delete();

            return [];
        }

        $scores = QuizAnswerProductScore::query()
            ->whereIn('answer_id', $answerIds)
            ->selectRaw('product_id, SUM(score) as total_score')
            ->groupBy('product_id')
            ->orderByDesc('total_score')
            ->get();

        if ($scores->isEmpty()) {
            QuizResult::where('submission_id', $submissionId)->delete();

            return [];
        }

        $maxScore = (float) $scores->max('total_score');

        $ranked = $scores->values()->map(function ($row, $index) use ($maxScore) {
            $totalScore = (float) $row->total_score;

            return [
                'product_id'  => (int) $row->product_id,
                'total_score' => $totalScore,
                'match_pct'   => $maxScore > 0
                    ? (int) round(($totalScore / $maxScore) * 100)
                    : 0,
                'rank'        => $index + 1,
            ];
        });

        $this->saveResults($submissionId, $ranked);

        return $ranked->take($topN)->values()->all();
    }

    private function saveResults(int $submissionId, Collection $ranked): void
    {
        QuizResult::where('submission_id', $submissionId)->delete();

        if ($ranked->isEmpty()) {
            return;
        }

        QuizResult::insert($ranked->map(fn ($row) => [
            'submission_id' => $submissionId,
            'product_id'    => $row['product_id'],
            'total_score'   => $row['total_score'],
            'rank'          => $row['rank'],
            'created_at'    => now(),
        ])->all());
    }
}
