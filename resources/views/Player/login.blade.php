<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLC Delivery - Login</title>

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
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --background-color: #1a1a1a;
            --text-color: #333;
            --error-color: #dc2626;
            --success-color: #059669;
        }

        body {
            background-image: url('assets/background_login_player.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .page-container {
            width: 100%;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #666;
            font-size: 0.95rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-delivery {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
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
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
        }

        .btn-delivery:active {
            transform: translateY(0);
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
            color: var(--error-color);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .form-floating label {
            padding: 1rem 0.75rem;
        }

        .managed-by {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.875rem;
            color: #666;
        }

        .blc-logo {
            width: 80px;
            height: auto;
            filter: none;
            transition: all 0.3s ease;
        }

        .blc-logo:hover {
            transform: scale(1.05);
        }

        .copyright {
            font-size: 0.7rem;
            color: var(--text-color);
            opacity: 0.6;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="login-container">
            <div class="login-header">
                <h1 class="login-title">Welcome Back!</h1>
                <p class="login-subtitle">Please login to continue to BLC Delivery</p>
            </div>

            @if(session()->has('loginError'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle"></i>
                {{ session('loginError') }}
            </div>
            @endif

            <form action="/loginPlayer" method="post">
                @csrf
                <div class="form-floating">
                    <input type="text" class="form-control" id="player_username" name="player_username"
                        placeholder="Username" required autocomplete="username">
                    <label for="player_username">
                        <i class="bi bi-person me-2"></i>Username
                    </label>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Password" required autocomplete="current-password">
                    <label for="password">
                        <i class="bi bi-lock me-2"></i>Password
                    </label>
                    <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                </div>

                <button class="btn btn-delivery w-100" type="submit">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Log in
                </button>

            </form>

            <div class="admin-link">
                <a href="/loginAdmin">
                    <i class="bi bi-person"></i>
                    BLC Administrator?
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
                const username = $('#player_username').val();
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