@extends('layout.main_room')

@section('script')
<!-- Load jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load DataTables setelah jQuery -->
<script src="https://cdn.datatables.net/2.2.0/js/dataTables.min.js"></script>
@endsection

@section('container')
@section('room_id')
{{ $room_id }}
@endsection
<div class="container mt-4">
    <h3>Players in Room {{ $room_id }}</h3>
    <table class="table text-center w-100" id="player-datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Player Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    $(document).ready(() => {
        window.Echo.channel('join-room')
            .listen('PlayerJoin', () => {
                console.log('player join');
                datatable.ajax.reload();
            });

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
                {
                    data: 'id',
                    name: 'action',
                    orderable: true,
                    searchable: true,
                    render: (data, type, row) => {
                        return `
                        <form action='kick-player' method='POST' class='form-delete'>
                            @csrf
                            <button class="btn btn-danger btn-sm kick-player" data-id="${data}">
                                Kick
                            </button>
                        </form>`;
                    },
                },
            ],
        });

        $('#player-datatable').on('submit', '.form-delete', function(e) {
            e.preventDefault();
            const form = this;
            const playerId = $(form).find('.kick-player').data('id');
            console.log(playerId);

            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '/kick-player',
                    type: 'POST',
                    data: {
                        player_id: playerId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        datatable.ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Failed to delete user');
                        }
                    },
                });

            }
        });

    });
</script>
@endsection