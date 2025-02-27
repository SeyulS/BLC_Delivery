@extends('layout.player_room')

@section('container')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #2ea44f;
        --info: #3498db;
        --warning: #f7b731;
        --danger: #e74c3c;
        --dark: #2d3436;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    .toast-info {
        background-color: #2563eb !important;
        /* Blue */
    }

    .demands-dashboard {
        min-height: 100vh;
        background-color: #f1f5f9;
        padding: 2rem 0;
    }

    /* Header Section */
    .dashboard-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .header-content {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .datetime-display {
        font-family: 'JetBrains Mono', monospace;
        border-left: 4px solid var(--primary);
        padding-left: 1rem;
    }

    .datetime-label {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .datetime-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark);
    }

    /* Filters Section */
    .filters-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .filter-group {
        position: relative;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: var(--light);
    }

    .filter-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    /* Demands Grid */
    .demands-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .demand-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid var(--border);
        position: relative;
    }

    .demand-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .demand-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 1rem;
        text-align: center;
    }

    .demand-id {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .demand-body {
        padding: 1.5rem;
    }

    .demand-info {
        margin-bottom: 1.1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .info-label {
        color: #64748b;
        font-weight: 300;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .demand-footer {
        padding: 0.5rem;
        text-align: center;
        border-top: 1px solid var(--border);
    }

    .btn-take-demand {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-take-demand:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.3);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border);
        padding: 1.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .demands-grid {
            grid-template-columns: 1fr;
        }

        .header-content {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>

<div class="demands-dashboard">
    <div class="container">

        <!-- Filters -->
        <div class="filters-container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Destination
                        </label>
                        <select id="destination-filter" class="filter-select">
                            <option value="">All Destinations</option>
                            @foreach($uniqueDestinations as $destination)
                            <option value="{{ $destination }}">{{ $destination }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-hashtag me-2"></i>Demand ID
                        </label>
                        <input type="text" id="demand-id-filter" class="filter-select"
                            placeholder="Search by ID...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-box me-2"></i>Item Type
                        </label>
                        <select id="item-filter" class="filter-select">
                            <option value="">All Items</option>
                            @foreach($uniqueItems as $item)
                            <option value="{{ $item->item_name }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demands Grid -->
        <div class="demands-grid" id="demand-container">
            @foreach($demands as $demand)
            @if(!$demand->taken)
            <div class="demand-card"
                data-destination="{{ $demand->tujuan_pengiriman }}"
                data-demand-id="{{ $demand->demand_id }}"
                data-item="{{ $demand->item->item_name }}" id="card{{$demand->demand_id}}">
                <div class="demand-header">
                    <h3 class="demand-id">{{ $demand->demand_id }}</h3>
                </div>
                <div class="demand-body">
                    <div class="demand-info">
                        <div class="info-row">
                            <span class="info-label">Destination:</span>
                            <span class="info-value">{{ $demand->tujuan_pengiriman }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Need Day:</span>
                            <span class="info-value">Day {{ $demand->need_day }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Item:</span>
                            <span class="info-value">{{ $demand->item->item_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Quantity:</span>
                            <span class="info-value">{{ $demand->quantity }} units</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Revenue:</span>
                            <span class="info-value">${{ number_format($demand->revenue, 2) }}</span>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <img src="{{ asset('assets/BLCLogoCircle.png') }}" alt="BLC Delivery Logo"
                            style="width: 30%; height: auto;">
                        <p class="text-muted mt-2" style="font-size: 0.875rem;">
                            Managed by <img src="{{ asset('assets/BLCSentence.png') }}" alt="BLC Delivery Logo"
                                style="width: 20%;">
                        </p>
                    </div>
                </div>
                <div class="demand-footer">
                    <button class="btn-take-demand take-demand"
                        data-demand-id="{{ $demand->demand_id }}">
                        <i class="fas fa-truck me-2"></i>Take Demand
                    </button>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const roomId = "{{ $room->room_id }}";
        const playerUsername = "{{ $player->player_username }}";

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
        }
        $('#destination-filter, #demand-id-filter, #item-filter').on('change input', function() {
            console.log('Filtering demands...');
            var selectedDestination = $('#destination-filter').val().toLowerCase() || '';
            var selectedDemandId = $('#demand-id-filter').val().toLowerCase() || '';
            var selectedItem = $('#item-filter').val().toLowerCase() || '';

            $('#demand-container .demand-card').each(function() {
                var cardDestination = String($(this).data('destination') || '').toLowerCase();
                var cardDemandId = String($(this).data('demand-id') || '').toLowerCase();
                var cardItem = String($(this).data('item') || '').toLowerCase();

                var isMatch = true;

                if (selectedDestination && cardDestination !== selectedDestination) {
                    isMatch = false;
                }
                // Menggunakan includes() agar hasil filter tidak harus sama persis
                if (selectedDemandId && !cardDemandId.includes(selectedDemandId)) {
                    isMatch = false;
                }
                if (selectedItem && cardItem !== selectedItem) {
                    isMatch = false;
                }

                if (isMatch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        window.Echo.channel('demand-taken')
            .listen('.DemandTakenEvent', (event) => {
                var demandId = event.demandId;
                var card = $('#card' + demandId);
                toastr.info(`Demand No. ${demandId} has been taken`);
                card.remove();
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

        $(document).on('click', '.take-demand', function() {
            var demandId = $(this).data('demand-id');
            var card = $('#card' + demandId);
            var roomId = "{{ $room->room_id }}";
            var playerId = "{{ $player->player_username }}";

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to take this demand ( No. ${demandId} )?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Take It!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/take-demand/',
                        method: 'POST',
                        data: {
                            demand_id: demandId,
                            room_id: roomId,
                            player_id: playerId,
                            _token: '{{ csrf_token() }}',
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we take the demand.',
                                icon: 'info',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                                card.remove();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong. Please try again.',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });
    });
</script>

@endsection