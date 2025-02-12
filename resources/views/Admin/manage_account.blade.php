@extends('layout.admin_home')

@section('container')
<div class="container mt-5">
    <!-- Tampilkan pesan sukses -->
    @if(session('success'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ session("success") }}');
        });
    </script>
    @endif

    <!-- Tampilkan pesan error -->
    @if($errors->any())
    <script>
        $(document).ready(function() {
            @foreach($errors->all() as $error)
            toastr.error('{{ $error }}');
            @endforeach
        });
    </script>
    @endif


    <!-- Form Create Player -->
    <div id="createRoomForm" class="form-box shadow-sm p-4 rounded mb-4">
        <h4>Create Player</h4>
        <hr>
        <form action="/registPlayer" method="POST">
            @csrf
            <div class="mb-3">
                <label for="player_username" class="form-label">Username</label>
                <input type="text" name="player_username" id="player_username" class="form-control" placeholder="Enter username" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="confirmation_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirmation_password" id="confirmation_password" class="form-control" placeholder="Confirm password" required>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Create Player</button>
            </div>
        </form>
    </div>

    <!-- Players -->
    <div class="col-md-12 mt-4">
        <div class="p-4" style="background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div class="text-start">
                <h5>List Of Players</h5>
                <hr>
            </div>
            <div class="mt-3">
                <table id="PlayerListTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Player Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($players as $player)
                        <tr id="player-row-{{ $player->player_username }}">
                            <td>{{ $player->player_username }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-player" data-username="{{ $player->player_username }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        const table = $('#PlayerListTable').DataTable();

        $(document).on('click', '.delete-player', function() {
            const username = $(this).data('username');
            const row = $(this).closest('tr'); // Temukan baris terkait

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete the player "${username}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna menekan "Yes"
                    $.ajax({
                        url: `/deletePlayer`, // Endpoint untuk delete
                        type: 'POST',
                        data: {
                            'player_username': username,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.row(row).remove().draw(false);
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'An error occurred. Please try again.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

@endsection