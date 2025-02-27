@extends('layout.player_room')

@section('container')
<style>
    .toast-success {
        background-color: #059669 !important;
        /* Green */
    }

    .toast-error {
        background-color: #dc2626 !important;
        /* Red */
    }

    .lobby-container {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .lobby-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }

    .lobby-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .lobby-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .lobby-subtitle {
        color: #64748b;
        font-size: 0.95rem;
    }

    .player-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }

    .player-table thead th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }

    .player-table tbody td {
        padding: 1rem;
        color: #334155;
        font-size: 0.95rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        text-align: center;
        /* Add this line */
    }

    .player-name {
        display: flex;
        align-items: center;
        justify-content: center;
        /* Add this line */
        gap: 0.75rem;
    }

    .player-avatar {
        width: 35px;
        height: 35px;
        background: #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .player-avatar i {
        color: #64748b;
        font-size: 1.1rem;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.5rem;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.9rem;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Custom Loading Animation */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div class="lobby-container">
    <div class="lobby-card">
        <div class="lobby-header">
            <h1 class="lobby-title">
                <i class="bi bi-people-fill me-2"></i>
                Lobby
            </h1>
            <p class="lobby-subtitle">Room ID: {{ $room->room_id }}</p>
        </div>

        <table class="player-table" id="player-datatable">
            <thead>
                <tr>
                    <th class="text-center">Player Name</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
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
        const playerUsername = "{{ $player->player_username }}";
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
                name: 'player_username',
                className: 'text-center', // Add this line
                render: function(data) {
                    return `
                <div class="player-name">
                    ${data}
                </div>
            `;
                }
            }],
            // Add these lines for better center alignment
            language: {
                processing: '<div class="loading-spinner mx-auto"></div>'
            },
            drawCallback: function() {
                $('.dataTables_length, .dataTables_filter').addClass('d-flex justify-content-center');
            }
        });

        window.Echo.channel('update-revenue')
            .listen('.UpdateRevenueEvent', (event) => {
                if (event.playerUsername == playerUsername && event.roomId == roomId) {

                    $.ajax({
                        url: '/updateRevenue',
                        method: 'POST',
                        data: {
                            player_id: playerUsername,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.revenue !== undefined) {
                                const formatCurrency = (number) => {
                                    return new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'USD'
                                    }).format(number);
                                };
                                $('#revenue').html(`: ${formatCurrency(response.revenue)}`);
                                $('#sidebar_revenue').html(formatCurrency(response.revenue));
                                $('#debt').html(`: ${formatCurrency(response.debt)}`);
                                $('#jatuh_tempo').html(`: ${response.jatuh_tempo} days`);
                            }
                        },
                        error: (xhr) => {
                            toastr.error('Failed to fetch revenue:', xhr.responseText);
                        }
                    })

                }
            });

        window.Echo.channel('join-room')
            .listen('.JoinRoomEvent', (event) => {
                var roomIdEvent = event.roomId;
                if (roomId == roomIdEvent) {
                    datatable.ajax.reload();
                    toastr.success('Player Joined');
                }
            });

        window.Echo.channel('player-remove')
            .listen('.PlayerRemoveEvent', (event) => {
                if (event.playerUsername == playerUsername) {
                    window.location.href = '/homePlayer'
                }
                if (event.roomId == roomId) {
                    datatable.ajax.reload();
                }

            });

        window.Echo.channel('start-simulation')
            .listen('.StartSimulationEvent', (event) => {
                if (event.roomId = roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'The simulation has started',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('pause-simulation')
            .listen('.PauseSimulationEvent', (event) => {
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'The simulation was paused',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('resume-simulation')
            .listen('.ResumeSimulationEvent', (event) => {
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'The simulation was resumed',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('next-day')
            .listen('.NextDaySimulationEvent', (event) => {
                console.log(event.roomId, roomId);
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Moving to the next day. Please wait.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('end-simulation')
            .listen('.EndSimulationEvent', (event) => {
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Simulation Ended',
                        text: 'The simulation has ended',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = '/homePlayer';
                    }, 5000);
                }
            });
    });
</script>
@endsection