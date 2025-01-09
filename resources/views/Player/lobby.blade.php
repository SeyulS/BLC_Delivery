@extends('layout.main')

@section('script')
<!-- Load jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load DataTables setelah jQuery -->
<script src="https://cdn.datatables.net/2.2.0/js/dataTables.min.js"></script>
@endsection

@section('container')
<div class="container mt-4">
    <h2>Name : {{ Auth::guard('player')->user()->player_username }}</h2>
    <br>
    <h3>Players   in Room {{ $room_id }}</h3>
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
        const roomId = "{{ $room_id }}";
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

        window.Echo.channel('join-room')
            .listen('PlayerJoin', () => {
                console.log('player join');
                datatable.ajax.reload();
            });

        window.Echo.channel('player-remove')
            .listen('PlayerRemove', () => {
                window.location.href = "/homePlayer"
            });

    });
</script>
@endsection