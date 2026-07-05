<?php

namespace Theme\TLCommerce\Http\Controllers\Api;

use Illuminate\Http\Request;
use Plugin\TlcommerceCore\Models\Product;
use App\Http\Controllers\Controller;
use Theme\TLCommerce\Http\Resources\QuizResource;
use Plugin\TlcommerceCore\Http\Resources\ProductCollection;
use Theme\TLCommerce\Repositories\QuizSubmissionRepository;

class QuizController extends Controller
{
    public function __construct(protected QuizSubmissionRepository $submissionRepository)
    {
        if (!isActivePluging('tlecommercecore')) {
            abort(403, 'Tlcommerce plugin is required');
        }
    }

    public function show(string $slug)
    {
        $quiz = $this->submissionRepository->findActiveQuizBySlug($slug);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => translate('Quiz not found'),
            ], 404);
        }

        return new QuizResource($quiz);
    }

    public function submit(string $slug, Request $request)
    {
        $request->validate([
            'session_token' => 'required|string|max:64',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer',
            'answers.*.answer_id' => 'required|integer',
            'guest.name' => 'nullable|string|max:255',
            'guest.email' => 'nullable|email|max:255',
            'guest_customer_id' => 'nullable|integer',
        ]);

        $quiz = $this->submissionRepository->findActiveQuizBySlug($slug);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => translate('Quiz not found'),
            ], 404);
        }

        try {
            $result = $this->submissionRepository->submitQuiz($quiz, $request);
            $submission = $result['submission'];
            $ranked = $result['ranked'];

            return response()->json([
                'success' => true,
                'submission_id' => $submission->id,
                'results' => $this->formatRankedResults($ranked),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => translate('Unable to submit quiz'),
            ], 500);
        }
    }

    public function results(int $id, Request $request)
    {
        $request->validate([
            'session_token' => 'nullable|string|max:64',
        ]);

        $submission = $this->submissionRepository->getSubmissionResults(
            $id,
            $request->input('session_token')
        );

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => translate('Results not found'),
            ], 404);
        }

        $topN = (int) ($submission->quiz->layout_config['top_n'] ?? 3);
        $topN = $topN > 0 ? $topN : 3;

        $maxScore = (float) $submission->results->max('total_score');

        $ranked = $submission->results
            ->sortBy('rank')
            ->take($topN)
            ->map(function ($row) use ($maxScore) {
                return [
                    'product_id'  => (int) $row->product_id,
                    'total_score' => (float) $row->total_score,
                    'match_pct'   => $maxScore > 0
                        ? (int) round(((float) $row->total_score / $maxScore) * 100)
                        : 0,
                    'rank'        => (int) $row->rank,
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'submission_id' => $submission->id,
            'results' => $this->formatRankedResults($ranked),
        ]);
    }

    /**
     * @param  array<int, array{product_id: int, total_score: float, match_pct: int, rank: int}>  $ranked
     */
    protected function formatRankedResults(array $ranked): array
    {
        if (empty($ranked)) {
            return [];
        }

        $productIds = collect($ranked)->pluck('product_id')->all();

        $products = Product::with(['single_price', 'variations', 'reviews'])
            ->whereIn('id', $productIds)
            ->where('status', config('settings.general_status.active'))
            ->where('is_approved', config('settings.general_status.active'))
            ->get()
            ->keyBy('id');

        $productCollection = new ProductCollection($products->values());
        $locale = session()->get('api_locale');

        $productPayload = collect($productCollection->toArray(request())['data'] ?? [])
            ->keyBy('id');

        return collect($ranked)->map(function ($row) use ($productPayload, $products, $locale) {
            $product = $productPayload->get($row['product_id']);
            if (empty($product)) {
                return null;
            }

            $model = $products->get($row['product_id']);
            if ($model) {
                $summary = $model->translation('summary', $locale);
                $product['summary'] = is_string($summary) ? $this->normalizeQuizProductSummary($summary) : '';
            }

            return array_merge($row, [
                'product' => $product,
            ]);
        })->filter(fn ($row) => is_array($row) && !empty($row['product']))->values()->all();
    }

    protected function normalizeQuizProductSummary(string $html): string
    {
        $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/<\s*br\s*\/?>/i', "\n", $text) ?? $text;
        $text = preg_replace('/<\/\s*p\s*>/i', "\n\n", $text) ?? $text;
        $text = preg_replace('/<\/\s*div\s*>/i', "\n\n", $text) ?? $text;
        $text = strip_tags($text);
        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];
        $lines = array_map(function (string $line) {
            $line = preg_replace('/[ \t\x{C2}\x{A0}]+/u', ' ', $line) ?? $line;

            return trim($line);
        }, $lines);
        $lines = array_values(array_filter($lines, static fn (string $line) => $line !== ''));
        $text = implode("\n\n", $lines);

        return trim($text);
    }
}
