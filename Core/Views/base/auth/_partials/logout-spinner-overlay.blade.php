<div id="logout-overlay" class="logout-overlay" role="status" aria-live="polite" aria-busy="true"
    aria-label="{{ translate('Logging out') }}"
    style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background-image:url('/themes/default/1920.png');background-size:100% 100%;background-color:#f5f5f5;opacity:1;transition:opacity 0.3s ease;">
    <div class="logout-overlay__spinner"
        style="width:40px;height:40px;border:3px solid rgba(255,90,31,0.2);border-top-color:#FF5A1F;border-radius:50%;animation:logout-overlay-spin 0.65s linear infinite;">
    </div>
</div>
<style>
    @media (max-width: 1200px) {
        #logout-overlay {
            background-image: url('/themes/default/bg.png') !important;
            background-size: cover !important;
        }
    }

    @keyframes logout-overlay-spin {
        to {
            transform: rotate(360deg);
        }
    }

    #logout-overlay.logout-overlay--fade-out {
        opacity: 0;
        pointer-events: none;
    }
</style>
