@php
    use Theme\TLCommerce\Http\Resources\QuizResultsConfig;
    $resultsBlocksRaw = old('results_blocks_json') ? json_decode(old('results_blocks_json'), true) : (isset($quiz) && is_array($quiz->layout_config['results'] ?? null) ? ($quiz->layout_config['results']['blocks'] ?? null) : null);
    $resultsBlocks = is_array($resultsBlocksRaw) && count($resultsBlocksRaw) ? $resultsBlocksRaw : QuizResultsConfig::defaultBlocks();
@endphp

<input type="hidden" name="results_blocks_json" id="results-blocks-json" value="@json($resultsBlocks)">

<div id="results-blocks-panel">
    <hr class="my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ translate('Result card blocks') }}</h5>
        <button type="button" class="btn btn-sm btn-outline-dark" id="results-reset-blocks-btn">{{ translate('Reset blocks') }}</button>
    </div>
    <p class="text-muted small mb-3">
        {{ translate('Enable, reorder, and edit blocks. Use tokens:') }}
        @{{product.name}}, @{{product.url}}, @{{product.summary}}, @{{match_pct}}, @{{product_2.name}}, etc.
    </p>
    <div id="results-blocks-list" class="mb-3"></div>
</div>

<style>
    .results-block-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        margin-bottom: 8px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fafafa;
    }
    .results-block-row__main { flex: 1; min-width: 0; }
    .results-block-row__type { font-weight: 600; font-size: 13px; }
    .results-block-row__hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
    .results-block-row__actions { display: flex; flex-direction: column; gap: 4px; }
    .results-block-row__actions button { padding: 2px 8px; font-size: 12px; }
    .results-block-editor { width: 100%; min-height: 80px; }
    .results-block-row .note-editor { margin-top: 8px; }
    .results-block-separator-panel .theme-input-style { max-width: 120px; font-size: 12px; }
    .results-sep-row .small { color: #6b7280; }
    .results-block-spacing-panel,
    .results-block-appearance-panel { padding: 8px 10px; background: #f3f4f6; border-radius: 6px; border: 1px solid #e5e7eb; }
    .results-mixture-part .btn { padding: 2px 6px; font-size: 11px; }
    .results-sep-mixture-wrap { padding: 8px; background: #fff; border: 1px dashed #d1d5db; border-radius: 6px; }
</style>
