@php

    use Theme\TLCommerce\Http\Resources\QuizResultsConfig;

    $isDefaultLang = $isDefaultLang ?? true;

    $resultsActionsRaw = null;

    if (old('results_actions_json')) {

        $oldRaw = old('results_actions_json');

        $resultsActionsRaw = json_decode($oldRaw, true);

        if (!is_array($resultsActionsRaw)) {

            $resultsActionsRaw = json_decode(html_entity_decode($oldRaw, ENT_QUOTES, 'UTF-8'), true);

        }

    } elseif (isset($layoutConfigForForm) && is_array($layoutConfigForForm['results'] ?? null)) {

        $resultsActionsRaw = $layoutConfigForForm['results']['actions'] ?? null;

    } elseif (isset($quiz) && is_array($quiz->layout_config['results'] ?? null)) {

        $resultsActionsRaw = $quiz->layout_config['results']['actions'] ?? null;

    }

    $resultsActions = QuizResultsConfig::normalizeActions(

        is_array($resultsActionsRaw) ? $resultsActionsRaw : null

    );

@endphp



<textarea name="results_actions_json" id="results-actions-json" class="d-none" rows="1" aria-hidden="true">@json($resultsActions)</textarea>



<div id="results-actions-panel">

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mb-3 @if (!$isDefaultLang) area-disabled @endif">

        <h5 class="mb-0">{{ translate('Action buttons') }}</h5>

        <button type="button" class="btn btn-sm btn-outline-dark" id="results-add-action-btn">{{ translate('Add button') }}</button>

    </div>

    <p class="text-muted small mb-3">{{ translate('Up to 5 buttons below the result card. Actions: link, top product, share, restart quiz.') }}</p>

    <div id="results-actions-list" class="mb-3"></div>

</div>



<style>

    .results-action-row {

        padding: 12px;

        margin-bottom: 8px;

        border: 1px solid #e5e7eb;

        border-radius: 8px;

        background: #fafafa;

    }

    .results-action-row.disabled { opacity: 0.6; }

</style>

