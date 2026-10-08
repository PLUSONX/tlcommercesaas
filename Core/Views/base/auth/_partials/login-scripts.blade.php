@php
    $idPrefix = $idPrefix ?? '';
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutOverlay = document.getElementById('logout-overlay');
        if (logoutOverlay) {
            logoutOverlay.classList.add('logout-overlay--fade-out');

            const removeOverlay = function() {
                if (logoutOverlay.parentNode) {
                    logoutOverlay.remove();
                }
            };

            logoutOverlay.addEventListener('transitionend', removeOverlay, { once: true });
            setTimeout(removeOverlay, 400);
        }

        const idPrefix = @json($idPrefix);
        const tokenInput = document.getElementById(idPrefix + 'token');
        const loginForm = tokenInput ? tokenInput.closest('form') : null;

        function syncDeviceToken() {
            if (!tokenInput) {
                return false;
            }
            const savedToken = localStorage.getItem('token');
            if (savedToken) {
                tokenInput.value = savedToken;
                return true;
            }
            return false;
        }

        syncDeviceToken();

        function getSubmitBtn() {
            if (!loginForm) {
                return document.getElementById(idPrefix + 'submitBtn');
            }
            return document.getElementById(idPrefix + 'submitBtn') ||
                loginForm.querySelector('.login-submit-btn') ||
                loginForm.querySelector('[type="submit"]');
        }

        function resetLoginSubmitState() {
            const submitBtn = getSubmitBtn();
            if (!submitBtn) {
                return;
            }
            submitBtn.classList.remove('is-loading');
            submitBtn.disabled = false;
            submitBtn.removeAttribute('aria-busy');
        }

        // Browser Back restores this page from bfcache with the spinner still on.
        window.addEventListener('pageshow', function() {
            resetLoginSubmitState();
        });

        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                syncDeviceToken();

                const submitBtn = getSubmitBtn();

                if (submitBtn && submitBtn.classList.contains('is-loading')) {
                    e.preventDefault();
                    return;
                }

                if (submitBtn) {
                    submitBtn.classList.add('is-loading');
                    submitBtn.disabled = true;
                    submitBtn.setAttribute('aria-busy', 'true');
                }
            });
        }

        if (!syncDeviceToken()) {
            let attempts = 0;
            const maxAttempts = 20;
            const pollId = setInterval(function() {
                attempts += 1;
                if (syncDeviceToken() || attempts >= maxAttempts) {
                    clearInterval(pollId);
                }
            }, 500);
        }

        const togglePassword = document.getElementById(idPrefix + 'togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const input = document.getElementById(idPrefix + 'password');
                const eyeIcon = this.querySelector('.eye-icon');
                const eyeOffIcon = this.querySelector('.eye-off-icon');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'inline';
                } else {
                    input.type = 'password';
                    eyeIcon.style.display = 'inline';
                    eyeOffIcon.style.display = 'none';
                }
            });
        }

        const demoLoginBtn = document.getElementById(idPrefix + 'demoLoginBtn');
        if (demoLoginBtn) {
            demoLoginBtn.addEventListener('click', function() {
                document.getElementById(idPrefix + 'email').value = this.dataset.email;
                document.getElementById(idPrefix + 'password').value = this.dataset.password;
            });
        }
    });
</script>
