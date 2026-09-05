<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Sistem Manajemen Ikan - Login">
    <title>Login | Sistem Ikan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary:         #0077b6;
            --primary-dark:   #023e8a;
            --primary-light:  #90e0ef;
            --accent:         #00b4d8;
            --accent-glow:    rgba(0, 180, 216, 0.35);
            --surface:        rgba(255, 255, 255, 0.07);
            --surface-hover:  rgba(255, 255, 255, 0.12);
            --border:         rgba(144, 224, 239, 0.25);
            --text-primary:   #caf0f8;
            --text-secondary: rgba(202, 240, 248, 0.65);
            --error:          #ff6b6b;
            --error-bg:       rgba(255, 107, 107, 0.12);
            --success:        #52c41a;
        }

        html, body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #03045e 0%, #023e8a 30%, #0077b6 60%, #0096c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 16px;
            overflow-y: auto;
        }

        /* ── Animasi Background ── */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 30% 40%, rgba(0, 180, 216, 0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 60%, rgba(0, 119, 182, 0.2) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 80%, rgba(2, 62, 138, 0.3) 0%, transparent 50%);
            animation: bgPulse 8s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes bgPulse {
            0%   { transform: scale(1) rotate(0deg); opacity: 0.7; }
            100% { transform: scale(1.05) rotate(2deg); opacity: 1; }
        }

        /* ── Bubble Decorations ── */
        .bubbles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .bubble {
            position: absolute;
            bottom: -150px;
            border-radius: 50%;
            background: rgba(144, 224, 239, 0.08);
            border: 1px solid rgba(144, 224, 239, 0.15);
            animation: bubbleRise linear infinite;
        }

        .bubble:nth-child(1)  { width: 60px;  height: 60px;  left: 8%;   animation-duration: 12s; animation-delay: 0s; }
        .bubble:nth-child(2)  { width: 30px;  height: 30px;  left: 18%;  animation-duration: 9s;  animation-delay: 2s; }
        .bubble:nth-child(3)  { width: 90px;  height: 90px;  left: 35%;  animation-duration: 14s; animation-delay: 1s; }
        .bubble:nth-child(4)  { width: 45px;  height: 45px;  left: 55%;  animation-duration: 11s; animation-delay: 3s; }
        .bubble:nth-child(5)  { width: 75px;  height: 75px;  left: 72%;  animation-duration: 13s; animation-delay: 0.5s; }
        .bubble:nth-child(6)  { width: 25px;  height: 25px;  left: 85%;  animation-duration: 8s;  animation-delay: 4s; }
        .bubble:nth-child(7)  { width: 50px;  height: 50px;  left: 93%;  animation-duration: 10s; animation-delay: 1.5s; }

        @keyframes bubbleRise {
            0%   { transform: translateY(0) rotate(0deg);   opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.5; }
            100% { transform: translateY(-110vh) rotate(360deg); opacity: 0; }
        }

        /* ── Wrapper & Card ── */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        .login-card {
            background: #ffffff;
            border: none;
            border-radius: 24px;
            padding: 32px 28px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.35),
                0 8px 20px rgba(0, 0, 0, 0.2);
            animation: cardAppear 0.5s ease-out both;
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ── Logo & Header ── */
        .logo-area {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 86px;
            height: 86px;
            margin: 0 auto 12px;
            background: transparent;
            box-shadow: none;
            border: none;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .app-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .app-subtitle {
            font-size: 13px;
            color: #64748b;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider-text {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── Form ── */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus {
            background: #fff;
            border-color: #0077b6;
            box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.12);
        }

        .input-icon-right {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 14px;
        }

        .form-input.has-right-icon {
            padding-right: 40px;
        }

        /* ── Alerts ── */
        .alert-error, .alert-success {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error {
            background: var(--error-bg);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff9a9a;
        }

        .alert-success {
            background: rgba(82, 196, 26, 0.12);
            border: 1px solid rgba(82, 196, 26, 0.3);
            color: #95de64;
        }

        .field-error {
            font-size: 12px;
            color: var(--error);
            margin-top: 4px;
        }

        /* ── Remember ── */
        .form-extras {
            margin-bottom: 20px;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrap input[type="checkbox"] {
            accent-color: var(--accent);
            cursor: pointer;
        }

        .checkbox-wrap span {
            font-size: 13px;
            color: #475569;
            font-weight: 600;
        }

        /* ── Submit Button ── */
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px var(--accent-glow);
            transition: transform 0.15s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <!-- Background Bubbles -->
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">

            <!-- Logo & Title -->
            <div class="logo-area">
                <div class="logo-icon">
                    <img src="{{ asset('logo/logo_ikan.png') }}" alt="Logo" onerror="this.parentElement.innerHTML='<i class=\'fas fa-fish\' style=\'font-size:38px;color:#90e0ef;\'></i>'">
                </div>
                <h1 class="app-title">PT. PELOPOR SAMI JAYA</h1>
                <p class="app-subtitle">Sistem pencatatan &amp; Monitoring penjualan ikan</p>
            </div>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">Masuk ke Akun</span>
                <div class="divider-line"></div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            class="form-input @error('username') is-invalid @enderror"
                            placeholder="Masukkan username Anda"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            autofocus
                        >
                    </div>
                    @error('username')
                        <p class="field-error">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-input has-right-icon @error('password') is-invalid @enderror"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                        >
                        <i class="fas fa-eye input-icon-right" id="togglePassword" title="Tampilkan password"></i>
                    </div>
                    @error('password')
                        <p class="field-error">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-extras">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login" id="btnLogin">
                    <span class="btn-content">
                        <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
                    </span>
                </button>
            </form>

        </div>
    </div>

    <script>
        // Toggle show/hide password
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            this.classList.toggle('fa-eye', !isHidden);
            this.classList.toggle('fa-eye-slash', isHidden);
        });

        // Button loading state
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnLogin');
            btn.innerHTML = '<span class="btn-content"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>';
            btn.disabled = true;
        });
    </script>

</body>
</html>