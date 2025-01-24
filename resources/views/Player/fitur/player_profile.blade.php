@extends('layout.player_room')

@section('container')
<div class="container mt-5">
    <div class="row">
        <!-- Kotak Pertama: Finance -->
        <div class="col-md-4">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">Finance</div>
                <div class="card-body">
                    <h5 class="card-title">Informasi Keuangan</h5>
                    <p id="revenue"><strong>Revenue:</strong> ${{ number_format($player->revenue, 2) }}</p>
                    <p id="debt"><strong>Pinjaman:</strong> ${{ number_format($player->debt, 2) }}</p>
                    <p id="jatuh_tempo"><strong>Jatuh Tempo:</strong> {{ $player->jatuh_tempo }} days</p>
                </div>
            </div>
        </div>

        <!-- Kotak Kedua: Warehouse -->
        <div class="col-md-4">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white">Warehouse</div>
                <div class="card-body">
                    <p id="warehouseCapacity"><strong>Warehouse Capacity:</strong> {{ $player->inventory }} m²</p>
                    <p id="currentCapacity"><strong>Used Capacity:</strong> {{ $usedCapacity }} m² <strong>/ {{ $player->inventory }} m²</strong></p>

                    <div class="row">
                        <div class="col-6">
                            <p><strong>Raw Items:</strong></p>
                            <ul>
                                @for ($i = 0; $i < count($roomRawItem); $i++)
                                    <li>{{ $roomRawItem[$i]->raw_item_name }} : {{ $playerRawItem[$i] }}</li>
                                    @endfor
                            </ul>
                        </div>

                        <!-- Finished Items -->
                        <div class="col-6">
                            <p><strong>Finished Items:</strong></p>
                            <ul>
                                @for ($i = 0; $i < count($roomItem); $i++)
                                    <li>{{ $roomItemName[$i] }} : {{ $playerItemQty[$i] }}</li>
                                    @endfor
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Kotak Ketiga: Machine -->
        <div class="col-md-4">
            <div class="card border-warning mb-3">
                <div class="card-header bg-warning text-dark">Machine</div>
                <div class="card-body">
                    @for ($i = 0; $i < count($roomMachine); $i++)
                        <p><strong>{{ $roomMachineName[$i] }} Capacity:</strong> {{ $playerMachineCapacity[$i] }} units</p>
                        @endfor

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        const playerId = "{{ $player->player_username }}";
        const roomId = "{{ $room->room_id }}";
        window.Echo.channel('player-remove')
            .listen('PlayerRemove', () => {
                window.location.href = "/homePlayer";
            });

        setupSimulationEvents(roomId);

        window.Echo.channel('update-warehouse')
            .listen('UpdateWarehouse', () => {
                $.ajax({
                    url: '/updateWarehouse',
                    method: 'POST',
                    data: {
                        player_id: playerId,
                        room_id: roomId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.warehouseCapacity !== undefined) {
                            $('#warehouseCapacity').html(`<strong>Capacity:</strong> ${response.warehouseCapacity} m²`);
                            $('#currentCapacity').html(`<strong>Capacity:</strong> ${response.currentCapacity} m² / ${response.warehouseCapacity} m²` );
                        }
                    },
                    error: (xhr) => {
                        toastr.error('Failed to fetch revenue:', xhr.responseText);
                    }
                })
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
                    success: function(response) {
                        if (response.revenue !== undefined) {

                            const formatCurrency = (number) => {
                                return new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD'
                                }).format(number);
                            };
                            $('#revenue').html(`<strong>Revenue:</strong> ${formatCurrency(response.revenue)}`);
                            $('#debt').html(`<strong>Pinjaman:</strong> ${formatCurrency(response.debt)}`);
                            $('#jatuh_tempo').html(`<strong>Jatuh Tempo:</strong> ${response.jatuh_tempo} days`);

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