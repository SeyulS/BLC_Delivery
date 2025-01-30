@extends('layout.player_room')

@section('container')
<div class="container mt-4">


    <!-- Warehouse Section -->
    <div class="card mb-4">
        <div class="card-header bg-light text-dark">
            <h5>Warehouse</h5>
        </div>
        <div class="card-body">
            <p id="warehouseCapacity"><strong>Warehouse Capacity:</strong> {{ $player->inventory }} m²</p>
            <p id="currentCapacity"><strong>Current Capacity:</strong> {{ $usedCapacity }} m² / {{ $player->inventory }} m²</p>
            <p><strong>Warehouse Price: </strong>{{ $room->warehouse_size }} m² - ${{ $room->warehouse_price }}</p>
            <form>
                @csrf
                <!-- Input Hidden untuk warehouse_id -->
                <input type="hidden" name="warehouse_id">
                <!-- Button dengan room_id -->
                <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-dark" id="purchase">
                    Purchase Warehouse
                </button>
            </form>
        </div>
    </div>

    <!-- Machines Section -->
    <div class="card">
        <div class="card-header bg-light text-dark">
            <h5>Machines</h5>
        </div>
        <div class="card-body">
            <p><strong>Current Machine Capacities:</strong></p>
            <ul>
                @for ($i = 0; $i < count($playerMachineCapacity); $i++)
                    <p>{{ $machineName[$i] }} : {{ $playerMachineCapacity[$i]}} Units</p>
                    @endfor
            </ul>
            <form action="/purchaseMachine" method="POST">
                <input type="hidden" name="room_id" value="{{ $room->room_id }}">

                @csrf
                <div class="form-group">
                    <label for="machineType">Select Machine Type:</label>
                    <select name="machineType" id="machineType" class="form-control" required>
                        <option value="" disabled selected>Select Machine Type</option>
                        @for ($i = 0; $i < count($machine); $i++)
                            <option value="{{ $machine[$i] }}">{{ $machineName[$i] }} - ${{ $machinePrice[$i] }}</option>
                            @endfor
                    </select>
                </div>
                <script>
                    document.querySelector("form").addEventListener("submit", function(event) {
                        const machineType = document.getElementById("machineType").value;
                        if (!machineType) {
                            event.preventDefault();
                            // toastr.error("Choose 1 Machine");
                        }
                    });
                </script>

                <br>
                <button type="submit" class="btn btn-dark">Purchase Machine</button>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(() => {
        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
        toastr.error("{{ session('error') }}");
        @endif

        const playerId = "{{ $player->player_username }}";
        const roomId = "{{ $room->room_id }}";

        setupSimulationEvents(roomId);

        // Event listener untuk pembelian warehouse
        $("#purchase").on("click", function(event) {
            event.preventDefault(); // Menghentikan default behavior tombol

            // Konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Konfirmasi Pembelian Warehouse',
                text: `Apakah Anda yakin ingin membeli warehouse dengan kapasitas ${{{ $room->warehouse_size }}} m² seharga ${{ $room->warehouse_price }}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, beli!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mencetak data yang dikirim pada form
                    console.log("Tombol Purchase ditekan");
                    console.log("Player ID:", playerId);
                    console.log("Room ID:", roomId);

                    // Kirim form menggunakan AJAX jika konfirmasi
                    $.ajax({
                        url: '/purchaseWarehouse',
                        method: 'POST',
                        data: {
                            room_id: roomId,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log('Success Response:', response);
                            if (response.success) {
                                $('#warehouseCapacity').html(`<strong>Warehouse Capacity:</strong> ${response.currentWarehouse} m²`);
                                $('#currentCapacity').html(`<strong>Current Capacity:</strong> ${response.currentCapacity} m² / ${response.currentWarehouse} m²`);
                                $('#player_inventory').html(`<p class="me-7">${response.player_inventory}</p>`);
                                $('#player_revenue').html(`<p class="me-7">${response.player_revenue}</p>`)
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error Response:', xhr.responseText);
                            toastr.error('Error: ' + xhr.responseText);
                        }
                    });
                }
            });
        });

        // Event listener untuk pembelian mesin
        $("form[action='/purchaseMachine']").on("submit", function(event) {
            event.preventDefault(); // Menghentikan default behavior form submit

            // Ambil data yang diperlukan
            const machineType = $('#machineType').val();

            // Konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Konfirmasi Pembelian Mesin',
                text: `Apakah Anda yakin ingin membeli mesin ${machineType}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, beli!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim form menggunakan AJAX jika konfirmasi
                    $.ajax({
                        url: '/purchaseMachine',
                        method: 'POST',
                        data: {
                            room_id: roomId,
                            machineType: machineType,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log('Success Response:', response.message);
                            if (response.status == 'fail') {
                                toastr.error(response.message);
                            } else {
                                toastr.success(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error Response:', xhr.responseText);
                            toastr.error('Error: ' + xhr.responseText);
                        }
                    });
                }
            });
        });

    });
</script>



@endsection