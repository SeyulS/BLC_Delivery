@extends('layout.player_room')

@section('script')
<!-- Load jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load DataTables setelah jQuery -->
<script src="https://cdn.datatables.net/2.2.0/js/dataTables.min.js"></script>
@endsection

@section('container')
<div class="container mt-4">
    <h2>Name : {{ $player->player_username }}</h2>
    <br>
    <h3 id="revenue">Revenue: {{ $player->revenue }}</h3> <!-- Tambahkan id untuk elemen revenue -->
</div>

<script>
    $(document).ready(() => {
        const playerId = "{{ $player->player_username }}";
        const roomId = "{{ $roomCode }}";
        window.Echo.channel('player-remove')
            .listen('PlayerRemove', () => {
                window.location.href = "/homePlayer";
            });

        window.Echo.channel('start-simulation')
            .listen('StartSimulation', () => {
                window.location.href = `/player-lobby/${roomId}`;
            }); 

        window.Echo.channel('pause-simulation')
            .listen('PauseSimulation', () => {
                window.location.href = `/player-lobby/${roomId}`;
            });

        window.Echo.channel('update-revenue')
            .listen('UpdateRevenue', () => {
                $.ajax({
                    url: '/updateRevenue',
                    method: 'POST',
                    data: {
                        player_id: playerId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response){
                        if (response.revenue !== undefined) {
                            $('#revenue').text(`Revenue: ${response.revenue}`);
                        }
                    },
                    error: (xhr) => {
                        toastr.error('Failed to fetch revenue:', xhr.responseText);
                    }
                })
            });
    });
</script>

@endsection
