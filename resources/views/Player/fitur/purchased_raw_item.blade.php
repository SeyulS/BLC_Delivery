@extends('layout.player_room')

@section('container')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #2ea44f;
        --warning-color: #f7b731;
        --danger-color: #dc3545;
        --dark-color: #1e2a35;
        --light-color: #f8f9fa;
        --border-color: #e2e8f0;
    }

    .purchase-history-container {
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .history-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .history-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .page-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .page-title {
        color: var(--dark-color);
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-section {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .history-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }

    .history-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .purchase-items {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .purchase-item {
        background: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .purchase-quantity {
        background: #4f46e5;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .revenue-change {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .revenue-before {
        color: #64748b;
        text-decoration: line-through;
    }

    .revenue-after {
        color: #059669;
        font-weight: 600;
    }

    .arrow-icon {
        color: #64748b;
        font-size: 1rem;
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
</style>

<div class="purchase-history-container">
    <div class="container">
        <div class="history-card">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="bi bi-cart-check"></i>
                    Purchase History
                </h1>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="filter-title">
                        <i class="bi bi-funnel me-2"></i>
                        Filter History
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
                </div>
            </div>

            <!-- History Table -->
            <div class="table-responsive p-3">
                <table class="table history-table" id="purchaseHistoryTable">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Items Purchased</th>
                            <th>Total Cost</th>
                            <th>Revenue Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseHistory as $history)
                        <tr>
                            <td>Day {{ $history['day'] }}</td>
                            <td>
                                <div class="purchase-items">
                                    @foreach($history['items'] as $item)
                                    <div class="purchase-item">
                                        <i class="bi bi-box-seam"></i>
                                        {{ $item['item_name'] }}
                                        <span class="purchase-quantity">{{ number_format($item['quantity']) }}x</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>${{ number_format($history['total_cost'], 2) }}</td>
                            <td>
                                <div class="revenue-change">
                                    <span class="revenue-before">${{ number_format($history['revenue_before'], 2) }}</span>
                                    <i class="bi bi-arrow-right arrow-icon"></i>
                                    <span class="revenue-after">${{ number_format($history['revenue_after'], 2) }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const roomId = "{{ $room->room_id }}";
        const playerUsername = "{{ $player->player_username }}";
        const table = $('#purchaseHistoryTable').DataTable({
            pageLength: 10,
            ordering: true,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel me-2"></i>Export Excel',
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

        // Day Filter
        $('#dayFilter').on('change', function() {
            const value = $(this).val();

            // Gunakan regex agar sesuai dengan format "Day X"
            table.column(0).search(value ? '^Day ' + value + '$' : '', true, false).draw();
        });

        table.columns().every(function() {
            var that = this;

            $('input, select', this.footer()).on('keyup change', function() {
                if (that.search() !== this.value) {
                    that.search(this.value, true, false).draw();
                }
            });
        });

        // Clear Filters
        $('#clearFilters').on('click', function() {
            $('.form-select').val(null).trigger('change');
            table.search('').columns().search('').draw();
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