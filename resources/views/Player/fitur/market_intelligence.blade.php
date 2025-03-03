@extends('layout.player_room')

@section('container')
<style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #1e40af;
        --success-color: #059669;
        --warning-color: #d97706;
        --danger-color: #dc2626;
        --dark-color: #1f2937;
        --light-bg: #f9fafb;
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
        --text-primary: #111827;
        --text-secondary: #6b7280;
    }

    .market-dashboard {
        background-color: var(--light-bg);
        min-height: 100vh;
        width: 100%;
        padding: 0;
        /* Remove padding */
    }

    /* Update container styling */
    .market-dashboard .container {
        height: 100%;
        padding: 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
        background-color: transparent;
        /* Remove background */
    }

    .page-header {
        background: var(--card-bg);
        color: var(--text-primary);
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .market-dashboard .container {
            padding: 1rem;
        }
    }

    .page-header h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .page-header p {
        color: var(--text-secondary);
        margin: 0.5rem 0 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--card-bg);
        padding: 1.25rem;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-card h6 {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
        margin: 0;
    }

    .stat-value {
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .info-card {
        background: var(--card-bg);
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
    }

    .card-header-custom h5 {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table th {
        background: var(--light-bg);
        color: var(--text-primary);
        font-weight: 500;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        text-align: left;
    }

    .custom-table td {
        padding: 0.75rem 1rem;
        color: var(--text-secondary);
        border-top: 1px solid var(--border-color);
        font-size: 0.875rem;
    }

    .custom-table tr:hover td {
        background: var(--light-bg);
    }

    .bom-card {
        height: 100%;
        padding: 1.25rem;
    }

    .bom-materials {
        background: var(--light-bg);
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 1rem;
    }

    .bom-materials h6 {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .badge {
        background: var(--light-bg);
        color: var(--text-primary);
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .dimensions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .dimension-item {
        background: var(--light-bg);
        padding: 0.75rem;
        border-radius: 0.5rem;
        text-align: center;
    }

    .dimension-item small {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }

    .dimension-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .custom-table {
            font-size: 0.875rem;
        }

        .dimension-value {
            font-size: 0.875rem;
        }
    }
</style>

<div class="market-dashboard">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3>Market Intelligence</h3>
                    <p>Comprehensive market data and analysis</p>
                </div>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h6>Warehouse Price</h6>
                <div class="stat-value">${{ number_format($warehousePrice) }}/{{ $warehouseSize }}m²</div>
            </div>
            <div class="stat-card">
                <h6>Early Delivery Charge</h6>
                <div class="stat-value">${{ number_format($earlyDeliveryCharge) }}/day</div>
            </div>
            <div class="stat-card">
                <h6>Late Delivery Charge</h6>
                <div class="stat-value">${{ number_format($lateDeliveryCharge) }}/day</div>
            </div>
            <div class="stat-card">
                <h6>Inventory Cost</h6>
                <div class="stat-value">${{ number_format($inventoryCost) }}/unit</div>
            </div>
        </div>

        <!-- Shipping Methods -->
        @foreach([
        ['title' => 'Less Container Load', 'data' => $lcl],
        ['title' => 'Full Container Load', 'data' => $fcl],
        ['title' => 'Air Delivery', 'data' => $air]
        ] as $shipping)
        <div class="info-card">
            <div class="card-header-custom">
                <h5>{{ $shipping['title'] }}</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Destination</th>
                            <th>Duration</th>
                            <th>Volume Capacity</th>
                            <th>Weight Capacity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipping['data'] as $item)
                        <tr>
                            <td>{{ $item->destination }}</td>
                            <td>{{ $item->pengiriman_duration }} days</td>
                            <td>{{ isset($item->current_volume_capacity) ? 
                                          "{$item->current_volume_capacity} / {$item->max_volume_capacity}" : 
                                          $item->max_volume_capacity }}</td>
                            <td>{{ isset($item->current_weight_capacity) ? 
                                          "{$item->current_weight_capacity} / {$item->max_weight_capacity}" : 
                                          $item->max_weight_capacity }}</td>
                            <td>${{ number_format($item->price) }}/m³</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        <!-- Materials and Equipment Section -->
        <div class="row g-3">
            <!-- Bill of Materials -->
            @foreach($BOM as $materials)
            <div class="col-md-4">
                <div class="info-card h-100">
                    <div class="card-header-custom">
                        <h5>{{ $materials['item_name'] }}</h5>
                    </div>
                    <div class="bom-card">
                        <div class="bom-materials">
                            <h6>Materials Required</h6>
                            @foreach($materials['raw_items'] as $raw)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-secondary">{{ $raw['name'] }}</span>
                                <span class="badge">× {{ $raw['quantity'] }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="dimensions-grid">
                            <div class="dimension-item">
                                <small>Width</small>
                                <div class="dimension-value">{{ $materials['width'] }} m</div>
                            </div>
                            <div class="dimension-item">
                                <small>Height</small>
                                <div class="dimension-value">{{ $materials['height'] }} m</div>
                            </div>
                            <div class="dimension-item">
                                <small>Length</small>
                                <div class="dimension-value">{{ $materials['length'] }} m</div>
                            </div>
                            <div class="dimension-item">
                                <small>Weight</small>
                                <div class="dimension-value">{{ $materials['weight'] }} kg</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Raw Items and Machines -->
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h5>Raw Items</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rawItems as $item)
                                <tr>
                                    <td>{{ $item->raw_item_name }}</td>
                                    <td>${{ number_format($item->raw_item_price) }}/pc</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h5>Machines</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Size</th>
                                    <th>Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($machines as $machine)
                                <tr>
                                    <td>{{ $machine->machine_name }}</td>
                                    <td>${{ number_format($machine->machine_price) }}</td>
                                    <td>{{ $machine->machine_size }}m²</td>
                                    <td>{{ $machine->production_capacity }}/day</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        const roomId = "{{ $room->room_id }}";
        const playerUsername = "{{ $player->player_username }}";
        window.Echo.channel('player-remove')
            .listen('.PlayerRemoveEvent', (event) => {
                if (event.playerUsername == playerUsername) {
                    window.location.href = '/homePlayer'
                }
                if (event.roomId == roomId) {
                    datatable.ajax.reload();
                }

            });

        window.Echo.channel('start-simulation')
            .listen('.StartSimulationEvent', (event) => {
                if (event.roomId = roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'The simulation has started',
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