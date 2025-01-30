@extends('layout.admin_home')

@section('container')
<div class="container mt-5">

    <!-- Button to toggle the form visibility -->
    <button id="toggleFormButton" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-down-circle"></i> Create Room
    </button>

    <!-- Form Box (Initially hidden) -->
    <div id="createRoomForm" class="form-box shadow-sm p-4 rounded mb-4" style="display: none;">
        <form action="/createRoom" method="POST">
            @csrf

            <!-- Room Number and Room Description (Row 1) -->
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-md-4">
                        <label for="roomCode" class="form-label">Room Number</label>
                        <input type="text" class="form-control" name="roomCode" id="roomCode" maxlength="3" placeholder="Enter 3 Digit" required>
                    </div>
                    <div class="col-md-4">
                        <label for="roomDescription" class="form-label">Room Description</label>
                        <input type="text" class="form-control" id="roomDescription" name="roomDescription" placeholder="Enter Room Description" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Deck</label>
                        <select class="form-select" name="deck" id="deck" required>
                            <option value="" selected disabled>Select Deck</option>
                            @foreach ($decks as $deck)
                            <option value="{{ $deck->id }}">{{ $deck->deck_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Number of Days and Special Days (Row 2) -->
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <label for="numDays" class="form-label">Number of Days</label>
                        <input type="number" class="form-control" id="numDays" name="numDays" placeholder="Enter number of days" min="1" required>
                    </div>
                    <div class="col-md-6" id="specialDaysContainer">
                        <label class="form-label">Special Days</label>
                        <div class="d-flex align-items-center mb-2">
                            <input type="number" class="form-control" name="specialDays[]" placeholder="Enter special day" min="1">
                            <button type="button" class="btn btn-success btn-sm ms-2" id="addSpecialDay">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Selection (Row 3) -->
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
            <div class="form-group mt-3">
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

            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <label for="inventoryCost" class="form-label">Inventory Cost</label>
                        <input type="number" class="form-control" name="inventoryCost" id="inventoryCost" placeholder="Enter Inventory Cost" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lateDeliveryCost" class="form-label">Late Delivery Cost</label>
                        <input type="number" class="form-control" name="lateDeliveryCost" id="lateDeliveryCost" placeholder="Enter Late Delivery Cost" required>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-secondary ms-auto d-block">Create</button>
            </div>

        </form>
    </div>

    <!-- Existing Rooms List -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="shadow-sm p-3 rounded">
                <h5>Existing Rooms</h5>
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
                            <td>{{ $room->status == 0 ? 'Paused' : 'Ongoing' }}</td>
                            <td>{{ $room->total_players }}</td>
                            <td class="text-center">
                                <a href="/lobby/{{ $room->room_id }}">
                                    <button class="btn btn-secondary" type="button">
                                    <i class="bi bi-gear"></i>
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
        $('#roomsTable').DataTable();  // Initialize DataTable
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
