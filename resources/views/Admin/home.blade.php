@extends('layout.admin_home')

@section('container')

<div class="container mt-5">
    <!-- Button to toggle the form visibility -->
    <button id="toggleFormButton" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-down-circle"></i> Create Room
    </button>

    <!-- Form Box (Initially hidden) -->
    <div id="createRoomForm" class="form-box shadow-sm p-4 rounded mb-4" style="display: none;">
        <h5>Create Room Form</h5>
        <hr>
        <form action="/createRoom" method="POST">
            @csrf
            <!-- Room Number and Room Description (Row 1) -->
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-md-4">
                        <label for="roomCode" class="form-label">Room Number</label>
                        <input type="text" class="form-control" name="roomCode" id="roomCode" maxlength="3" placeholder="Enter 3 Digit" required>
                    </div>
                    <div class="col-md-8">
                        <label for="roomDescription" class="form-label">Room Description</label>
                        <input type="text" class="form-control" id="roomDescription" name="roomDescription" placeholder="Enter Room Description" required>
                    </div>
                </div>
            </div>

            <!-- Number of Days and Special Days (Row 2) -->
            <!-- Number of Days and Special Days (Row 2) -->
            <div class="form-group mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <label for="numDays" class="form-label">Number of Days</label>
                        <input type="number" class="form-control" id="numDays" name="numDays" placeholder="Enter number of days" min="1" required>
                    </div>
                    <div class="col-md-6" id="specialDaysContainer">
                        <label class="form-label">Special Days</label>
                        <!-- Remove mb-2 from this div to match spacing -->
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control" name="specialDays[]" placeholder="Enter special day" min="1">
                            <button type="button" class="btn btn-success btn-sm ms-2" id="addSpecialDay">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <label class="form-label">Items</label>
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-select" name="item1" id="item1" required>
                            <option value="" selected disabled>Select Item 1</option>
                            @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="item2" id="item2">
                            <option value="" selected disabled>Select Item 2</option>
                            @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="item3" id="item3">
                            <option value="" selected disabled>Select Item 3</option>
                            @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Warehouse Size and Price (Side by Side) -->
            <div class="form-group mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <label for="warehouseSize" class="form-label">Warehouse Size</label>
                        <input type="number" class="form-control" name="warehouseSize" id="warehouseSize" placeholder="Enter Warehouse Size" required>
                    </div>
                    <div class="col-md-6">
                        <label for="warehousePrice" class="form-label">Warehouse Price</label>
                        <input type="number" class="form-control" name="warehousePrice" id="warehousePrice" placeholder="Enter Warehouse Price" required>
                    </div>
                </div>
            </div>

            <div class="form-group mt-5">
                <h5>Makassar</h5>
                <hr>
                <div class="row">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" value="Less Container Load" readonly>
                    </div>
                    <div class="col">
                        <label for="mks[LCL_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="mks[LCL_volume_capacity]" id="mks[LCL_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mks[LCL_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="mks[LCL_weight_capacity]" id="mks[LCL_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mks[LCL_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="mks[LCL_price]" id="mks[LCL_price]" required>
                    </div>
                    <div class="col">
                        <label for="mks[LCL_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="mks[LCL_duration]" id="mks[LCL_duration]" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" name="Delivery Type" id="Delivery Type " value="Full Container Load" readonly>
                    </div>
                    <div class="col">
                        <label for="mks[FCL_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="mks[FCL_volume_capacity]" id="FCL_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mks[FCL_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="mks[FCL_weight_capacity]" id="FCL_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mks[FCL_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="mks[FCL_price]]" id="FCL_price]" required>
                    </div>
                    <div class="col">
                        <label for="mks[FCL_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="mks[FCL_duration]" id="FCL_duration]" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" value="Udara" readonly>
                    </div>
                    <div class="col">
                        <label for="mks[udara_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="mks[udara_volume_capacity]" id="mks[udara_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mks[udara_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="mks[udara_weight_capacity]" id="mks[udara_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mks[udara_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="mks[udara_price]" id="mks[udara_price]" required>
                    </div>
                    <div class="col">
                        <label for="mks[udara_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="mks[udara_duration]" id="mks[udara_duration]" required>
                    </div>
                </div>
                <hr>
            </div>

            <div class="form-group mt-5">
                <h5>Banjarmasin</h5>
                <hr>
                <div class="row">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" value="Less Container Load" readonly>
                    </div>
                    <div class="col">
                        <label for="banjar[LCL_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="banjar[LCL_volume_capacity]" id="banjar[LCL_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[LCL_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="banjar[LCL_weight_capacity]" id="banjar[LCL_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[LCL_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="banjar[LCL_price]" id="banjar[LCL_price]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[LCL_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="banjar[LCL_duration]" id="banjar[LCL_duration]" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" name="Delivery Type" id="Delivery Type " value="Full Container Load" readonly>
                    </div>
                    <div class="col">
                        <label for="banjar[FCL_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="banjar[FCL_volume_capacity]" id="banjar[FCL_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[FCL_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="banjar[FCL_weight_capacity]" id="banjar[FCL_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[FCL_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="banjar[FCL_price]" id="banjar[FCL_price]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[FCL_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="banjar[FCL_duration]" id="banjar[FCL_duration]" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" name="Delivery Type" id="Delivery Type " value="Udara" readonly>
                    </div>
                    <div class="col">
                        <label for="banjar[udara_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="banjar[udara_volume_capacity]" id="banjar[udara_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[udara_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="banjar[udara_weight_capacity]" id="banjar[udara_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[udara_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="banjar[udara_price]" id="banjar[udara_price]" required>
                    </div>
                    <div class="col">
                        <label for="banjar[udara_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="banjar[udara_duration]" id="banjar[udara_duration]" required>
                    </div>
                </div>
                <hr>
            </div>

            <div class="form-group mt-5">
                <h5>Manado</h5>
                <hr>
                <div class="row">

                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" name="Delivery Type" id="Delivery Type " value="Less Container Load" readonly>
                    </div>
                    <div class="col">
                        <label for="mnd[LCL_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="mnd[LCL_volume_capacity]" id="mnd[LCL_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[LCL_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="mnd[LCL_weight_capacity]" id="mnd[LCL_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[LCL_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="mnd[LCL_price]" id="mnd[LCL_price]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[LCL_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="mnd[LCL_duration]" id="mnd[LCL_duration]" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" name="Delivery Type" id="Delivery Type " value="Full Container Load" readonly>
                    </div>
                    <div class="col">
                        <label for="mnd[FCL_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="mnd[FCL_volume_capacity]" id="mnd[FCL_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[FCL_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="mnd[FCL_weight_capacity]" id="mnd[FCL_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[FCL_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="mnd[FCL_price]" id="mnd[FCL_price]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[FCL_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="mnd[FCL_duration]" id="mnd[FCL_duration]" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="Delivery Type" class="form-label">Delivery Type</label>
                        <input type="text" class="form-control" name="Delivery Type" id="Delivery Type " value="Udara" readonly>
                    </div>
                    <div class="col">
                        <label for="mnd[udara_volume_capacity]" class="form-label">Volume Capacity</label>
                        <input type="number" class="form-control" name="mnd[udara_volume_capacity]" id="mnd[udara_volume_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[udara_weight_capacity]" class="form-label">Weight Capacity</label>
                        <input type="number" class="form-control" name="mnd[udara_weight_capacity]" id="mnd[udara_weight_capacity]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[udara_price]" class="form-label">Price</label>
                        <input type="number" class="form-control" name="mnd[udara_price]" id="mnd[udara_price]" required>
                    </div>
                    <div class="col">
                        <label for="mnd[udara_duration]" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="mnd[udara_duration]" id="mnd[udara_duration]" required>
                    </div>
                </div>
                <hr>
            </div>

            <div class="container-fluid mt-5">
                <div class="row">
                    <!-- Loan Type 1 -->
                    <div class="col-md-4">
                        <label for="loanValue1" class="form-label">Loan Type 1</label>
                        <input type="number" class="form-control mb-2" name="loanValue1" id="loanValue1" placeholder="Enter Loan Value" required>
                        <div class="row">
                            <div class="col-md-7">
                                <input type="number" class="form-control mb-2" name="loanInterest1" id="loanInterest1" placeholder="Loan Interest (%)" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="loanDue1" id="loanDue1" placeholder="Loan Due" required>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Type 2 -->
                    <div class="col-md-4">
                        <label for="loanValue2" class="form-label">Loan Type 2</label>
                        <input type="number" class="form-control mb-2" name="loanValue2" id="loanValue2" placeholder="Enter Loan Value" required>
                        <div class="row">
                            <div class="col-md-7">
                                <input type="number" class="form-control mb-2" name="loanInterest2" id="loanInterest2" placeholder="Loan Interest (%)" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="loanDue2" id="loanDue2" placeholder="Loan Due" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Type 3 -->
                    <div class="col-md-4">
                        <label for="loanValue3" class="form-label">Loan Type 3</label>
                        <input type="number" class="form-control mb-2" name="loanValue3" id="loanValue3" placeholder="Enter Loan Value" required>
                        <div class="row">
                            <div class="col-md-7">
                                <input type="number" class="form-control mb-2" name="loanInterest3" id="loanInterest3" placeholder="Loan Interest (%)" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="loanDue3" id="loanDue3" placeholder="Loan Due" step="0.01" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <label for="inventoryCost" class="form-label">Inventory Cost</label>
                        <input type="number" class="form-control" name="inventoryCost" id="inventoryCost" placeholder="Enter Inventory Cost" required>
                    </div>
                    <div class="col-md-3">
                        <label for="earlyDeliveryCost" class="form-label">Early Delivery Cost</label>
                        <input type="number" class="form-control" name="earlyDeliveryCost" id="earlyDeliveryCost" placeholder="Enter early Delivery Cost" required>
                    </div>
                    <div class="col-md-3">
                        <label for="lateDeliveryCost" class="form-label">Late Delivery Cost</label>
                        <input type="number" class="form-control" name="lateDeliveryCost" id="lateDeliveryCost" placeholder="Enter Late Delivery Cost" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cardPerDays" class="form-label">Card Per Days</label>
                        <input type="number" class="form-control" name="cardPerDays" id="cardPerDays" placeholder="Card Per Days" required>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-secondary ms-auto d-block">Create</button>
            </div>

        </form>
    </div>

    <!-- <form action="/cobaGenerate" method="post">
        @csrf
        <button type="submit" class="btn btn-secondary ms-auto d-block">Generate</button>
    </form> -->

    <!-- Existing Rooms List -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="shadow-sm p-3 rounded">
                <h5>Available Rooms</h5>
                <table id="roomsTable" class="display">
                    <thead>
                        <tr>
                            <th>Room ID</th>
                            <th>Room Description</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Total Players</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td>{{ $room->room_id }}</td>
                            <td>{{ $room->room_name }}</td>
                            <td>{{ $room->recent_day }}</td>
                            <td>{{ $room->start == 0 ? 'Not Yet Started' : 'Ongoing' }}</td>
                            <td>{{ $room->total_players }}</td>
                            <td class="text-center">
                                <a href="/lobby/{{ $room->room_id }}">
                                    <button class="btn btn-secondary" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </a>
                                <a href="/lobby/{{ $room->room_id }}">
                                    <button class="btn btn-danger" type="button">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#roomsTable').DataTable(); // Initialize DataTable
    });

    document.addEventListener('DOMContentLoaded', function() {
        const specialDaysContainer = document.getElementById('specialDaysContainer');
        const addSpecialDayBtn = document.getElementById('addSpecialDay');
        const toggleFormButton = document.getElementById('toggleFormButton');
        const createRoomForm = document.getElementById('createRoomForm');

        addSpecialDayBtn.addEventListener('click', function() {
            const inputGroup = document.createElement('div');
            inputGroup.className = 'd-flex align-items-center mb-2';

            const newInput = document.createElement('input');
            newInput.type = 'number';
            newInput.name = 'specialDays[]';
            newInput.className = 'form-control';
            newInput.placeholder = 'Enter special day';
            newInput.min = '1';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-danger btn-sm ms-2';
            removeBtn.textContent = '-';

            removeBtn.addEventListener('click', function() {
                specialDaysContainer.removeChild(inputGroup);
            });

            inputGroup.appendChild(newInput);
            inputGroup.appendChild(removeBtn);

            specialDaysContainer.appendChild(inputGroup);
        });

        toggleFormButton.addEventListener('click', function() {
            if (createRoomForm.style.display === 'none') {
                createRoomForm.style.display = 'block';
                toggleFormButton.innerHTML = '<i class="bi bi-arrow-down-circle"></i> Close Form';
            } else {
                createRoomForm.style.display = 'none';
                toggleFormButton.innerHTML = '<i class="bi bi-arrow-up-circle"></i> Create Room';
            }
        });
    });
</script>

@endsection