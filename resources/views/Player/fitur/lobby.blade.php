@extends('layout.player_room')

@section('container')
<div class="container mt-4">
    <div class="col-md-12 mt-4">
        <div class="p-4" style="background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <table class="table text-center w-75" id="player-datatable">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle">Player Name</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
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
                name: 'player_username'
            }, ],
        });

        window.Echo.channel('join-room')
            .listen('.JoinRoomEvent', (event) => {
                if (event.roomId == roomId) {
                    window.location.href = "/homePlayer"
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

    });
</script>
@endsection