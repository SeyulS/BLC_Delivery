@extends('layout.main')

@section('script')
<!-- Bootstrap CSS (if not already loaded) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('container')
<div class="container mt-5">
    <div class="d-flex justify-content-center mb-4">
        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createRoomModal">Create Room</button>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createRoomModalLabel">Setup Room Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/createRoom" method="POST">
                        @csrf

                        <!-- Room Number Input -->
                        <div class="form-group mt-3">
                            <label for="roomCode" class="form-label">Room Number</label>
                            <input type="text" class="form-control" name="roomCode" id="roomCode" maxlength="3" placeholder="Enter 3 Digit" required>
                        </div>

                        <!-- Room Description Input -->
                        <div class="form-group mt-3">
                            <label for="roomDescription" class="form-label">Room Description</label>
                            <input type="text" class="form-control" id="roomDescription" name="roomDescription" placeholder="Enter Room Description" required>
                        </div>

                        <!-- Number of Days -->
                        <div class="form-group mt-3">
                            <label for="numDays" class="form-label">Number of Days</label>
                            <input type="number" class="form-control" id="numDays" name="numDays" placeholder="Enter number of days" min="1" required>
                        </div>

                        <!-- Special Days Input -->
                        <div class="form-group mt-3" id="specialDaysContainer">
                            <label class="form-label">Special Days</label>
                            <div class="d-flex align-items-center mb-2">
                                <input type="number" class="form-control" name="specialDays[]" placeholder="Enter special day" min="1">
                                <button type="button" class="btn btn-success btn-sm ms-2" id="addSpecialDay">+</button>
                            </div>
                        </div>

                        <!-- Items Selection -->
                        <div class="form-group mt-3">
                            <label class="form-label">Items</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-select" name="item1" id="item1" required>
                                        <option value="" selected disabled>Select Item 1</option>
                                        @foreach ($items as $item)
                                        <option value="{{ $item->item_id }}">{{ $item->item_id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="item2" id="item2">
                                        <option value="" selected disabled>Select Item 2</option>
                                        @foreach ($items as $item)
                                        <option value="{{ $item->item_id }}">{{ $item->item_id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="item3" id="item3">
                                        <option value="" selected disabled>Select Item 3</option>
                                        @foreach ($items as $item)
                                        <option value="{{ $item->item_id }}">{{ $item->item_id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Deck Selection -->
                        <div class="form-group mt-3">
                            <label class="form-label">Deck</label>
                            <select class="form-select" name="deck" id="deck" required>
                                <option value="" selected disabled>Select Deck</option>
                                @foreach ($decks as $deck)
                                <option value="{{ $deck->deck_id }}">{{ $deck->deck_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Warehouse Size and Price -->
                        <div class="form-group mt-3">
                            <label for="warehouseSize" class="form-label">Warehouse Size</label>
                            <input type="number" class="form-control" name="warehouseSize" id="warehouseSize" placeholder="Enter Warehouse Size" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="warehousePrice" class="form-label">Warehouse Price</label>
                            <input type="number" class="form-control" name="warehousePrice" id="warehousePrice" placeholder="Enter Warehouse Price" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Room Details</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Existing Rooms List -->
    <div class="row mt-4">
        @foreach($rooms as $room)
        <div class="col-md-3">
            <div class="card text-dark bg-light mb-3">
                <div class="card-header text-center">{{ $room->room_name }}</div>
                <div class="card-body text-center">
                    <ul class="list-unstyled">Day : {{ $room->recent_day }}</ul>
                    <ul class="list-unstyled">Status : </ul>
                    <ul class="list-unstyled">Total Players : {{ $room->total_players }}</ul>
                    <div class="d-flex justify-content-center">
                        <a href="/lobby/{{ $room->room_id }}">
                            <button class="btn btn-primary" type="button" class="btn btn-info btn-sm">Manage</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const specialDaysContainer = document.getElementById('specialDaysContainer');
        const addSpecialDayBtn = document.getElementById('addSpecialDay');

        addSpecialDayBtn.addEventListener('click', function() {
            // Create a new input group for special days
            const inputGroup = document.createElement('div');
            inputGroup.className = 'd-flex align-items-center mb-2';

            // Create the new input element
            const newInput = document.createElement('input');
            newInput.type = 'number';
            newInput.name = 'specialDays[]';
            newInput.className = 'form-control';
            newInput.placeholder = 'Enter special day';
            newInput.min = '1';

            // Create the remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-danger btn-sm ms-2';
            removeBtn.textContent = '-';

            // Add event listener to remove button
            removeBtn.addEventListener('click', function() {
                specialDaysContainer.removeChild(inputGroup);
            });

            // Append the input and remove button to the input group
            inputGroup.appendChild(newInput);
            inputGroup.appendChild(removeBtn);

            // Append the input group to the container
            specialDaysContainer.appendChild(inputGroup);
        });
    });
</script>

