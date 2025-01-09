<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Boostrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- My Style -->
    <link rel="stylesheet" href="css/style.css">

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

</head>

<body>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <main class="form-signin w-100 m-auto">
                @if(session()->has('loginError'))
                <div>
                    Tai
                </div>
                @endif
                <h1 class="h3 mb-3 fw-normal text-center">Player Login</h1>
                <form action='/loginPlayer' method="post">
                    @csrf
                    <div class="form-floating">
                        <input type="text" class="form-control" id="player_username" name="player_username">
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <label for="floatingPassword">Password</label>
                    </div>
                    <button class="btn btn-primary w-100 py-2" type="submit">Log in</button>
                </form>
                <small class="d-block text-center mt-3"><a href="/loginAdmin">Admin? </a></small>
            </main>
        </div>
    </div>
</body>

</html>