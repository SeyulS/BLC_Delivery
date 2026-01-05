<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLC Delivery | Admin Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --admin-primary: #dc2626;
            --admin-secondary: #991b1b;
            --admin-accent: #ef4444;
            --dark: #1a1a1a;
            --light: #f3f4f6;
        }

        body {
            background-image: url('assets/background_login_admin.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Add this new container style */
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100vh;
            padding: 0;
            margin: 0;
        }


        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            animation: fadeInDown 0.5s ease;
            margin: 1rem;
        }

        .login-container::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background: url('assets/BLCLogoCircle.png') no-repeat center;
            background-size: contain;
            opacity: 0.05;
            z-index: 0;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .login-subtitle {
            color: #666;
            font-size: 0.95rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }

        .btn-delivery {
            background: linear-gradient(45deg, var(--admin-primary), var(--admin-accent));
            color: white;
            border: none;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-delivery:hover {
            background: linear-gradient(45deg, var(--admin-secondary), var(--admin-primary));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-danger {
            background-color: rgba(220, 38, 38, 0.1);
            color: var(--admin-primary);
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--admin-primary);
        }

        .admin-link {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.85rem;
        }

        .admin-link a {
            color: var(--admin-primary);
            opacity: 0.7;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .admin-link a:hover {
            opacity: 1;
            transform: translateX(3px);
            color: var(--admin-secondary);
        }

        .admin-link i {
            font-size: 0.9rem;
        }


        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h1 class="login-title">
                    <i class="bi bi-shield-lock-fill text-danger"></i>
                    Admin Portal
                </h1>
                <p class="login-subtitle">Secure access for administrators only</p>
            </div>

            @if(session()->has('loginError'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('loginError') }}
            </div>
            @endif

            <form action="/blc-delivery/loginAdmin" method="post">
                @csrf
                <div class="form-floating">
                    <input type="text" class="form-control" id="admin_username" name="admin_username"
                        placeholder="Username" required autocomplete="username">
                    <label for="admin_username">
                        <i class="bi bi-person-fill-lock me-2"></i>Admin Username
                    </label>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Password" required autocomplete="current-password">
                    <label for="password">
                        <i class="bi bi-key-fill me-2"></i>Password
                    </label>
                    <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                </div>

                <button class="btn btn-delivery w-100" type="submit">
                    <i class="bi bi-shield-fill-check me-2"></i>Secure Login
                </button>
            </form>

            <div class="admin-link">
                <a href="/blc-delivery/loginPlayer">
                    <i class="bi bi-person"></i>
                    Player?
                </a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Password visibility toggle
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $(this);

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Form validation and animation
            $('form').on('submit', function(e) {
                const username = $('#admin_username').val();
                const password = $('#password').val();

                if (!username || !password) {
                    e.preventDefault();
                    $('.login-container').addClass('animate__animated animate__shakeX');
                    setTimeout(() => {
                        $('.login-container').removeClass('animate__animated animate__shakeX');
                    }, 1000);
                }
            });
        });
    </script>
</body>

</html>