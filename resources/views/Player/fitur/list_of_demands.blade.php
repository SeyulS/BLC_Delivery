@extends('layout.player_room')

@section('container')
<style>
    .card-hover {
        transition: transform 0.3s ease, filter 0.3s ease;
        position: relative;
    }

    .card-hover:hover {
        transform: scale(1.04);
        filter: blur(0.3px);
    }

    .card-hover:hover .take-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, 0.6);
        width: 100%;
        height: 100%;
        border-radius: 0.5rem;
    }

    .take-icon {
        z-index: 2;
        color: #fff;
        border-radius: 8px;
        display: none;
        font-size: 2rem;
    }

    .disabled-card {
        opacity: 0.5;
        pointer-events: none;
    }

    .taken-stamp {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(255, 0, 0, 0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
        font-weight: bold;
        transform: rotate(-30deg);
    }
</style>

<div class="container mt-4">
    <div class="row g-3">
        @foreach($demands as $demand)
            @if($demand->taken == FALSE) <!-- Menampilkan hanya jika demand belum diambil -->
                <div class="col-6 col-md-2 col-lg-2">
                    <div class="card shadow-sm rounded-4 border-0 position-relative text-center card-hover" style="background: #ffffff;" data-bs-toggle="modal" data-bs-target="#cardModal{{ $demand->demand_id }}" id="card{{ $demand->demand_id }}">

                        <div class="take-icon position-absolute top-50 start-50 translate-middle text-center">
                            <i class="bi bi-archive"></i>
                        </div>

                        <div class="card-body p-4 position-relative d-flex flex-column align-items-center" style="z-index: 1; font-size: 0.65rem;">
                            <h5 class="fw-bold mb-1" style="font-size: 1rem;">{{ $demand->demand_id }}</h5>
                            <hr style="width: 50%; border: 1px solid #ddd;">
                            <table class="table table-borderless text-start mb-1" style="width: auto;">
                                <tbody>
                                    <tr>
                                        <td><strong>Tujuan</strong></td>
                                        <td>: {{ $demand->tujuan_pengiriman }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Need Day</strong></td>
                                        <td>: {{ $demand->need_day }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Item</strong></td>
                                        <td>: {{ $demand->item->item_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Quantity</strong></td>
                                        <td>: {{ $demand->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Revenue</strong></td>
                                        <td>: ${{ number_format($demand->revenue, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            <img src="{{ asset('assets/BLC_Logo.png') }}" alt="BLC Delivery Logo" style="width: 30%; height: auto;" class="mb-1">
                            <p class="text-muted mb-3" style="font-size: 0.75rem;">Managed by BLC Delivery</p>
                        </div>
                    </div>
                </div>

                <!-- Modal for each demand card -->
                <div class="modal fade" id="cardModal{{ $demand->demand_id }}" tabindex="-1" aria-labelledby="cardModalLabel{{ $demand->demand_id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cardModalLabel{{ $demand->demand_id }}">Demands Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card shadow-sm rounded-4 border-0 position-relative text-center" style="background: #ffffff;">
                                    <div class="card-body p-4 position-relative d-flex flex-column align-items-center" style="z-index: 1; font-size: 0.8rem;">
                                        <h5 class="fw-bold mb-1" style="font-size: 1rem;">{{ $demand->demand_id }}</h5>
                                        <hr style="width: 80%; border: 1px solid #ddd;">
                                        <table class="table table-borderless text-start mb-1" style="width: auto;">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Tujuan</strong></td>
                                                    <td>: {{ $demand->tujuan_pengiriman }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Need Day</strong></td>
                                                    <td>: {{ $demand->need_day }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Item</strong></td>
                                                    <td>: {{ $demand->item->item_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Quantity</strong></td>
                                                    <td>: {{ $demand->quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Revenue</strong></td>
                                                    <td>: ${{ number_format($demand->revenue, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center">
                                        <img src="{{ asset('assets/BLC_Logo.png') }}" alt="BLC Delivery Logo" style="width: 30%; height: auto;" class="mb-1">
                                        <p class="text-muted mb-3" style="font-size: 0.75rem;">Managed by BLC Delivery</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary take-demand" data-demand-id="{{ $demand->demand_id }}" data-bs-dismiss="modal">Take Demand</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<script>
    $(document).ready(function() {
        window.Echo.channel('demand-taken')
        .listen('.DemandTakenEvent', (event) => {
            var demandId = event.demandId;
            var card = $('#card' + demandId);
            card.remove();
        });

        $(document).on('click', '.take-demand', function() {
            var demandId = $(this).data('demand-id');
            var card = $('#card' + demandId);
            var roomId = "{{ $room->room_id }}";
            var playerId = "{{ $player->player_username }}";

            $.ajax({
                url: '/take-demand/',
                method: 'POST',
                data: {
                    demand_id: demandId,
                    room_id: roomId,
                    player_id: playerId,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if(response.status == 'success'){
                        toastr.success(response.message);
                        card.remove();
                    }
                    else{
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error); // Menampilkan error jika gagal
                }
            });
        });
    });
</script>

@endsection
