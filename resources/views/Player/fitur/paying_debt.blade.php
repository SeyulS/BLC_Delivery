@extends('layout.player_room')
@section('title')
BLC Delivery | Pay Debt
@endsection
@section('container')
<style>
    .debt-payment-container {
        max-width: 800px;
        margin: 2rem auto;
    }

    .info-box {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .info-box label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .info-box h4 {
        margin: 0;
        color: #2c3e50;
    }

    .card {
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        color: #2c3e50;
    }
</style>

<div class="debt-payment-container">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-4">Debt Payment</h3>

            <div class="debt-info mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <label class="text-muted">Current Debt</label>
                            @if ($player->jatuh_tempo == null)
                            <h4 class="due-amount" id="loanDue">-</h4>
                            @else
                            <h4 class="debt-amount" id="currentDebt">Rp {{ number_format($player->debt ?? 0, 0, ',', '.') }}</h4>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <label class="text-muted">Loan Due</label>
                            @if ($player->jatuh_tempo == null)
                            <h4 class="due-amount" id="loanDue">-</h4>
                            @else
                            <h4 class="due-amount" id="loanDue">{{ $player->jatuh_tempo - $room->recent_day }} days</h4>

                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <form id="debtPaymentForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="paymentAmount">Payment Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number"
                            class="form-control"
                            id="paymentAmount"
                            name="payment_amount"
                            placeholder="Enter payment amount"
                            min="0"
                            max="{{ $player->debt }}"
                            required>
                    </div>
                    <small class="form-text text-muted">
                        <a href="#" id="payFullDebt" class="text-primary">Click here to pay full debt amount</a>
                    </small>
                </div>

                <button type="submit" class="btn btn-primary mt-3" id="payment">Process Payment</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(() => {
        const roomId = "{{ $room->room_id }}";
        const playerUsername = "{{ $player->player_username }}";

        console.log(roomId);
        const payFullDebtLink = document.getElementById('payFullDebt');
        const paymentAmountInput = document.getElementById('paymentAmount');
        const currentDebtElement = document.getElementById('currentDebt');
        const form = $('#debtPaymentForm');

        payFullDebtLink.addEventListener('click', function(e) {
            e.preventDefault();
            const currentDebtText = currentDebtElement.textContent.replace('Rp ', '').replace(/\./g, '').trim();
            paymentAmountInput.value = parseInt(currentDebtText);
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

        form.on('submit', function(e) {
            e.preventDefault();
            var paymentAmount = $('#paymentAmount').val();

            if (paymentAmount <= 0) {
                Swal.fire('Error', 'Please enter a valid payment amount!', 'error');
                return;
            }

            Swal.fire({
                title: 'Confirm Payment',
                text: `Are you sure you want to pay Rp ${new Intl.NumberFormat('id-ID').format(paymentAmount)}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Pay Now!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/blc-delivery/payDebt",
                        type: "POST",
                        data: {
                            paymentAmount: paymentAmount,
                            roomId : roomId,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                Swal.fire('Success', response.message, 'success');
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }

                            let formattedDebt = new Intl.NumberFormat('id-ID').format(response.currentDebt);
                            $('#currentDebt').html(`Rp ${formattedDebt}`);
                            $('#loanDue').html(`${response.loanDue} days`);
                            $('#debtPaymentForm')[0].reset();
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'An error occurred: ' + xhr.responseText, 'error');
                        }
                    });
                }
            });
        });

        window.Echo.channel('player-remove')
            .listen('.PlayerRemoveEvent', (event) => {
                if (event.playerUsername == playerUsername) {
                    window.location.href = '/blc-delivery/homePlayer';
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
                        window.location.href = '/blc-delivery/homePlayer';;
                    }, 5000);
                }
            });

    });
</script>
@endsection