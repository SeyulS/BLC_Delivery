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