@extends('layout.admin_room')
@section('title')
    Lobby {{ $room->room_id }}
@endsection
@section('container')
<style>
    .table-warning {
        background-color: #fff3cd !important;
    }

    :root {
        --primary-color: #2563eb;
        --danger-color: #dc2626;
        --success-color: #16a34a;
        --warning-color: #d97706;
    }

    .toast-success {
        background-color: #059669 !important;
        /* Green */
    }

    .toast-error {
        background-color: #dc2626 !important;
        /* Red */
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


    .day-display {
        background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
        color: white;
        padding: 1.25rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.1);
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .day-label {
        font-size: 1.1rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
    }

    .day-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }

    .lobby-header {
        display: grid;
        grid-template-columns: 1fr auto auto;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
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
                            <span class="badge bg-light text-dark px-3 py-2" id="player-count">
                                <strong>Current Day : {{ $room->recent_day }}</strong>
                            </span>
                            <span class="badge bg-light text-dark px-3 py-2" id="player-count">
                                <strong>Simulation Duration : {{ $room->max_day }}</strong>
                            </span>
                        </div>
                        <table class="table table-hover mb-0" id="player-datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Player Name</th>
                                    <th class="px-4 py-3">Cash</th>
                                    <th class="px-4 py-3">Debt</th>
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
                        <button id="start-btn" class="btn btn-primary w-100" {{ $room->start == 1 ? 'disabled' : '' }}>
                            <i class="fas fa-play me-2"></i>Start Simulation
                        </button>
                        <div class="btn-group w-100">
                            <button id="pause-btn" class="btn btn-outline-primary w-50">
                                <i class="fas fa-pause me-2"></i>Pause
                            </button>
                            <button id="resume-btn" class="btn btn-outline-primary w-50">
                                <i class="fas fa-play me-2"></i>Resume
                            </button>
                        </div>
                        <button id="next-day-btn" class="btn btn-success w-100">
                            <i class="fas fa-forward me-2"></i>Next Day
                        </button>
                        <button id="end-btn" class="btn btn-danger w-100 {{ $room->finished == 1 ? 'disabled' : '' }}">
                            <i class="fas fa-stop-circle me-2"></i>End Simulation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
        }

        const roomId = "{{ $room->room_id }}";
        const highestLoan = parseFloat("{{ $highestLoan }}");

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
                    data: 'revenue',
                    name: 'revenue',
                    render: (data) => {
                        console.log('Revenue Data:', data); // Debugging
                        const value = parseFloat(data);
                        return isNaN(value) || value === 0 ? '-' : `Rp ${value.toLocaleString('id-ID')}`;
                    }
                },
                {
                    data: 'debt',
                    name: 'debt',
                    render: (data) => {
                        console.log('Revenue Data:', data); // Debugging
                        const value = parseFloat(data);
                        return isNaN(value) || value === 0 ? '-' : `Rp ${value.toLocaleString('id-ID')}`;
                    }
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
            createdRow: function(row, data, dataIndex) {
                let revenue = parseFloat(data.revenue);
                if (revenue + highestLoan <= 0) {
                    $(row).addClass('table-warning'); // Tambahkan warna highlight
                }
            }
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
                        url: '/lobby/kick-player',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            player_username: playerUsername,
                            room_id: roomId
                        },
                        success: (response) => {
                            toastr.success(response.message);
                            $('#player-datatable').DataTable().ajax.reload();
                        },
                        error: (xhr) => {
                            const errorMsg = xhr.responseJSON?.message ||
                                'Failed to kick player';
                            toastr.error(errorMsg);
                        }
                    });
                }
            });
        });
        const confirmAction = (title, text, actionUrl) => {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(actionUrl, {
                        _token: '{{ csrf_token() }}',
                        room_id: roomId
                    }, (response) => {
                        if (response.status === 'error') {
                            toastr.error(response.message);
                            return;
                        }
                        toastr.success(response.message);
                    }).fail((xhr) => {
                        toastr.error(xhr.responseJSON?.message || 'Action failed');
                    });
                }
            });
        };

        $('#start-btn').click(() => confirmAction('Start Simulation?', 'This will begin the simulation.', '/startSimulation'));
        $('#pause-btn').click(() => confirmAction('Pause Simulation?', 'The simulation will be paused.', '/pauseSimulation'));
        $('#resume-btn').click(() => confirmAction('Resume Simulation?', 'The simulation will continue.', '/resumeSimulation'));
        $('#next-day-btn').click(() => confirmAction('Next Day?', 'Proceed to the next day in simulation.', '/nextDaySimulation'));
        $('#end-btn').click(() => confirmAction('End Simulation?', 'This will permanently end the simulation.', '/endSimulation'));

    });
</script>
@endsection