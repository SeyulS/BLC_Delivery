@extends('layout.player_room')

@section('container')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --border-color: #e2e8f0;
    }

    .demands-container {
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .page-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        text-align: center;
        width: 100%;
    }

    .page-title {
        color: #1a202c;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        justify-content: center;
    }

    .filter-section {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .table thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-pending {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
    }

    .status-taken {
        background: rgba(22, 163, 74, 0.1);
        color: #16a34a;
    }

    .filter-title {
        color: #1e293b;
        font-size: 1rem;
        font-weight: 600;
    }

    .form-select {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.6rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .btn-light {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        color: #64748b;
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #1e293b;
    }

    /* Select2 customization */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .page-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 0;
        text-align: center;
    }

    .page-title {
        color: #1e293b;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .demands-container {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .container-custom {
        width: 100%;
    }

    /* Center icon with text */
    .page-title i {
        font-size: 1.25rem;
    }

    /* Add to your existing style section */
    .item-display {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: rgba(99, 102, 241, 0.1);
        border-radius: 20px;
        font-size: 0.9rem;
        color: #4f46e5;
        font-weight: 500;
    }

    .item-display i {
        font-size: 1rem;
        color: #6366f1;
    }

    .item-quantity {
        font-weight: 600;
        color: #4338ca;
    }
</style>

<div class="demands-container">
    <div class="container">

        <div class="card">
            <!-- Filter Section -->
            <div class="page-header">
                <h1 class="page-title text-center">
                    <i class="bi bi-box-seam me-2"></i>
                    Demand Delivered Information
                </h1>
            </div>
            <!-- Update the filter section -->
            <div class="filter-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="filter-title">
                        <i class="bi bi-funnel me-2"></i>
                        Filter Demands
                    </h5>
                    <button id="clearFilters" class="btn btn-light btn-sm">
                        <i class="bi bi-x-circle me-2"></i>Clear All Filters
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Day</label>
                        <select class="form-select" id="dayFilter">
                            <option value="">All Days</option>
                            @for($i = 1; $i <= $room->max_day; $i++)
                                <option value="{{ $i }}">Day {{ $i }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Destination</label>
                        <select class="form-select" id="destinationFilter">
                            <option value="">All Destinations</option>
                            <option value="Manado">Manado</option>
                            <option value="Makassar">Makassar</option>
                            <option value="Banjarmasin">Banjarmasin</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Datatable -->
            <table class="table" id="demandsTable">
                <thead>
                    <tr>
                        <th>Demand ID</th>
                        <th>Destination</th>
                        <th>Item</th>
                        <th>Need Day</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demands as $demand)
                    <tr>
                        <td>{{ $demand->demand_id }}</td>
                        <td>{{ $demand->tujuan_pengiriman }}</td>
                        <td>
                            <div class="item-display">
                                <i class="bi bi-box"></i>
                                <span class="item-quantity">{{ number_format($demand->quantity) }}x</span>
                                {{ $demand->item->item_name }}
                            </div>
                        </td>
                        <td>Day {{ $demand->need_day }}</td>
                        <td>${{ number_format($demand->revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
        };


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
                        window.location.href = `/blc-delivery/player-lobby/${roomId}`;
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
                        window.location.href = `/blc-delivery/player-lobby/${roomId}`;
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
                        window.location.href = `/blc-delivery/player-lobby/${roomId}`;
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
                        window.location.href = '/blc-delivery/homePlayer';
                    }, 5000);
                }
            });

        window.Echo.channel('update-revenue')
            .listen('.UpdateRevenueEvent', (event) => {
                if (event.playerUsername == playerUsername && event.roomId == roomId) {

                    $.ajax({
                        url: '/blc-delivery/updateRevenue',
                        method: 'POST',
                        data: {
                            player_id: playerUsername,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.revenue !== undefined) {
                                const formatCurrency = (number) => {
                                    return new Intl.NumberFormat('ID-id', {
                                        style: 'currency',
                                        currency: 'IDR'
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
        // Initialize DataTable
        const table = $('#demandsTable').DataTable({
            pageLength: 10,
            ordering: true,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf me-2"></i>Export PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer me-2"></i>Print',
                    className: 'btn btn-primary btn-sm'
                }
            ]
        });

        // Initialize Select2
        $('.form-select').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true // Allows clearing the selection
        });

        // Day Filter
        $('#dayFilter').on('change', function() {
            const value = $(this).val();
            table.column(4).search(value ? 'Day ' + value : '').draw();
        });

        // Destination Filter
        $('#destinationFilter').on('change', function() {
            const value = $(this).val();
            table.column(1).search(value || '').draw();
        });

        // Clear Filters Button
        $('#clearFilters').on('click', function() {
            // Reset Select2 dropdowns
            $('.form-select').val(null).trigger('change');

            // Clear DataTable filters
            table.search('').columns().search('').draw();
        });


    });
</script>
@endsection