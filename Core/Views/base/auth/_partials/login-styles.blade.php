<style>
    .login-form-card h5.mt-3 {
        font-weight: 400 !important;
        text-transform: none !important;
        color: #6c757d !important;
        font-size: 0.9rem;
    }

    .login-form-card h3 {
        text-transform: none !important;
    }

    .login-form-card .input-with-icon {
        position: relative;
        width: 100%;
        background: white;
    }

    .login-form-card .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #6c757d;
        pointer-events: none;
        z-index: 2;
    }

    .login-form-card .theme-input-style {
        padding-left: 40px !important;
        width: 100%;
        background: white;
        border: 1px solid black;
    }

    .login-form-card .theme-input-style:focus,
    .login-form-card .theme-input-style:active,
    .login-form-card .theme-input-style:hover {
        background-color: white !important;
        background: white !important;
        outline: none;
        border: 1px solid black !important;
    }

    .login-form-card .password-toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: inherit;
        display: flex;
        align-items: center;
    }

    .login-form-card .password-toggle-btn svg {
        width: 18px;
        height: 18px;
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .login-form-card .password-toggle-btn:hover svg {
        opacity: 1;
    }

    .login-form-card button.btn-orange,
    .login-form-card a.btn-orange {
        background: #ff5A1f !important;
        border-color: #e64a10 !important;
        color: #fff !important;
        transition: background 0.2s ease;
        box-shadow: none !important;
        border-radius: 6px !important;
    }

    .login-form-card button.btn-orange:hover,
    .login-form-card a.btn-orange:hover {
        background: #ff7545 !important;
        border-color: #e07b00 !important;
        color: #fff !important;
        box-shadow: none !important;
    }

    .login-form-card button.btn-orange:focus,
    .login-form-card button.btn-orange:active,
    .login-form-card button.btn-orange:active:focus {
        background: #ff5A1F !important;
        border-color: #e07b00 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .login-form-card button.btn-demo-outline {
        background: #fff !important;
        border: 1px solid #ff5A1f !important;
        color: #ff5A1f !important;
        transition: background 0.2s ease, color 0.2s ease;
        box-shadow: none !important;
        border-radius: 6px !important;
    }

    .login-form-card button.btn-demo-outline:hover {
        background: #fff5f0 !important;
        color: #e64a10 !important;
        border-color: #e64a10 !important;
    }

    #loginModal .contact-modal-dialog {
        max-width: 400px;
    }

    #loginModal .login-form-card {
        width: 100% !important;
        min-height: auto !important;
        margin: 0;
        padding: 32px 28px 28px !important;
        background: var(--surface, #fff) !important;
        border: 1px solid var(--line2, #e8e8e8) !important;
        border-radius: var(--rxl, 16px) !important;
        box-shadow: var(--sh2, 0 4px 16px rgba(0, 0, 0, .06), 0 1px 4px rgba(0, 0, 0, .03)) !important;
        box-sizing: border-box;
        position: relative;
    }

    #loginModal .login-form-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    #loginModal .login-form-card label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--ink3, #555);
    }

    #loginModal .login-form-card .input-with-icon {
        border-radius: var(--r, 10px);
        background: var(--surface, #fff);
    }

    #loginModal .login-form-card .theme-input-style {
        padding: 11px 40px 11px 40px !important;
        width: 100%;
        box-sizing: border-box;
        background: var(--surface, #fff) !important;
        border: 1.5px solid var(--line2, #e8e8e8) !important;
        border-radius: var(--r, 10px) !important;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        color: var(--ink, #0A0A0A);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }

    #loginModal .login-form-card .theme-input-style:focus {
        background-color: var(--surface, #fff) !important;
        background: var(--surface, #fff) !important;
        border-color: var(--o, #FF7A00) !important;
        box-shadow: 0 0 0 3px rgba(255, 122, 0, .12) !important;
    }

    #loginModal .login-form-card .theme-input-style:hover:not(:focus) {
        background-color: var(--surface, #fff) !important;
        background: var(--surface, #fff) !important;
        border-color: var(--line2, #e8e8e8) !important;
    }

    #loginModal .login-form-card button.btn-orange {
        border-radius: var(--r, 10px) !important;
    }

    #loginModal .login-form-card .d-flex {
        display: flex;
    }

    #loginModal .login-form-card .justify-content-between {
        justify-content: space-between;
    }

    #loginModal .login-form-card .align-items-center {
        align-items: center;
    }

    #loginModal .login-form-card .card-body {
        padding: 0;
    }

    @media (max-width: 600px) {
        #loginModal .login-form-card {
            padding: 24px 18px 20px !important;
        }
    }

    .login-form-card .form-group {
        margin-bottom: 0;
    }

    .login-form-card .mb-10 {
        margin-bottom: 10px;
    }

    .login-form-card .mb-20 {
        margin-bottom: 20px;
    }

    .login-form-card .font-14 {
        font-size: 14px;
    }

    .login-form-card .black {
        color: #0A0A0A;
    }

    .login-form-card .text-danger {
        color: #dc3545;
        font-size: 0.875rem;
    }

    .login-form-card .btn-block {
        display: block;
        width: 100%;
    }

    .login-form-card .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: auto;
        padding-left: 0;
    }

    .login-form-card .form-check-input,
    .login-form-card .form-check .form-check-input {
        position: static;
        float: none;
        margin: 0;
        flex-shrink: 0;
    }

    .login-form-card .form-check-label {
        margin: 0;
        line-height: 1.4;
        font-size: 0.9rem;
    }

    .login-form-card label.mb-2 {
        display: block;
        margin-bottom: 8px;
    }

    .login-form-card .login-submit-btn {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-form-card .login-submit-btn .login-btn-spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, .35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: login-btn-spin .65s linear infinite;
    }

    .login-form-card .login-submit-btn.is-loading .login-btn-label {
        visibility: hidden;
    }

    .login-form-card .login-submit-btn.is-loading .login-btn-spinner {
        display: block;
        position: absolute;
    }

    .login-form-card .login-submit-btn.is-loading {
        pointer-events: none;
        opacity: .92;
    }

    @keyframes login-btn-spin {
        to {
            transform: rotate(360deg);
        }
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
</style>
