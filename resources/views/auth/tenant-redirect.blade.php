
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
        <p>Redirecting to your dashboard...</p>
    </div>

    <form id="tenant-login-form" action="{{ $tenantUrl }}" method="POST">
        @csrf
        <input type="hidden" name="login_token" value="{{ $loginToken }}">
    </form>

    <script>
        document.getElementById('tenant-login-form').submit();
    </script>
</body>
</html>