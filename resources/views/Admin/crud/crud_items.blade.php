@extends('layout.admin_home')

@section('container')
<div class="container mt-5">
    <h1 class="text-center mb-4">Items</h1>
    <button class="btn btn-primary mb-3" id="addNewItem">Add New Item</button>
    <table id="itemsTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="itemForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="itemId">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="itemName" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="itemPrice" name="item_price" required>
                    </div>

                    <div id="dynamicFields">
                        <div class="mb-3 dynamic-row">
                            <label for="rawItemNeeded" class="form-label">Raw Item Needed</label>
                            <select class="form-control rawItemSelect" name="raw_item_needed[]">
                                <option value="">Select Raw Item</option>
                                @foreach($rawItems as $rawItem)
                                <option value="{{ $rawItem->id }}">{{ $rawItem->raw_item_name }}</option>
                                @endforeach
                            </select>
                            <input type="number" class="form-control mt-2" name="raw_item_quantity_needed[]" placeholder="Quantity" required>
                        </div>
                        <!-- Button to add more fields -->
                    </div>
                    <button type="button" class="btn btn-secondary" id="addMoreFields">+</button>
                    <div class="mb-3">
                        <label for="itemSize" class="form-label">Item Size (m2)</label>
                        <input type="number" class="form-control" id="itemSize" name="item_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemVolume" class="form-label">Item Volume</label>
                        <input type="number" class="form-control" id="itemVolume" name="item_price" required>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTables and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
    $('#itemForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#itemId').val();
        var url = id ? '/items/' + id : '/items';
        var method = id ? 'PUT' : 'POST';

        // Initialize arrays for raw item data
        var rawItemNeeded = [];
        var rawItemQuantityNeeded = [];

        // Process dynamic fields
        $('#dynamicFields .dynamic-row').each(function() {
            var rawItemValue = $(this).find('select[name="raw_item_needed[]"]').val();
            var quantityValue = $(this).find('input[name="raw_item_quantity_needed[]"]').val();

            if (rawItemValue && quantityValue) {
                rawItemNeeded.push(rawItemValue);
                rawItemQuantityNeeded.push(quantityValue);
            }
        });

        // Log the arrays to see the structured data
        console.log('Raw Item Needed:', rawItemNeeded);
        console.log('Raw Item Quantity Needed:', rawItemQuantityNeeded);

        // Now serialize the rest of the form
        var formData = $(this).serializeArray();

        // Log the form data including the raw item arrays
        console.log('Serialized Form Data:', formData);

        // Add raw item arrays to formData manually
        formData.push({
            name: 'raw_item_needed[]',
            value: rawItemNeeded
        });
        formData.push({
            name: 'raw_item_quantity_needed[]',
            value: rawItemQuantityNeeded
        });

        $.ajax({
            url: url,
            method: method,
            data: formData, // Send structured data
            success: function(response) {
                $('#itemModal').modal('hide');
                table.ajax.reload();
                toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                alert("Error: " + error); // Handle error if any
            }
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#itemsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/items/data',
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'item_name',
                    name: 'item_name'
                },
                {
                    data: 'item_price',
                    name: 'item_price'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Add New Item Modal
        $('#addNewItem').on('click', function() {
            $('#itemModal').modal('show');
            $('#itemModalLabel').text('Add Item');
            $('#itemForm').trigger('reset');
            $('#itemId').val('');
        });

        // Edit Item Modal
        $(document).on('click', '.editItem', function() {
            var id = $(this).data('id');
            $.get('/items/' + id + '/edit', function(data) {
                $('#itemModal').modal('show');
                $('#itemModalLabel').text('Edit Item');
                $('#itemId').val(data.id);
                $('#itemName').val(data.item_name);
                $('#itemPrice').val(data.item_price);

                // Reset and add dynamic fields based on existing data
                $('#dynamicFields').empty();
                data.raw_items.forEach(function(rawItem) {
                    var newField = `
                        <div class="mb-3 dynamic-row">
                            <label for="rawItemNeeded" class="form-label">Raw Item Needed</label>
                            <select class="form-control rawItemSelect" name="raw_item_needed[]">
                                <option value="">Select Raw Item</option>
                                @foreach($rawItems as $rawItem)
                                    <option value="{{ $rawItem->id }}" ${rawItem.id == rawItem.id ? 'selected' : ''}>{{ $rawItem->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" class="form-control mt-2" name="raw_item_quantity_needed[]" placeholder="Quantity" value="${rawItem.pivot.quantity_needed}" required>
                            <button type="button" class="btn btn-danger mt-2 removeField">Remove</button>
                        </div>
                    `;
                    $('#dynamicFields').append(newField);
                });
            });
        });

        // Save Item (Add or Update)
        $('#itemForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#itemId').val();
            var url = id ? '/items/' + id : '/items';
            var method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#itemModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    alert("Error: " + error); // Menangani error, jika ada
                }
            });
        });

        // Delete Item
        $(document).on('click', '.deleteItem', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: '/items/delete/' + id,
                    method: 'GET',
                    success: function(response) {
                        table.ajax.reload(); // Reload DataTable
                        toastr.success(response.message);
                    }
                });
            }
        });

        // Add more fields dynamically
        $('#addMoreFields').on('click', function() {
            var newField = `
                <div class="mb-3 dynamic-row">
                    <label for="rawItemNeeded" class="form-label">Raw Item Needed</label>
                    <select class="form-control rawItemSelect" name="raw_item_needed[]">
                        <option value="">Select Raw Item</option>
                        @foreach($rawItems as $rawItem)
                            <option value="{{ $rawItem->id }}">{{ $rawItem->raw_item_name }}</option>
                        @endforeach
                    </select>
                    <input type="number" class="form-control mt-2" name="raw_item_quantity_needed[]" placeholder="Quantity" required>
                    <button type="button" class="btn btn-danger mt-2 removeField">Remove</button>
                </div>
            `;
            $('#dynamicFields').append(newField);
        });

        // Remove field dynamically
        $(document).on('click', '.removeField', function() {
            $(this).closest('.dynamic-row').remove();
        });
    });
</script>

@endsection