@extends('layout.player_room')

@section('script')
<!-- Load jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load DataTables setelah jQuery -->
<script src="https://cdn.datatables.net/2.2.0/js/dataTables.min.js"></script>
@endsection

@section('container')
<div class="container mt-4">
    <h2>Warehouse And Machine</h2>
    <br>
    
    <h4>Raw Items Inventory</h4>
    @for ($i = 0; $i < count($roomRawItem); $i++)  
        {{ $roomRawItem[$i]->raw_item_name }} : {{ $playerRawItem[$i] }}
        <br>
    @endfor
    <br>
    <h4>Items Inventory</h4>
    @for ($i = 0; $i < count($roomItem); $i++)  
        {{ $roomItemName[$i] }} : {{ $playerItemQty[$i] }}
        <br>
    @endfor

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

        
    });
</script>

@endsection
