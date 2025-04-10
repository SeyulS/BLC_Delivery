@extends('layout.admin_home')

@section('container')
<style>
    .account-dashboard {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 10px;
        font-size: 1.25rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .form-label {
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .btn-primary {
        background: #0284c7;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #0369a1;
        transform: translateY(-1px);
    }

    .player-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .player-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .player-table td {
        padding: 1rem;
        color: #1e293b;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
    }

    .player-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f1f5f9;
        border-radius: 20px;
        font-weight: 500;
    }

    .player-badge i {
        color: #0284c7;
    }

    .btn-danger {
        background: #ef4444;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .toast-success {
        background-color: #10b981 !important;
        border-radius: 8px !important;
    }

    .toast-error {
        background-color: #ef4444 !important;
        border-radius: 8px !important;
    }
</style>

<div class="account-dashboard">
    <div class="container">
        <!-- Create Player Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h4 class="card-title">Create New Player</h4>
            </div>

            <form action="/registPlayer" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="player_username" class="form-label">Username</label>
                            <input type="text" name="player_username" id="player_username" 
                                   class="form-control" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="confirmation_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirmation_password" 
                                   class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create Player
                    </button>
                </div>
            </form>
        </div>

        <!-- Player List Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4 class="card-title">Player List</h4>
            </div>

            <div class="table-responsive">
                <table id="PlayerListTable" class="player-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($players as $player)
                        <tr id="player-row-{{ $player->player_username }}">
                            <td>
                                <div class="player-badge">
                                    <i class="bi bi-person"></i>
                                    {{ $player->player_username }}
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-player" 
                                        data-username="{{ $player->player_username }}">
                                    <i class="bi bi-trash me-1"></i>
                                    Delete
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

<script>
$(document).ready(function() {
    const table = $('#PlayerListTable').DataTable({
        pageLength: 10,
        dom: '<"table-header"lf>rt<"table-footer"ip>',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search players..."
        }
    });

    $('.delete-player').click(function() {
        const username = $(this).data('username');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Delete Player?',
            text: `Are you sure you want to delete ${username}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/deletePlayer',
                    type: 'POST',
                    data: {
                        player_username: username,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            row.remove();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while deleting the player.');
                    }
                });
            }
        });
    });
});
</script>
@endsection