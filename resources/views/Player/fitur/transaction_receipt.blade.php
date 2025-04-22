@extends('layout.player_room')

@section('title')
BLC Delivery | Transaction Receipt
@endsection

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
</style>

<div class="container py-4">
    <!-- Stats Cards -->
    <div class="dashboard-stats">
        <!-- ... existing stat cards ... -->
    </div>

    <!-- Charts -->
    <div class="charts-container">
        <div class="chart-card">
            <h5 class="mb-4">Daily Income vs Expenses</h5>
            <canvas id="dailyChart"></canvas>
        </div>
        <div class="chart-card">
            <h5 class="mb-4">Cumulative Balance</h5>
            <canvas id="balanceChart"></canvas>
        </div>
    </div>

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
                    <select class="form-select form-select-sm" id="filterType">
                        <option value="all">All Transactions</option>
                        <option value="income">Income Only</option>
                        <option value="expense">Expenses Only</option>
                    </select>
                    <select class="form-select form-select-sm" id="filterDay">
                        <option value="all">All Days</option>
                        @foreach($history->unique('day')->sortBy('day') as $transaction)
                        <option value="{{ $transaction->day }}">Day {{ $transaction->day }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="transaction-table">
                    <thead>
                        <tr>
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
    // Chart Configuration
    document.addEventListener('DOMContentLoaded', function() {
        const history = @json($history);
        let dailyChart, balanceChart;
        const roomId = "{{ $room->room_id }}";
        const playerUsername = "{{ $player->player_username }}";

        function initializeCharts(filteredData) {
            const days = [...new Set(filteredData.map(t => t.day))].sort((a, b) => a - b);

            // Prepare data for charts
            const chartData = days.map(day => {
                const dayTransactions = filteredData.filter(t => t.day === day);
                return {
                    day: `Day ${day}`,
                    income: dayTransactions.filter(t => t.value > 0).reduce((sum, t) => sum + t.value, 0),
                    expense: Math.abs(dayTransactions.filter(t => t.value < 0).reduce((sum, t) => sum + t.value, 0)),
                    balance: dayTransactions[dayTransactions.length - 1]?.revenue_after || 0
                };
            });

            // Destroy existing charts if they exist
            if (dailyChart) dailyChart.destroy();
            if (balanceChart) balanceChart.destroy();

            // Daily Income vs Expenses Chart
            dailyChart = new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: {
                    labels: chartData.map(d => d.day),
                    datasets: [{
                            label: 'Income',
                            data: chartData.map(d => d.income),
                            backgroundColor: 'rgba(46, 164, 79, 0.2)',
                            borderColor: 'rgba(46, 164, 79, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Expenses',
                            data: chartData.map(d => d.expense),
                            backgroundColor: 'rgba(231, 76, 60, 0.2)',
                            borderColor: 'rgba(231, 76, 60, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '$' + value.toLocaleString()
                            }
                        }
                    }
                }
            });

            // Cumulative Balance Chart
            balanceChart = new Chart(document.getElementById('balanceChart'), {
                type: 'line',
                data: {
                    labels: chartData.map(d => d.day),
                    datasets: [{
                        label: 'Balance',
                        data: chartData.map(d => d.balance),
                        borderColor: 'rgba(67, 97, 238, 1)',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            ticks: {
                                callback: value => '$' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        }

        function applyFilters() {
            const typeFilter = document.getElementById('filterType').value;
            const dayFilter = document.getElementById('filterDay').value;
            const transactions = document.querySelectorAll('.transaction-row');

            // Filter the data for charts
            let filteredData = [...history];

            if (typeFilter !== 'all') {
                filteredData = filteredData.filter(t =>
                    typeFilter === 'income' ? t.value > 0 : t.value < 0
                );
            }

            if (dayFilter !== 'all') {
                filteredData = filteredData.filter(t =>
                    t.day === parseInt(dayFilter)
                );
            }

            // Update charts with filtered data
            initializeCharts(filteredData);

            // Update table visibility
            transactions.forEach(item => {
                const type = item.dataset.type;
                const day = item.querySelector('.badge-day').textContent.replace('Day ', '');

                const matchesType = typeFilter === 'all' || type === typeFilter;
                const matchesDay = dayFilter === 'all' || parseInt(day) === parseInt(dayFilter);

                item.style.display = (matchesType && matchesDay) ? 'table-row' : 'none';
            });
        }

        // Initialize charts with all data
        initializeCharts(history);

        // Add event listeners
        document.getElementById('filterType').addEventListener('change', applyFilters);
        document.getElementById('filterDay').addEventListener('change', applyFilters);

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
    });
</script>
@endsection