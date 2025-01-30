@extends('layout.player_room')

@section('container')

<style>
    .locked-image {
        opacity: 0.5;
        pointer-events: none;
    }

    .locked-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        z-index: 1;
        pointer-events: none;
    }

    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .card img {
        height: 150px;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }

    .machine-input {
        width: 60px;
        margin-right: 10px;
    }

    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
    }

    .btn-produce {
        width: 100%;
        margin-top: 20px;
        font-size: 1rem;
    }
</style>

<div class="container mt-4">
    <h2 class="text-center mb-4">Production Page</h2>
    <form action="/produceItem" method="POST">
        @csrf
        <div class="row g-4">
            @for ($i = 0; $i < count($roomMachine); $i++)
                <input type="hidden" name="machine_id[]" value="{{ $roomMachine[$i] }}">
                <div class="col-md-4">
                    <div class="card shadow-sm position-relative">
                        @if($playerMachineCapacity[$i] == 0)
                        <div class="locked-overlay">
                            <i class="fas fa-lock"></i>
                        </div>
                        @endif
                        <!-- Menggunakan Lorem Picsum untuk gambar acak -->
                        <img src="https://picsum.photos/300/150?random={{ $i }}" alt="Random Image"
                            class="img-fluid {{ $playerMachineCapacity[$i] == 0 ? 'locked-image' : '' }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $roomMachineName[$i] }}</h5>
                            <div class="d-flex justify-content-center align-items-center mt-2">
                                <input type="number"
                                    class="form-control text-center machine-input"
                                    min="0"
                                    max="{{ $playerMachineCapacity[$i] }}"
                                    name="quantityProduce[]"
                                    value="{{ old('quantityProduce.' . $i, 0) }}"
                                    {{ $playerMachineCapacity[$i] == 0 ? 'readonly' : '' }}>
                                <span class="badge bg-secondary">Max: {{ $playerMachineCapacity[$i] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
        </div>
        <button type="submit" class="btn btn-dark btn-produce">Produce</button>
    </form>
</div>

<script>
    $(document).ready(() => {
        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if(session('fail'))
        toastr.error("{{ session('fail') }}");
        @endif

        const playerId = "{{ $player->player_username }}";
        const roomId = "{{ $room->room_id }}";

        setupSimulationEvents(roomId);

        // SweetAlert Confirmation sebelum submit form Produce
        $("form[action='/produceItem']").on("submit", function(event) {
            event.preventDefault(); // Menghentikan default behavior form submit

            // Konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Konfirmasi Produksi',
                text: 'Apakah Anda yakin ingin memproduksi barang dengan jumlah yang telah dipilih?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Produksi!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user memilih "Ya, Produksi!", submit form
                    this.submit(); // Submit form setelah konfirmasi
                }
            });
        });

        window.Echo.channel('update-warehouse')
            .listen('UpdateWarehouse', () => {
                $.ajax({
                    url: '/updateWarehouse',
                    method: 'POST',
                    data: {
                        player_id: playerId,
                        room_id: roomId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.revenue !== undefined) {
                            $('#warehouseCapacity').text(`Revenue: ${response.warehouseCapacity}`);
                            $('#currentCapacity').text(`Revenue: ${response.currentCapacity}`);
                        }
                    },
                    error: (xhr) => {
                        toastr.error('Failed to fetch revenue:', xhr.responseText);
                    }
                });
            });
    });
</script>


@endsection