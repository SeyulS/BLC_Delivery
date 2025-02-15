@extends('layout.admin_room')

@section('container')
<style>
    :root {
        --primary-color: #2563eb;
        --danger-color: #dc2626;
        --success-color: #16a34a;
        --warning-color: #d97706;
    }

    .card {
        border-radius: 12px;
        transition: transform 0.2s;
    }

    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .table> :not(caption)>*>* {
        padding: 1rem 1.5rem;
        vertical-align: middle;
    }

    .table>tbody>tr:hover {
        background-color: #f8fafc;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .kick-btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        background-color: var(--danger-color);
        border-color: var(--danger-color);
        color: white;
    }

    .kick-btn:hover {
        background-color: #b91c1c;
        border-color: #b91c1c;
    }
</style>

<div class="container py-4">
    <!-- Header Section -->
    <div class="lobby-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Room #{{ $room->room_id }}</h2>
            </div>
            <div class="lobby-status">
                @if ($room->start == 1)
                <span class="badge bg-success px-3 py-2">
                    <i class="fas fa-play-circle me-2"></i>Simulation Running
                </span>
                @else
                <span class="badge bg-warning px-3 py-2">
                    <i class="fas fa-clock me-2"></i>Waiting to Start
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Players List Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-users me-2 text-primary"></i>Players in Room
                            </h5>
                            <span class="badge bg-light text-dark px-3 py-2" id="player-count">
                                Loading players...
                            </span>
                        </div>
                        <table class="table table-hover mb-0" id="player-datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Player Name</th>
                                    <th class="px-4 py-3">Score</th>
                                    <th class="px-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control Panel Section -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-gamepad me-2 text-primary"></i>Simulation Controls
                    </h5>
                    <div class="d-grid gap-3">
                        <form action="/startSimulation" method="POST">
                            @csrf
                            <button type="submit" name="room_id" value="{{ $room->room_id }}"
                                class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center"
                                {{ $room->start == 1 ? 'disabled' : '' }}>
                                <i class="fas fa-play me-2"></i>Start Simulation
                            </button>
                        </form>

                        <div class="btn-group w-100">
                            <form action="/pauseSimulation" method="POST" class="w-50">
                                @csrf
                                <button type="submit" name="room_id" value="{{ $room->room_id }}"
                                    class="btn btn-outline-primary w-100">
                                    <i class="fas fa-pause me-2"></i>Pause
                                </button>
                            </form>
                            <form action="/resumeSimulation" method="POST" class="w-50">
                                @csrf
                                <button type="submit" name="room_id" value="{{ $room->room_id }}"
                                    class="btn btn-outline-primary w-100">
                                    <i class="fas fa-play me-2"></i>Resume
                                </button>
                            </form>
                        </div>

                        <form action="/nextDaySimulation" method="POST">
                            @csrf
                            <button type="submit" name="room_id" value="{{ $room->room_id }}"
                                class="btn btn-success w-100">
                                <i class="fas fa-forward me-2"></i>Next Day
                            </button>
                        </form>

                        <form action="/endSimulation" method="POST">
                            @csrf
                            <button type="submit" name="room_id" value="{{ $room->room_id }}"
                                class="btn btn-danger w-100">
                                <i class="fas fa-stop-circle me-2"></i>End Simulation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        const roomId = "{{ $room->room_id }}";

        // Initialize DataTable
        const datatable = $('#player-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `/api/players/${roomId}`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [{
                    data: 'player_username',
                    name: 'player_username'
                },
                {
                    data: 'score',
                    name: 'score'
                },
                {
                    data: 'player_username',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: (data) => {
                        return `
<div class="text-end">
    <form action='kick-player' method='POST' class='form-delete'>
        @csrf
        <button type="submit" class="btn kick-btn" data-username="${data}">
            <i class="bi bi-x-circle"></i>
        </button>
    </form>
</div>`;
                    },

                },
            ],

        });

        // Listen for player join event and reload table
        window.Echo.channel('join-room')
            .listen('.JoinRoomEvent', (event) => {
                var roomIdEvent = event.roomId;
                if (roomId == roomIdEvent) {
                    datatable.ajax.reload();
                    toastr.success('Player Joined');
                }
            });

        // Handle player kick
        $('#player-datatable').on('submit', '.form-delete', function(e) {
            e.preventDefault();

            const form = this;
            const playerUsername = $(form).find('.kick-btn').data('username'); // Ambil player_username
            console.log(playerUsername);
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you really want to kick ${playerUsername}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, kick them!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/kick-player',
                        type: 'POST',
                        data: {
                            player_username: playerUsername,
                            _token: '{{ csrf_token() }}',
                        },
                        success: (response) => {
                            console.log(response);
                            toastr.success(response.message);
                            $('#player-datatable').DataTable().ajax.reload();
                        },
                        error: (xhr) => {
                            const errorMsg = xhr.responseJSON?.message || 'Failed to kick player';
                            toastr.error(errorMsg);
                        },
                    });
                }
            });
        });

    });
</script>
@endsection