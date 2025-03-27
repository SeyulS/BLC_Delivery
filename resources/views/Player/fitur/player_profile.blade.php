@extends('layout.player_room')
@section('title')
BLC Delivery | Financial & Inventory
@endsection
@section('container')
<style>
    :root {
        --primary-color: #4361ee;
        --success-color: #2ea44f;
        --warning-color: #ff9800;
        --danger-color: #dc3545;
    }

    .dashboard-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #718096;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .progress-bar-custom {
        height: 8px;
        border-radius: 4px;
        background-color: #e2e8f0;
        margin: 1rem 0;
    }

    .capacity-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .inventory-item {
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-radius: 8px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .inventory-item:hover {
        background-color: #edf2f7;
        transform: translateX(5px);
    }

    .status-badge {
        padding: 0.2rem 0.7rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .machine-stat {
        border-left: 4px solid var(--primary-color);
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f8fafc;
        border-radius: 0 8px 8px 0;
    }

    .demand-table th {
        background-color: #f8fafc;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }

</style>

<div class="container-fluid py-4">

    <div class="row g-4">
        <!-- Finance Card -->
        <div class="col-md-3">
            <div class="dashboard-card h-100 p-4">
                <div class="section-title">
                    <i class="fas fa-wallet"></i> Financial Overview
                </div>
                <div class="mb-3">
                    <div class="stat-label">Cash On Hand</div>
                    <div class="stat-value text-success" id="revenue">${{ number_format($player->revenue, 2) }}</div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Current Debt</div>
                    <div class="stat-value text-danger" id="debt">${{ number_format($player->debt, 2) }}</div>
                </div>
                <div>
                    <div class="stat-label">Due Date</div>
                    @if ($jatuh_tempo == null)
                    <div class="status-badge bg-success text-white" id="jatuh_tempo">No Active Debt</div>
                    @else
                    <div class="status-badge bg-danger text-white" id="jatuh_tempo">{{ $jatuh_tempo }} days remaining</div>
                    @endif
                </div>
                

            </div>
        </div>

        <!-- Warehouse Card -->
        <div class="col-md-6">
            <div class="dashboard-card h-100 p-4">
                <div class="section-title">
                    <i class="fas fa-warehouse"></i> Warehouse Status
                </div>

                @php
                $capacityPercentage = $player->inventory > 0 ?
                min(($usedCapacity / $player->inventory) * 100, 100) : 0;

                $barColor = $capacityPercentage >= 90 ? 'bg-danger' :
                ($capacityPercentage >= 75 ? 'bg-warning' : 'bg-primary');
                @endphp

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-label">Storage Usage</div>
                        <div>{{ $usedCapacity }} m² / {{ $player->inventory }} m²</div>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="capacity-bar {{ $barColor }}"
                            style="width: {{ $capacityPercentage }}%"></div>
                    </div>
                </div>

                <div class="row">
                    <!-- Raw Materials -->
                    <div class="col-md-4">
                        <h6 class="section-title">
                            <i class="fas fa-box"></i> Raw Materials
                        </h6>
                        @forelse ($roomRawItem as $index => $item)
                        <div class="inventory-item d-flex justify-content-between align-items-center">
                            <span>{{ $item->raw_item_name }}</span>
                            <span class="badge bg-primary">{{ $playerRawItem[$index] }}</span>
                        </div>
                        @empty
                        <div class="text-muted">No raw materials available</div>
                        @endforelse
                    </div>

                    <!-- Finished Products -->
                    <div class="col-md-4">
                        <h6 class="section-title">
                            <i class="fas fa-box-open"></i> Finished Products
                        </h6>
                        @forelse ($roomItem as $index => $item)
                        <div class="inventory-item d-flex justify-content-between align-items-center">
                            <span>{{ $roomItemName[$index] }}</span>
                            <span class="badge bg-secondary">{{ $playerItemQty[$index] }}</span>
                        </div>
                        @empty
                        <div class="text-muted">No finished products available</div>
                        @endforelse
                    </div>
                    <div class="col-md-4">
                        <h6 class="section-title">
                            <i class="fas fa-tools"></i> Machine
                        </h6>
                        @forelse ($roomMachine as $index => $item)
                        <div class="inventory-item d-flex justify-content-between align-items-center">
                            <span>{{ $roomMachineName[$index] }}</span>
                            <span class="badge bg-warning">{{ $playerMachineQuantity[$index] }}</span>
                        </div>
                        @empty
                        <div class="text-muted">No finished products available</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Machine Status Card -->
        <div class="col-md-3">
            <div class="dashboard-card h-100 p-4">
                <div class="section-title">
                    <i class="fas fa-cogs"></i> Machine Production Capacity
                </div>
                @forelse ($roomMachine as $index => $machine)
                <div class="machine-stat">
                    <div class="stat-label">{{ $roomMachineName[$index] }}</div>
                    <div class="stat-value">{{ $playerMachineCapacity[$index] }} <small>units / day</small></div>
                </div>
                @empty
                <div class="text-muted">No machines available</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Demands Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="section-title mb-0">
                        <i class="fas fa-list"></i> Active Demands
                    </div>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-download me-2"></i>Export Data
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="demandTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Destination</th>
                                <th>Need Day</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demands as $demand)
                            <tr>
                                <td>#{{ $demand->demand_id }}</td>
                                <td>{{ $demand->tujuan_pengiriman }}</td>
                                <td>Day {{ $demand->need_day }}</td>
                                <td>{{ $demand->item->item_name }}</td>
                                <td>{{ number_format($demand->quantity) }}</td>
                                <td>${{ number_format($demand->revenue, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        const roomId = "{{ $room->room_id }}";
        const playerUsername = "{{ $player->player_username }}";
        // Initialize DataTable with enhanced features

        $('#demandTable').DataTable({
            pageLength: 10,
            order: [
                [2, 'asc']
            ],
            responsive: true,
            dom: '<"d-flex justify-content-between align-items-center mb-4"lf>rt<"d-flex justify-content-between align-items-center"ip>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search demands...",
                lengthMenu: "_MENU_ demands per page"
            },
        });


        window.Echo.channel('update-revenue')
            .listen('.UpdateRevenueEvent', (event) => {
                if (event.playerUsername == playerUsername && event.roomId == roomId) {

                    $.ajax({
                        url: '/updateRevenue',
                        method: 'POST',
                        data: {
                            player_id: playerUsername,
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
                                $('#revenue').html(`: ${formatCurrency(response.revenue)}`);
                                $('#sidebar_revenue').html(formatCurrency(response.revenue));
                                $('#debt').html(`: ${formatCurrency(response.debt)}`);
                                $('#jatuh_tempo').html(`: ${response.jatuh_tempo} days`);
                            }
                        },
                        error: (xhr) => {
                            toastr.error('Failed to fetch revenue:', xhr.responseText);
                        }
                    })

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

        window.Echo.channel('end-simulation')
            .listen('.EndSimulationEvent', (event) => {
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Simulation Ended',
                        text: 'The simulation has ended',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = '/homePlayer';
                    }, 5000);
                }
            });
    });
</script>
@endsection