@extends('layout.player_home')
@section('container')
    <div class="container centered-form">
        <div class="row justify-content-center align-items-center"> <!-- Add justify-content-center and align-items-center classes -->
            <div class="col-md-4">
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action='/joinRoom' method="POST" id="joinForm">
                    @csrf
                    <div class="form-group">
                        <label for="roomCode" class="text-center d-block">Room Code</label>
                        <input type="text" class="form-control room-code-input mx-auto" name="roomCode" id="roomCode" maxlength="3" placeholder="XXX">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary btn-block">Join</button>
                </form>
                <br>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const joinForm = document.getElementById('joinForm');

            joinForm.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Loading...',
                    text: 'Joining the room, please wait...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading(); 
                    }
                });

                setTimeout(() => {
                    joinForm.submit();
                }, 500);
            });
        });
    </script>
@endsection
