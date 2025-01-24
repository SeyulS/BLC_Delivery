@extends('layout.admin_room')

@section('script')
<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('style')
<style>
    .form-control {
        width: 30%;
        /* Panjang input hanya 30% */
        text-align: center;
        /* Teks di dalam input rata tengah */
        margin: 0 auto;
        /* Pusatkan input */
    }

    .card img {
        max-width: 100%;
        /* Membuat gambar responsif */
        height: auto;
        margin-bottom: 10px;
        /* Tambahkan jarak bawah */
    }

    .select-container {
        margin-bottom: 20px;
        /* Jarak bawah untuk select */
    }

    .row .card {
        margin: 15px 0;
        /* Jarak antar kartu */
    }
</style>
@endsection

@section('container')
<div class="container mt-4">
    @if(session('success'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ session("success") }}');
        });
    </script>
    @endif

    @if(session('fail'))
    <script>
        $(document).ready(function() {
            toastr.error('{{ session("fail") }}');
        });
    </script>
    @endif

    <h3 class="text-center">Bahan Baku</h3>
    <br>

    <form id="bahan-baku-form" action="/setting_bahan_baku" method="POST">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->room_id }}">

        <!-- Pilih Tim -->
        <div class="select-container text-center">
            <select class="form-select form-select-lg" id="team-select" name="team" aria-label="Large select example">
                <option value="" selected disabled>Select Team</option>
                @foreach($players as $player)
                <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                @endforeach
            </select>
        </div>
        <br>

        <div class="row">
            @foreach($rawItems as $rawItem)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $rawItem->raw_item_name }}</h5>
                        <img src="https://tse2.mm.bing.net/th?id=OIP.TJiE4HWiS0s6051Xa63_YAHaFq&pid=Api&P=0&h=220" alt="Kayu">
                        <input type="number" class="form-control quantity-input"
                            name="quantity[{{ $rawItem->id }}]"
                            xplaceholder="Quantity"
                            data-item-id="{{ $rawItem->id }}"
                            style="width: 35%; margin-top: 15px; margin: 0 auto;">
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <button type="submit" id="submit-button" class="btn btn-danger">Set Pembelian</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#team-select').select2({
            placeholder: "Select Team",
            allowClear: true,
            width: '100%'
        });

        $('#submit-button').click(function(e) {
            e.preventDefault();

            const playerId = $('#team-select').val();
            const roomId = $('input[name="room_id"]').val();
            const quantities = [];

            $('.quantity-input').each(function() {
                const itemId = $(this).data('item-id');
                const quantity = $(this).val();

                if (quantity && parseInt(quantity) > 0) {
                    quantities.push({
                        item_id: itemId,
                        quantity: parseInt(quantity)
                    });
                }
            });

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
                    text: 'Please select at least 1 items!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            console.log({
                player_id: playerId,
                room_id: roomId,
                items: quantities
            });

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
                    toastr.success(response);
                    console.log(response);
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON;
                    console.error('Error:', errors);
                    toastr.error('Terjadi kesalahan, silakan coba lagi.');
                }
            });
        });
    });
</script>


@endsection