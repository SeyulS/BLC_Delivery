@extends('layout.admin_room')
@section('title')
Loan Management | Room {{ $room->room_id }}
@endsection
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

    .toast-success {
        background-color: #059669 !important;
        /* Green */
    }

    .toast-error {
        background-color: #dc2626 !important;
        /* Red */
    }

    .dashboard-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .loan-type-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .loan-type-card:hover {
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .loan-stat {
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
    }

    .loan-value {
        color: var(--primary-color);
        font-size: 1.25rem;
        font-weight: 600;
    }

    .loan-interest {
        color: var(--danger-color);
        font-size: 1.25rem;
        font-weight: 600;
    }

    .loan-due {
        color: var(--success-color);
        font-size: 1.25rem;
        font-weight: 600;
    }

    .select2-container--default .select2-selection--single {
        height: 45px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 45px;
        padding-left: 15px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px;
    }

    .btn-loan {
        background: var(--primary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-loan:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.3);
    }

    .loan-history-table thead th {
        background: #f8f9fa;
        color: var(--dark-color);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .revenue-before {
        color: #64748b;
        text-decoration: line-through;
        font-size: 0.85rem;
    }

    .revenue-after {
        color: rgb(34, 204, 4);
        font-weight: 600;
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

<div class="dashboard-container">
    <div class="container">

        <div class="row">
            <!-- Loan Form Column -->
            <div class="col-md-4">
                <div class="card p-4 mb-4">
                    <h5 class="mb-3">Loan</h5>
                    <form id="pinjaman-form">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->room_id }}">

                        <div class="mb-3">
                            <label class="form-label">Select Player</label>
                            <select class="form-select" id="player-select" name="player">
                                <option value="" disabled selected>Choose a player</option>
                                @foreach($players as $player)
                                <option value="{{ $player->player_username }}">
                                    {{ $player->player_username }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Loan Type</label>
                            <select class="form-select" name="loan_select" id="loan_select" required>
                                <option value="" disabled selected>Select loan type</option>
                                @foreach ($loans as $loan)
                                <option value="{{ $loan->id }}"
                                    data-value="{{ $loan->loan_value }}"
                                    data-interest="{{ $loan->loan_interest }}"
                                    data-due="{{ $loan->loan_due }}">
                                    Rp {{ number_format($loan->loan_value) }} - {{ $loan->loan_interest }}% - {{ $loan->loan_due }} Days
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-loan w-100">
                            <i class="fas fa-check-circle me-2"></i>Set Loan
                        </button>
                    </form>
                </div>

                <!-- Available Loans -->
                <div class="card p-4">
                    <h5 class="mb-3">Available Loan Types</h5>
                    @foreach($loans as $loan)
                    <div class="loan-type-card">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <div class="loan-stat bg-light">
                                    <small class="d-block text-muted mb-1">Value</small>
                                    <span class="loan-value">Rp {{ number_format($loan->loan_value) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="loan-stat bg-light">
                                    <small class="d-block text-muted mb-1">Interest</small>
                                    <span class="loan-interest">{{ $loan->loan_interest }}%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="loan-stat bg-light">
                                    <small class="d-block text-muted mb-1">Duration</small>
                                    <span class="loan-due">{{ $loan->loan_due }} Days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Loan History Column -->
            <div class="col-md-8">
                <div class="card p-4">
                    <h5 class="mb-3">Loan History</h5>
                    <div class="table-responsive">
                        <table class="table table-hover loan-history-table" id="loanHistory">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Day</th>
                                    <th>Amount</th>
                                    <th>Interest</th>
                                    <th>Duration</th>
                                    <th>Revenue Change</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $h)
                                <tr>
                                    <td>{{ $h->player_username }}</td>
                                    <td>{{ $h->day }}</td>
                                    <td>Rp {{ number_format($h->loan_value) }}</td>
                                    <td>{{ $h->loan_interest }}%</td>
                                    <td>{{ $h->loan_due }} days</td>
                                    <td>
                                        <div class="revenue-decrease">
                                            <span class="revenue-before">Rp {{ number_format($h->before_loan, 0, ',', '.') }}</span>
                                            <i class="bi bi-arrow-right revenue-arrow"></i>
                                            <span class="revenue-after">Rp {{ number_format($h->after_loan, 0, ',', '.') }}</span>
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
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#player-select').select2({
            placeholder: 'Select Team',
            allowClear: true,
            width: '100%'
        });

        $('#loan_select').select2({
            placeholder: 'Select Loan Type',
            allowClear: true,
            width: '100%'
        });

        // Initialize DataTable
        const table = $('#loanHistory').DataTable({
            pageLength: 10,
            order: [
                [1, 'desc']
            ], // Sort by day descending
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Player"
            },
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
        };

        // Form Submission
        $('#pinjaman-form').on('submit', function(e) {
            e.preventDefault();

            const selectedPlayer = $('#player-select').val();
            const selectedOption = $('#loan_select').find(':selected');
            const loanValue = selectedOption.data('value');
            const loanInterest = selectedOption.data('interest');
            const loanDue = selectedOption.data('due');

            Swal.fire({
                title: 'Confirm Loan Details',
                html: `
                <div class="text-center">
                    <p><i class="fas fa-user me-2"></i><strong>Player:</strong> ${selectedPlayer}</p>
                    <p><i class="fas fa-money-bill me-2"></i><strong>Amount:</strong> Rp ${loanValue.toLocaleString()}</p>
                    <p><i class="fas fa-percentage me-2"></i><strong>Interest:</strong> ${loanInterest}%</p>
                    <p><i class="fas fa-calendar me-2"></i><strong>Duration:</strong> ${loanDue} Days</p>
                </div>
            `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#718096'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/setPinjaman',
                        method: 'POST',
                        data: {
                            player_username: selectedPlayer,
                            loanAmount: loanValue,
                            loanInterest: loanInterest,
                            loanDuration: loanDue,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to process loan. Please try again.');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection