@extends('layout.player_room')

@section('container')
<style>
    :root {
        /* Enhanced color palette with better contrast */
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --primary-dark: #1d4ed8;
        --secondary: #475569;
        --success: #059669;
        --background: #f8fafc;
        --surface: #ffffff;
        --border: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --shadow: rgba(0, 0, 0, 0.05);
    }

    .toast-success {
        background-color: #059669 !important;
        /* Green */
    }

    .toast-error {
        background-color: #dc2626 !important;
        /* Red */
    }

    /* Layout */
    .dashboard-container {
        background-color: var(--background);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Cards */
    .info-card {
        background: var(--surface);
        border-radius: 12px;
        box-shadow: 0 1px 2px var(--shadow);
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        background: var(--surface);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .card-header h5 {
        color: var(--text-primary);
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .card-header i {
        color: var(--primary);
        font-size: 1.25rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Statistics Grid */
    .warehouse-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-box {
        background: var(--background);
        padding: 1.25rem;
        border-radius: 8px;
        text-align: center;
        border: 1px solid var(--border);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        border-color: var(--primary-light);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Machine Grid */
    .machine-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .machine-card {
        background: var(--background);
        padding: 1.25rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .machine-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-light);
    }

    .machine-card h6 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    /* Form Elements */
    .form-control,
    .form-select {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: var(--text-primary);
        background-color: var(--surface);
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    /* Buttons */
    .btn-purchase {
        background: var(--primary);
        color: var(--surface);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-purchase:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    /* Helper Classes */
    .text-muted {
        color: var(--text-secondary) !important;
    }

    .fw-bold {
        color: var(--text-primary);
        font-weight: 600;
    }

    /* Custom tooltip */
    .tooltip-icon {
        color: var(--text-secondary);
        font-size: 1rem;
        transition: color 0.2s ease;
    }

    .tooltip-icon:hover {
        color: var(--primary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .warehouse-stats {
            grid-template-columns: 1fr;
        }

        .machine-grid {
            grid-template-columns: 1fr;
        }

        .card-body {
            padding: 1rem;
        }
    }
</style>

<div class="dashboard-container">
    <div class="container">
        <!-- Warehouse Section -->
        <div class="info-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Warehouse Management</h5>
                <i class="fas fa-warehouse"></i>
            </div>
            <div class="card-body">
                <div class="warehouse-stats">
                    <div class="stat-box">
                        <div class="stat-value" id="warehouseCapacity">{{ $player->inventory }} m²</div>
                        <div class="stat-label">Total Capacity</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">{{ $usedCapacity }} m²</div>
                        <div class="stat-label">Used Capacity</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">${{ number_format($room->warehouse_price) }}</div>
                        <div class="stat-label">Price per {{ $room->warehouse_size }}m²</div>
                    </div>
                </div>

                <form id="purchaseWarehouse">
                    @csrf
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="number"
                                    class="form-control"
                                    name="quantityPurchase"
                                    id="quantityPurchase"
                                    placeholder="Enter purchase quantity"
                                    required>
                                <span class="ms-2 d-flex align-items-center">
                                    <i class="fas fa-question-circle tooltip-icon"
                                        data-bs-toggle="tooltip"
                                        title="Enter the number of warehouse units to purchase"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-purchase w-100" id="purchase">
                                <i class="fas fa-plus-circle me-2"></i>Purchase Warehouse
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Machines Section -->
        <div class="info-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Machine Management</h5>
                <i class="fas fa-cogs"></i>
            </div>
            <div class="card-body">
                <div class="machine-grid" id="machineCapacities">
                    @foreach($playerMachineCapacity as $index => $capacity)
                    <div class="machine-card">
                        <h6>{{ $machineName[$index] }}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Production Capacity:</span>
                            <span class="fw-bold">{{ $capacity }} Units</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <form id="purchaseMachine" action="/purchaseMachine" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <select name="machineType" id="machineType" class="form-select" required>
                                <option value="" disabled selected>Select Machine Type</option>
                                @foreach($machine as $index => $type)
                                <option value="{{ $type }}" data-machine-name="{{ $machineName[$index] }}">
                                    {{ $machineName[$index] }} - ${{ number_format($machinePrice[$index]) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-purchase w-100">
                                <i class="fas fa-plus-circle me-2"></i>Purchase Machine
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        const roomId = "{{ $room->room_id }}";
        const playerUsername = "{{ $player->player_username }}";

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
        };

        $("#purchase").on("click", function(event) {
            event.preventDefault();

            var quantity = $('#quantityPurchase').val();
            var warehouseSize = "{{ $room->warehouse_size }}";
            var warehousePrice = "{{ $room->warehouse_price }}";
            var roomId = "{{ $room->room_id }}";

            Swal.fire({
                title: 'Warehouse Purchase Confirmation',
                text: `You sure you want to buy warehouse with size ${quantity * warehouseSize} m² and price $${quantity * warehousePrice}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, BUY!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Tombol Purchase ditekan");
                    console.log("Room ID:", roomId);
                    console.log("Quantity", quantity);

                    $.ajax({
                        url: '/purchaseWarehouse',
                        method: 'POST',
                        data: {
                            quantityPurchase: quantity,
                            room_id: roomId,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#warehouseCapacity').html(`${response.currentWarehouse} m²`);
                                $('#currentCapacity').html(`${response.currentCapacity} m²`);
                            } else {
                                toastr.error(response.message);
                            }
                            $('#purchaseWarehouse')[0].reset();
                        },
                        error: function(xhr, status, error) {
                            console.log('Error Response:', xhr.responseText);
                            toastr.error('Error: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
        // Event listener untuk pembelian mesin
        $("form[action='/purchaseMachine']").on("submit", function(event) {
            event.preventDefault(); // Menghentikan default behavior form submit

            const machineType = $('#machineType').val();
            const machineName = $('#machineType option:selected').data('machine-name');

            Swal.fire({
                title: 'Konfirmasi Pembelian Mesin',
                html: `You sure you want to buy this <strong>${machineName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, beli!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/purchaseMachine',
                        method: 'POST',
                        data: {
                            room_id: roomId,
                            machineType: machineType,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log('Success Response:', response);

                            if (response.status === 'fail') {
                                toastr.error(response.message);
                            } else {
                                toastr.success(response.message);
                                let machineCapacityHtml = ""; // Tambahkan deklarasi variabel
                                for (let i = 0; i < response.currentCapacity.length; i++) {
                                    machineCapacityHtml += `<div class="machine-card">
                                                                <h6 class="mb-2">${response.machineName[i]}</h6>
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span class="text-muted">Current Production Capacity:</span>
                                                                    <span class="fw-bold">${response.currentCapacity[i]} Units</span>
                                                                </div>
                                                            </div>`;
                                }
                                $("#machineCapacities").html(machineCapacityHtml);
                                $("#player_revenue").html(response.revenue);
                                $('#purchaseMachine')[0].reset();


                            }
                        },
                        error: function(xhr) {
                            toastr.error('Error: ' + xhr.responseText);
                        }
                    });
                }
            });
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
    });
</script>

@endsection