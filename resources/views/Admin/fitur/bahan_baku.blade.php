@extends('layout.admin_room')

@section('container')
<style>
    .container-custom {
        background: white;
        padding: 1.5rem;
        /* Reduced from 2rem */
    }

    .badge-team {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-quantity {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
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

    .select-container {
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .form-select {
        width: 300px;
        max-width: 100%;
        padding: 0.75rem;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        font-size: 0.95rem;
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .page-title {
        color: #1a202c;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        /* Reduced from 1.5rem */
        text-align: center;
    }

    .toast-success {
        background-color: #059669 !important;
        /* Green */
    }

    .toast-error {
        background-color: #dc2626 !important;
        /* Red */
    }

    .select-container {
        margin-bottom: 1.5rem;
        /* Reduced from 2rem */
        text-align: center;
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
        transition: all 0.3s ease;
    }

    .purchase-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.1);
    }

    .purchase-item i {
        font-size: 1rem;
        color: #6366f1;
    }

    .purchase-quantity {
        background: #4f46e5;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        background: #fff;
        padding: 1.5rem;
        height: 100%;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .card-icon {
        font-size: 2.5rem;
        color: #4a5568;
        margin: 1rem 0;
        transition: all 0.3s ease;
    }

    .card:hover .card-icon {
        color: #3b82f6;
        transform: scale(1.1);
    }

    .quantity-input {
        text-align: center;
        border: 2px solid #e2e8f0;
        padding: 0.5rem;
        font-size: 0.9rem;
        width: 100%;
        border-radius: 10px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .quantity-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .button-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    #submit-button {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    #submit-button:hover {
        background-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    hr {
        border-color: #e2e8f0;
        margin: 1.5rem 0;
    }

    .nav-tabs {
        border: none;
        margin-bottom: 2rem;
        justify-content: center;
        gap: 1rem;
    }

    .nav-tabs .nav-link {
        border: none;
        padding: 1rem 2rem;
        border-radius: 10px;
        font-weight: 500;
        color: #64748b;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #3b82f6;
        background: #eff6ff;
        font-weight: 600;
    }

    .nav-tabs .nav-link i {
        margin-right: 0.5rem;
    }

    .tab-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
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

    .badge-team {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-quantity {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .history-table th.raw-items-column,
    .history-table td.raw-items-column {
        min-width: 300px;
        width: 30%;
    }

    /* Filter styles */
    .filter-section {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .filter-row {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
    }

    .filter-label {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    /* Existing purchase items styles... */
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
        gap: 0.75rem;
        font-size: 0.9rem;
    }

    .revenue-before {
        color: #64748b;
        text-decoration: line-through;
        font-size: 0.85rem;
    }

    .revenue-after {
        color: #ef4444;
        font-weight: 600;
    }

    .revenue-arrow {
        color: #64748b;
        font-size: 1rem;
    }

    .revenue-decrease {
        background: rgba(239, 68, 68, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="container">
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="shop-tab" data-bs-toggle="tab" data-bs-target="#shop" type="button" role="tab">
                <i class="bi bi-shop"></i>Raw Item Shop
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                <i class="bi bi-clock-history"></i>Purchase History
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content">
        <!-- Shop Tab -->
        <div class="tab-pane fade show active" id="shop" role="tabpanel">
            <div class="container-custom">
                <!-- Existing form content... -->
                <form id="bahan-baku-form" action="/setting_bahan_baku" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->room_id }}">

                    <div class="select-container">
                        <div class="filter-section">
                            <select class="form-select" id="team-select" name="team">
                                <option value="" selected disabled>Select Team</option>
                                @foreach($players as $player)
                                <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach($rawItems as $rawItem)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $rawItem->raw_item_name }}</h5>
                                    <i class="bi bi-box-seam card-icon"></i>
                                    <input type="number"
                                        class="quantity-input"
                                        name="quantity[{{ $rawItem->id }}]"
                                        placeholder="Enter quantity"
                                        data-item-id="{{ $rawItem->id }}"
                                        min="0">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="button-container">
                        <button type="submit" id="submit-button" class="btn">Sell To Player</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Tab -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            <div class="container-custom">
                <div class="filter-section">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">Filter by Day</label>
                            <select class="form-select" id="dayFilter">
                                <option value="">All Days</option>
                                @for($i = 1; $i <= $room->max_day; $i++)
                                    <option value="{{ $i }}">Day {{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Filter by Player</label>
                            <select class="form-select" id="playerFilter">
                                <option value="">All Players</option>
                                @foreach($players as $player)
                                <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <table class="table history-table" id="historyTable">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Player</th>
                            <th>Raw Item Purchased</th>
                            <th>Total Cost</th>
                            <th>Revenue Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseHistory as $history)
                        <tr>
                            <td>{{ $history['day'] }}</td>
                            <td><span class="badge-team">{{ $history['player_username'] }}</span></td>
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
                                <div class="revenue-decrease">
                                    <span class="revenue-before">${{ number_format($history['revenue_before'], 2) }}</span>
                                    <i class="bi bi-arrow-right revenue-arrow"></i>
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
        $('#team-select').select2({
            placeholder: "Select Team",
            allowClear: true,
            width: '100%'
        });

        $('#dayFilter, #playerFilter').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });

        // Initialize DataTable
        const table = $('#historyTable').DataTable({
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
            ],
            // Define column definitions for better searching
            columnDefs: [{
                    targets: 0, // Day column
                    type: 'string',
                    render: function(data, type, row) {
                        return type === 'filter' ? data : data;
                    }
                },
                {
                    targets: 1, // Player column
                    type: 'string',
                    render: function(data, type, row) {
                        // Extract player name from badge span
                        return type === 'filter' ? $(data).text() : data;
                    }
                }
            ]
        });

        $('#dayFilter').on('change', function() {
            const value = $(this).val();
            if (value) {
                table.column(0).search('^' + value + '$', true, false).draw();
            } else {
                table.column(0).search('').draw();
            }
        });

        // Player Filter
        $('#playerFilter').on('change', function() {
            const value = $(this).val();
            if (value) {
                table.column(1).search(value).draw();
            } else {
                table.column(1).search('').draw();
            }
        });

        // Clear Filters Button
        $('#clearFilters').on('click', function() {
            $('#dayFilter, #playerFilter').val(null).trigger('change');
            table.search('').columns().search('').draw();
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
        };

        $('#submit-button').click(function(e) {
            e.preventDefault();
            const playerId = $('#team-select').val();
            const playerName = $('#team-select option:selected').text();
            const roomId = $('input[name="room_id"]').val();
            let quantities = [];
            console.log(quantities);
            let itemTable = `<table style="width:100%; text-align:center; border-collapse: collapse;">
                            <tr>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Barang</th>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Quantity</th>
                            </tr>`;

            $('.quantity-input').each(function() {
                const itemId = $(this).data('item-id');
                const quantity = $(this).val();
                const itemName = $(this).closest('.card-body').find('.card-title').text();

                quantities.push({
                    item_id: itemId,
                    quantity: isNaN(parseInt(quantity)) ? 0 : parseInt(quantity),
                    item_name: itemName
                });
                if (quantity && parseInt(quantity) > 0) {
                    itemTable += `<tr>
                                <td style="padding: 8px;">${itemName}</td>
                                <td style="padding: 8px;">${quantity}</td>
                              </tr>`;
                }
            });

            itemTable += `</table>`;

            if (!playerId) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please select a team!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (quantities.length === 0) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please select at least 1 item!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Pembelian',
                html: `<p>Tim: <b>${playerName}</b></p>
                   <p>Barang yang akan dibeli:</p>
                   ${itemTable}
                   <p class='mt-2'><strong>Bacakan Kembali ke Player!</strong></p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Player sudah setuju!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/setting_bahan_baku',
                        type: 'POST',
                        data: {
                            player_id: playerId,
                            room_id: roomId,
                            items: quantities,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == "success") {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                window.location.reload();
                            } else {
                                Swal.fire({
                                    title: 'Fail!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }


                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan, silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            window.location.reload();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection