{{-- Browser notification permission prompt (admin shell) --}}
<div id="web-notification-permission-banner" class="web-notification-permission-banner d-none" role="dialog"
    aria-labelledby="web-notification-permission-title" aria-live="polite">
    <div class="web-notification-permission-banner__inner">
        <div class="web-notification-permission-banner__text">
            <strong id="web-notification-permission-title">{{ translate('Enable notifications') }}</strong>
            <p>{{ translate('Allow notifications so you hear an alert when something new arrives.') }}</p>
        </div>
        <div class="web-notification-permission-banner__actions">
            <button type="button" id="web-notification-enable-btn"
                class="btn btn-sm">{{ translate('Enable') }}</button>
            <button type="button" id="web-notification-dismiss-btn"
                class="btn btn-sm btn-light">{{ translate('Not now') }}</button>
        </div>
    </div>
</div>
