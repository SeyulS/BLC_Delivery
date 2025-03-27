@extends('layout.admin_room')

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

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .charts-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.3rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .transaction-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .transaction-table th {
        background: #f8fafc;
        padding: 1rem;
        font-weight: 600;
        text-align: left;
        color: #64748b;
    }

    .transaction-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .transaction-table tr:hover {
        background: rgba(67, 97, 238, 0.05);
    }

    .revenue-change {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .old-value {
        text-decoration: line-through;
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .arrow-icon {
        color: #64748b;
    }

    .new-value {
        font-weight: 600;
        color: var(--dark);
    }

    .badge-day {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-income {
        background: rgba(46, 164, 79, 0.1);
        color: var(--success);
    }

    .badge-expense {
        background: rgba(231, 76, 60, 0.1);
        color: var(--danger);
    }

    .form-control-sm {
    height: calc(1.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
}

.form-control-sm:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}
</style>

<div class="container py-4">
    <!-- Transaction Table -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Transaction History
                </h5>
                <!-- Replace the existing filter div with this updated version -->
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="filterType" style="width: 150px;">
                        <option value="all">All Transactions</option>
                        <option value="income">Income Only</option>
                        <option value="expense">Expenses Only</option>
                    </select>
                    <select class="form-select form-select-sm" id="filterDay" style="width: 150px;">
                        <option value="all">All Days</option>
                        @foreach($history->unique('day')->sortBy('day') as $transaction)
                        <option value="{{ $transaction->day }}">Day {{ $transaction->day }}</option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control form-control-sm" id="filterPlayer"
                        placeholder="Search player..." style="width: 200px;">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Day</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $transaction)
                        <tr class="transaction-row" data-type="{{ $transaction->value > 0 ? 'income' : 'expense' }}">
                            <td>
                                <span class="badge-day">
                                    {{ $transaction->player_username }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-day {{ $transaction->value > 0 ? 'badge-income' : 'badge-expense' }}">
                                    Day {{ $transaction->day }}
                                </span>
                            </td>
                            <td>{{ $transaction->transaction_description }}</td>
                            <td>
                                <span class="badge {{ $transaction->value > 0 ? 'badge-income' : 'badge-expense' }}">
                                    {{ $transaction->value > 0 ? 'Income' : 'Expense' }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $transaction->value > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    ${{ number_format(abs($transaction->value), 2) }}
                                </span>
                            </td>
                            <td>
                                <div class="revenue-change">
                                    <span class="old-value">${{ number_format($transaction->revenue_before, 2) }}</span>
                                    <i class="bi bi-arrow-right arrow-icon"></i>
                                    <span class="new-value">${{ number_format($transaction->revenue_after, 2) }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-receipt-cutoff fs-1 text-muted"></i>
                                <p class="mt-3 text-muted">No transactions found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterType = document.getElementById('filterType');
        const filterDay = document.getElementById('filterDay');
        const filterPlayer = document.getElementById('filterPlayer');

        function applyFilters() {
            const typeFilter = filterType.value;
            const dayFilter = filterDay.value;
            const playerFilter = filterPlayer.value.toLowerCase();

            const rows = document.querySelectorAll('.transaction-row');

            rows.forEach(row => {
                const type = row.dataset.type;
                const day = row.querySelector('.badge-day').textContent.replace('Day ', '');
                const player = row.querySelector('td:first-child').textContent.trim().toLowerCase();

                const matchesType = typeFilter === 'all' || type === typeFilter;
                const matchesDay = dayFilter === 'all' || parseInt(day) === parseInt(dayFilter);
                const matchesPlayer = player.includes(playerFilter);

                row.style.display = (matchesType && matchesDay && matchesPlayer) ? 'table-row' : 'none';
            });
        }

        // Add event listeners for all filters
        filterType.addEventListener('change', applyFilters);
        filterDay.addEventListener('change', applyFilters);

        // Add debounce for player search
        let timeout = null;
        filterPlayer.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilters, 300);
        });
    });
</script>
@endsection