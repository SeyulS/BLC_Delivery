@extends('layout.player_room')

@section('container')
<div class="container mt-4">


    <!-- Warehouse Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Warehouse</h5>
        </div>
        <div class="card-body">
            <p id="warehouseCapacity"><strong>Warehouse Capacity:</strong> {{ $player->inventory }} m²</p>
            <p id="usedCapacity"><strong>Current Capacity:</strong> {{ $usedCapacity }} m² / {{ $player->inventory }} m²</p>
            <p><strong>Warehouse Price: </strong>{{ $room->warehouse_size }} m² - ${{ $room->warehouse_price }}</p>
            <form action="/purchaseWarehouse" method="POST">
                @csrf
                <!-- Input Hidden untuk warehouse_id -->
                <input type="hidden" name="warehouse_id">
                <!-- Button dengan room_id -->
                <button type="submit" name="room_id" value="{{ $room->room_id }}" class="btn btn-primary">
                    Purchase Warehouse
                </button>
            </form>

        </div>
    </div>

    <!-- Machines Section -->
    <div class="card">
        <div class="card-header bg-success text-white">
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
                <button type="submit" class="btn btn-success">Purchase Machine</button>
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
        $("form[action='/purchaseWarehouse']").submit(function(event) {
            event.preventDefault(); // Menghentikan form untuk submit agar bisa diuji

            // Mencetak data yang dikirim pada form
            console.log("Form is about to be submitted");
            console.log("Player ID:", playerId);
            console.log("Room ID:", roomId);


            // Kirim form menggunakan ajax jika perlu
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

        });

        // window.Echo.channel('update-warehouse')
        //     .listen('UpdateWarehouse', () => {
        //         $.ajax({
        //             url: '/updateWarehouse',
        //             method: 'POST',
        //             data: {
        //                 player_id: playerId,
        //                 room_id: roomId,
        //                 _token: '{{ csrf_token() }}',
        //             },
        //             success: function(response) {
        //                 if (response.revenue !== undefined) {
        //                     $('#warehouseCapacity').text(`Revenue: ${response.warehouseCapacity}`);
        //                     $('#usedCapacity').text(`Revenue: ${response.currentCapacity}`);
        //                 }
        //             },
        //             error: (xhr) => {
        //                 toastr.error('Failed to fetch revenue:', xhr.responseText);
        //             }
        //         });
        //     });
    });
</script>


@endsection