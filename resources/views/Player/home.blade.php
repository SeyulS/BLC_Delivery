@extends('layout.main')
@section('container')
    <div class="container centered-form">
        <div class="row">
            <div class="col-md-4">
                <h1 class="text-center mb-4">Welcome, {{ Auth::guard('player')->user()->player_username }}</h1>
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-danger">
                        {{ session('success') }}
                    </div>
                @endif
                <form action='/joinRoom' method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="roomCode" class="text-center d-block">Room Code</label>
                        <input type="text" class="form-control room-code-input mx-auto" name="roomCode" id="roomCode" maxlength="3" placeholder="XXX">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
                <br>
                <form action='/logoutPlayer' method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block">Logout</button>
                </form>
            </div>
        </div>
    </div>
@endsection