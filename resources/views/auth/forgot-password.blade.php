<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Sistem Manajemen Ikan - Pemulihan Password">
    <title>Lupa Password | Sistem Ikan</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo_ikan2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <noscript>
        <style>
            .recovery-wrapper, .bubbles { display: none !important; }
            .noscript-warning-screen {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: #03045e;
                color: #ffffff;
                z-index: 999999;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 24px;
                text-align: center;
                font-family: 'Inter', sans-serif;
            }
            .noscript-warning-box {
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 20px;
                padding: 36px 28px;
                max-width: 480px;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            }
            .noscript-icon {
                font-size: 50px;
                color: #f59e0b;
                margin-bottom: 16px;
            }
            .noscript-title {
                font-size: 20px;
                font-weight: 800;
                margin-bottom: 10px;
                color: #ffffff;
            }
            .noscript-text {
                font-size: 14px;
                color: #caf0f8;
                line-height: 1.6;
            }
        </style>
    </noscript>
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
            --border:         rgba(144, 224, 239, 0.25);
            --error:          #ff6b6b;
            --error-bg:       rgba(255, 107, 107, 0.12);
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

        @keyframes bubbleRise {
            0%   { transform: translateY(0) rotate(0deg);   opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.5; }
            100% { transform: translateY(-110vh) rotate(360deg); opacity: 0; }
        }

        /* ── Wrapper & Card ── */
        .recovery-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            margin: auto;
        }

        .recovery-card {
            background: #ffffff;
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
            width: 80px;
            height: 80px;
            margin: 0 auto 12px;
            background: #eff6ff;
            border-radius: 50%;
            border: 3px solid #dbeafe;
            color: #2563eb;
            font-size: 32px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
        }

        .app-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .app-subtitle {
            font-size: 13px;
            color: #64748b;
            line-height: 1.4;
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
            font-weight: 500;
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

        /* ── Alert Error ── */
        .alert-error {
            background: var(--error-bg);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .field-error {
            font-size: 12px;
            color: var(--error);
            margin-top: 4px;
            font-weight: 600;
        }

        .info-tip {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.4;
        }

        /* ── Submit Button ── */
        .btn-submit {
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
            margin-top: 6px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
        }

        .btn-back-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .btn-back-login:hover {
            color: #0f172a;
        }
    </style>
</head>
<body>
<noscript>
    <div class="noscript-warning-screen">
        <div class="noscript-warning-box">
            <div class="noscript-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="noscript-title">JavaScript Diperlukan</h2>
            <p class="noscript-text">
                Halaman ini membutuhkan JavaScript agar dapat berfungsi dengan baik dan aman. Silakan aktifkan JavaScript di browser Anda untuk melanjutkan.
            </p>
        </div>
    </div>
</noscript>

    <!-- Background Bubbles -->
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="recovery-wrapper">
        <div class="recovery-card">

            @php
                $isResetStep = session('step') === 'reset' && session('token');
            @endphp

            @if(!$isResetStep)
                <!-- ════════ STEP 1: VERIFIKASI IDENTITAS (USERNAME/HP & PIN) ════════ -->
                <div class="logo-area">
                    <div class="logo-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h1 class="app-title">Pemulihan Password</h1>
                    <p class="app-subtitle">Masukkan Username / No. HP dan PIN Pemulihan Akun Anda</p>
                </div>

                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">Verifikasi Akun</span>
                    <div class="divider-line"></div>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="info-tip">
                    <i class="fas fa-shield-alt" style="margin-top: 2px;"></i>
                    <span><strong>Keamanan Akun:</strong> PIN Pemulihan adalah kode rahasia darurat (default: <code>123456</code> untuk akun awal).</span>
                </div>

                <form method="POST" action="{{ route('password.verify') }}" id="verifyForm">
                    @csrf

                    <!-- Identifier (Username atau No HP) -->
                    <div class="form-group">
                        <label for="identifier" class="form-label">Username / No. HP Akun</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user-tag input-icon"></i>
                            <input
                                id="identifier"
                                type="text"
                                name="identifier"
                                class="form-input @error('identifier') is-invalid @enderror"
                                placeholder="Contoh: admin atau 08123456789"
                                value="{{ old('identifier') }}"
                                autofocus
                                required
                            >
                        </div>
                    </div>

                    <!-- PIN Pemulihan -->
                    <div class="form-group">
                        <label for="recovery_pin" class="form-label">PIN Pemulihan Akun</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                id="recovery_pin"
                                type="password"
                                name="recovery_pin"
                                class="form-input has-right-icon @error('recovery_pin') is-invalid @enderror"
                                placeholder="Masukkan PIN darurat akun"
                                required
                            >
                            <i class="fas fa-eye input-icon-right" id="togglePin" title="Tampilkan PIN"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnVerify">
                        <i class="fas fa-check-circle"></i> Verifikasi & Lanjutkan
                    </button>
                </form>

            @else
                <!-- ════════ STEP 2: BUAT PASSWORD BARU ════════ -->
                <div class="logo-area">
                    <div class="logo-icon" style="background: #ecfdf5; border-color: #a7f3d0; color: #059669;">
                        <i class="fas fa-lock-open"></i>
                    </div>
                    <h1 class="app-title">Buat Password Baru</h1>
                    <p class="app-subtitle">Akun terverifikasi: <strong>{{ session('verified_user_name') }}</strong> ({{ '@' . session('verified_user_username') }})</p>
                </div>

                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">Password Baru</span>
                    <div class="divider-line"></div>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ session('token') }}">

                    <!-- Password Baru -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-input has-right-icon @error('password') is-invalid @enderror"
                                placeholder="Minimal 6 karakter"
                                autofocus
                                required
                            >
                            <i class="fas fa-eye input-icon-right" id="togglePwd1" title="Tampilkan password"></i>
                        </div>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-input has-right-icon"
                                placeholder="Ulangi password baru"
                                required
                            >
                            <i class="fas fa-eye input-icon-right" id="togglePwd2" title="Tampilkan password"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnReset">
                        <i class="fas fa-save"></i> Simpan Password Baru
                    </button>
                </form>
            @endif

            <a href="{{ route('login') }}" class="btn-back-login">
                <i class="fas fa-arrow-left"></i> Kembali ke Halaman Login
            </a>

        </div>
    </div>

    <script>
        function setupToggle(toggleId, inputId) {
            const btn = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            if (btn && input) {
                btn.addEventListener('click', function () {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    this.classList.toggle('fa-eye', !isHidden);
                    this.classList.toggle('fa-eye-slash', isHidden);
                });
            }
        }

        setupToggle('togglePin', 'recovery_pin');
        setupToggle('togglePwd1', 'password');
        setupToggle('togglePwd2', 'password_confirmation');

        const verifyForm = document.getElementById('verifyForm');
        if (verifyForm) {
            verifyForm.addEventListener('submit', function () {
                const btn = document.getElementById('btnVerify');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
                btn.disabled = true;
            });
        }

        const resetForm = document.getElementById('resetForm');
        if (resetForm) {
            resetForm.addEventListener('submit', function () {
                const btn = document.getElementById('btnReset');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                btn.disabled = true;
            });
        }
    </script>
</body>
</html>
