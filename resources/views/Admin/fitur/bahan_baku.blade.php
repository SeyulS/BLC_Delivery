@extends('layout.admin_room')

@section('container')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
        background: #fff;
        padding: 20px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.15);
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        /* Warna teks lebih netral */
    }

    .img-fluid {
        max-width: 120px;
        height: auto;
        margin-bottom: 10px;
    }

    .quantity-input {
        text-align: center;
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 14px;
        width: 80%;
        border-radius: 8px;
        margin: 0 auto;
        /* Biar input quantity tetap di tengah */
        display: block;
    }

    .select-container {
        margin-bottom: 20px;
        text-align: center;
    }

    .form-select {
        width: 40%;
        /* Mengurangi panjang select team */
        margin: 0 auto;
        text-align: center;
    }

    .container-custom {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .button-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
</style>

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

<div class="container mt-4">
    <div class="container-custom">
        <h4 class="text-center mb-3">Bahan Baku</h4>
        <hr>

        <form class="mt-3" id="bahan-baku-form" action="/setting_bahan_baku" method="POST">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->room_id }}">

            <!-- Pilih Tim -->
            <div class="select-container">
                <select class="form-select form-select-lg" id="team-select" name="team">
                    <option value="" selected disabled>Select Team</option>
                    @foreach($players as $player)
                    <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Daftar Item -->
            <div class="row mt-4">
                @foreach($rawItems as $rawItem)
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">{{ $rawItem->raw_item_name }}</h5>
                            <img src="https://tse2.mm.bing.net/th?id=OIP.TJiE4HWiS0s6051Xa63_YAHaFq&pid=Api&P=0&h=220"
                                alt="Bahan Baku" class="img-fluid rounded">
                            <input type="number" class="form-control quantity-input mt-3"
                                name="quantity[{{ $rawItem->id }}]"
                                placeholder="Quantity"
                                data-item-id="{{ $rawItem->id }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Tombol Submit -->
            <div class="button-container">
                <button type="submit" id="submit-button" class="btn btn-secondary">Set Pembelian</button>
            </div>
        </form>
    </div>
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
            const playerName = $('#team-select option:selected').text();
            const roomId = $('input[name="room_id"]').val();
            let quantities = [];
            let itemTable = `<table style="width:100%; text-align:center; border-collapse: collapse;">
                            <tr>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Barang</th>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Quantity</th>
                            </tr>`;

            $('.quantity-input').each(function() {
                const itemId = $(this).data('item-id');
                const quantity = $(this).val();
                const itemName = $(this).closest('.card-body').find('.card-title').text();

                if (quantity && parseInt(quantity) > 0) {
                    quantities.push({
                        item_id: itemId,
                        quantity: parseInt(quantity),
                        item_name: itemName
                    });

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
                            } else {
                                Swal.fire({
                                    title: 'Fail!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                            $('#bahan-baku-form')[0].reset();
                            $('#team-select').val('').trigger('change');

                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan, silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });

                            $('#bahan-baku-form')[0].reset();
                            $('#team-select').val('').trigger('change');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection