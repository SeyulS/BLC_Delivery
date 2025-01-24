@extends('layout.admin_room')

@section('script')
<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load DataTables -->
<script src="https://cdn.datatables.net/2.2.0/js/dataTables.min.js"></script>
@endsection

@section('container')
@section('room_id')
{{ $room->room_id }}
@endsection

<div class="container mt-4">
    <h3>Room {{ $room->room_id }}</h3>

    <!-- Tabel Player -->
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

    <!-- Tombol Action -->
    <div class="mt-3">
        <form action="/startSimulation" method="POST" class="d-inline-block">
            @csrf
            @if ($room->status == 1)
            <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-primary" disabled>Start Simulation</button>
            @else
            <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-primary">Start Simulation</button>
            @endif
        </form>
        <form action="/pauseSimulation" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-primary">Pause Simulation</button>
        </form>
        <form action="/resumeSimulation" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-primary">Resume Simulation</button>
        </form>
        <form action="/nextDaySimulation" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-primary">Next Day</button>
        </form>
        <form action="/endSimulation" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-primary">End Simulation</button>
        </form>
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
                    orderable: false,
                    searchable: false,
                    render: (data) => {
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

        // Listen for player join event and reload table
        window.Echo.channel('join-room')
            .listen('PlayerJoin', () => {
                console.log('Player joined');
                datatable.ajax.reload();
            });

        // Handle player kick
        $('#player-datatable').on('submit', '.form-delete', function(e) {
            e.preventDefault();

            const form = this;
            const playerId = $(form).find('.kick-player').data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to kick this player?',
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
                            player_id: playerId,
                            _token: '{{ csrf_token() }}',
                        },
                        success: (response) => {
                            toastr.success(response.message);
                            datatable.ajax.reload();
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