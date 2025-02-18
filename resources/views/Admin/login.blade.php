<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <style>
        body {
            background: url('assets/background_login_admin.png') no-repeat center center fixed;
            background-size: cover;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
            overflow: hidden;
        }

        /* Background Logo di belakang input */
        .login-container::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 70%;
            background: url('assets/BLC_Logo.png') no-repeat center;
            background-size: cover;
            opacity: 0.1;
            z-index: 0;
        }

        .login-container form {
            position: relative;
            z-index: 1;
        }

        /* Efek Glow untuk Tombol */
        .btn-delivery {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: box-shadow 0.3s ease-in-out;
        }

        .btn-delivery:hover {
            box-shadow: 0 0 10px rgba(255, 20, 147, 0.7),
                0 0 20px rgba(255, 20, 147, 0.5),
                0 0 30px rgba(255, 20, 147, 0.3);
        }


        /* Styling untuk Link Admin */
        .admin-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        .admin-link a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .admin-link a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="login-container">
            @if(session()->has('loginError'))
            <div class="alert alert-danger">
                {{ session('loginError') }}
            </div>
            @endif
            <h1 class="h3 mb-3 fw-normal text-center blc-title">Login</h1>
            <form action='/loginAdmin' method="post">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="admin_username" name="admin_username" placeholder="Username" required>
                    <label for="admin_username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <button class="btn btn-delivery w-100 py-2" type="submit">Log in</button>
            </form>
        </div>
    </div>
</body>

</html>