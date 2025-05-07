<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title') | Admin
    </title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/b  ootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.0/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.17.0/echo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('script')
    <style>
        /* General Styles */
        a {
            text-decoration: none;
        }

        /* Profile styles */
        .sidebar-profile {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .profile-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-image {
            min-width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .profile-info {
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        /* Collapsed state */
        #sidebar.collapse .sidebar-profile {
            padding: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #sidebar.collapse .profile-wrapper {
            justify-content: center;
        }

        #sidebar.collapse .profile-info {
            width: 0;
            opacity: 0;
            visibility: hidden;
        }

        #sidebar.collapse .profile-image {
            min-width: 35px;
            height: 35px;
            margin: 0;
        }

        /* Admin Profile Text Colors */
        .admin-name {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0;
        }

        .admin-role {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        .admin-role.super-admin {
            color: #fcd34d;
            /* Gold color for super admin */
            font-weight: 600;
        }

        .admin-role.regular-admin {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .profile-image i {
            color: #ffffff;
            font-size: 1.25rem;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        @include('partials.admin_sidebar_home')
        <div class="main p-3">
            @yield('container')

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
</body>

</html>