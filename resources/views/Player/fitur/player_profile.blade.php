@extends('layout.player_room')

@section('container')
<div class="container mt-5">
    <div class="row">
        <!-- Kotak Pertama: Finance -->
        <div class="col-md-3">
            <div class="p-4 shadow-sm h-100" style="background-color: white; border-radius: 8px;">
                <div class="text-start">
                    <h5>Finance</h5>
                    <hr>
                </div>
                <div class="card-body mt-4">
                    <div class="row mb-3">
                        <div class="col-4"><strong>Revenue</strong></div>
                        <div class="col-8 text-start" id="revenue">: ${{ number_format($player->revenue, 2) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4"><strong>Pinjaman</strong></div>
                        <div class="col-8 text-start" id="debt">: ${{ number_format($player->debt, 2) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4"><strong>Jatuh Tempo</strong></div>
                        <div class="col-8 text-start" id="jatuh_tempo">: <span style="color: red;">( {{ $jatuh_tempo }} more days )</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kotak Kedua: Warehouse -->
        <div class="col-md-6">
            <div class="p-4 shadow-sm h-100" style="background-color: white; border-radius: 8px;">
                <div class="text">
                    <h5>Warehouse</h5>
                    <hr>
                </div>
                <div class="card-body mt-4">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-4"><strong>Warehouse Capacity</strong></div>
                                <div class="col-8 text-start" id="revenue">: {{ $player->inventory }} m²</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-4"><strong>Used Capacity</strong></div>
                                <div class="col-8 text-start" id="revenue">: {{ $usedCapacity }} m² <strong>/ {{ $player->inventory }} m²</strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Raw items -->
                        <div class="col-8 text-start">
                            <p><strong>Raw Items:</strong></p>
                            <ul class="list-unstyled">
                                <div class="row">
                                    @for ($i = 0; $i < count($roomRawItem); $i++)
                                        <div class="col-5 mb-1">
                                        <div class="row">
                                            <div class="col-6 text-start">{{ $roomRawItem[$i]->raw_item_name }}</div>
                                            <div class="col-6 text-start" id="revenue">: {{ $playerRawItem[$i] }}</div>
                                        </div>
                                </div>
                                @endfor
                        </div>
                        </ul>
                    </div>

                    <!-- Finished Items -->
                    <div class="col-4 text-start">
                        <p><strong>Finished Items:</strong></p>
                        <ul class="list-unstyled">
                            @for ($i = 0; $i < count($roomItem); $i++)
                                <li class="d-flex justify-content-center mb-1">
                                <div class="col-4 text-start">{{ $roomItemName[$i] }}</div>
                                <div class="col-7 text-start" id="revenue">: {{ $playerItemQty[$i] }}</div>
                                </li>
                                @endfor
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Kotak Ketiga: Machine -->
    <div class="col-md-3">
        <div class="p-4 shadow-sm h-100" style="background-color: white; border-radius: 8px;">
            <div class="text">
                <h5>Machine</h5>
                <hr>
            </div>
            <div class="card-body mt-4">
                @for ($i = 0; $i < count($roomMachine); $i++)
                    <div class="row mb-3">
                    <div class="col-8"><strong>{{ $roomMachineName[$i] }} Capacity</strong></div>
                    <div class="col-4 text-start" id="revenue"> : {{ $playerMachineCapacity[$i] }} units</div>
            </div>
            @endfor
        </div>
    </div>
</div>
</div>

<!-- Tabel List of Demand -->
<div class="col-md-12 mt-4">
    <div class="p-4" style="background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <div class="text-start">
            <h5>List Of Demands</h5>
            <hr>
        </div>
        <div class="mt-3">
            <table id="demandTable" class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th>Demand ID</th>
                        <th>Tujuan</th>
                        <th>Need Day</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($demands as $demand)
                    <tr>
                        <td>{{ $demand->demand_id }}</td>
                        <td>{{ $demand->tujuan_pengiriman }}</td>
                        <td>{{ $demand->need_day }}</td>
                        <td>{{ $demand->item->item_name }}</td>
                        <td>{{ $demand->quantity }}</td>
                        <td>${{ number_format($demand->revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        // Inisialisasi DataTables
        $('#demandTable').DataTable();

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
                        if (response.warehouseCapacity !== undefined) {
                            $('#warehouseCapacity').html(`<strong>Capacity:</strong> ${response.warehouseCapacity} m²`);
                            $('#currentCapacity').html(`<strong>Capacity:</strong> ${response.currentCapacity} m² / ${response.warehouseCapacity} m²`);
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