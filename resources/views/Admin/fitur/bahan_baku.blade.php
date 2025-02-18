@extends('layout.admin_room')

@section('container')
<style>
    .container-custom {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        margin-top: 2rem;
    }

    .page-title {
        color: #1a202c;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .select-container {
        margin-bottom: 2rem;
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
</style>

<div class="container">
    <div class="container-custom">
        <h4 class="page-title">Bahan Baku Management</h4>
        <hr>

        <form id="bahan-baku-form" action="/setting_bahan_baku" method="POST">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->room_id }}">

            <div class="select-container">
                <select class="form-select" id="team-select" name="team">
                    <option value="" selected disabled>Select Team</option>
                    @foreach($players as $player)
                    <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                    @endforeach
                </select>
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