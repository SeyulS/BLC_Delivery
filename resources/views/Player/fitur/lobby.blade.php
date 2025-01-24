@extends('layout.player_room')

@section('container')
<div class="container mt-4">

    <table class="table text-center w-100" id="player-datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Player Name</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    $(document).ready(() => {
        const roomId = "{{ $room->room_id }}";
        const datatable = $('#player-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `/api/players/${roomId}`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'player_username',
                    name: 'player_username'
                },
            ],
        });

        setupSimulationEvents(roomId);

        window.Echo.channel('join-room')
            .listen('PlayerJoin', () => {
                datatable.ajax.reload();
            });

        window.Echo.channel('player-remove')
            .listen('PlayerRemove', () => {
                window.location.href = "/homePlayer"
            });

    });
</script>
@endsection