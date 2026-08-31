<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ \App\Models\Setting::current()->app_name }}</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background-color: #f4f6f9;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* Left branding panel */
        .brand-panel {
            background: linear-gradient(160deg, #1e293b 0%, #0d1420 100%);
            color: #fff;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.15);
            top: -150px;
            right: -150px;
        }

        .brand-panel .logo-circle {
            width: 90px;
            height: 90px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
            z-index: 1;
        }

        .brand-panel h1 {
            font-weight: 700;
            font-size: 2.2rem;
            z-index: 1;
        }

        .brand-panel p {
            color: rgba(255,255,255,0.65);
            max-width: 380px;
            text-align: center;
            z-index: 1;
        }

        .brand-panel .feature-list {
            margin-top: 2.5rem;
            z-index: 1;
        }

        .brand-panel .feature-list li {
            color: rgba(255,255,255,0.75);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* Right form panel */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-panel .form-box {
            width: 100%;
            max-width: 380px;
        }

        .form-panel .mobile-logo {
            display: none;
        }

        @media (max-width: 991.98px) {
            .login-wrapper {
                flex-direction: column;
            }

            .brand-panel {
                display: none;
            }

            .form-panel .mobile-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                margin-bottom: 2rem;
            }

            .form-panel .mobile-logo .logo-circle-sm {
                width: 44px;
                height: 44px;
                background: #1e293b;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 1.3rem;
            }

            .form-panel .mobile-logo span {
                font-weight: 700;
                font-size: 1.3rem;
                color: #1e293b;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">

        {{-- ============ LEFT — BRANDING PANEL (hidden on mobile) ============ --}}
        <div class="brand-panel">
            <div class="logo-circle">
                <i class="bi bi-diagram-3"></i>
            </div>
            <h1>{{ \App\Models\Setting::current()->app_name }}</h1>
            <p>Manage your distribution network — companies, distributors, shops, and orders — all in one place.</p>

            <ul class="list-unstyled feature-list">
                <li><i class="bi bi-check-circle-fill text-primary"></i> Real-time GPS visit tracking</li>
                <li><i class="bi bi-check-circle-fill text-primary"></i> Multi-company, role-based access</li>
                <li><i class="bi bi-check-circle-fill text-primary"></i> Orders, stock & sales reports</li>
            </ul>
        </div>

        {{-- ============ RIGHT — LOGIN FORM ============ --}}
        <div class="form-panel">
            <div class="form-box">

                <div class="mobile-logo">
                    <div class="logo-circle-sm">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <span>{{ \App\Models\Setting::current()->app_name }}</span>
                </div>

                <h3 class="fw-bold mb-1">Welcome back</h3>
                <p class="text-muted mb-4">Log in to access your dashboard.</p>

                {{-- Session Status (e.g. password reset confirmation) --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="you@salesconnect.com" required autofocus autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                        Log In
                    </button>
                </form>

            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    </script>

</body>
</html>