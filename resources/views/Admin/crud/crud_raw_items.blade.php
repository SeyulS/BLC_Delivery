@extends('layout.admin_home')

@section('container')
<div class="container mt-5">
    <h1 class="text-center mb-4">Raw Items</h1>
    <button class="btn btn-primary mb-3" id="addNewRawItem">Add New Raw Item</button>
    <table id="rawItemsTable" class="table table-bordered">
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
<div class="modal fade" id="rawItemModal" tabindex="-1" aria-labelledby="rawItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <form id="rawItemForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rawItemModalLabel">Add Raw Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="rawItemId">
                    <div class="mb-3">
                        <label for="rawItemName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="rawItemName" name="raw_item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="rawItemPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="rawItemPrice" name="raw_item_price" required>
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
    $(document).ready(function() {
        // Setup CSRF token untuk setiap permintaan AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#rawItemsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/raw-items/data',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'raw_item_name', name: 'raw_item_name' },
                { data: 'raw_item_price', name: 'raw_item_price' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Add New Raw Item Modal
        $('#addNewRawItem').on('click', function() {
            $('#rawItemModal').modal('show');
            $('#rawItemModalLabel').text('Add Raw Item');
            $('#rawItemForm').trigger('reset');
            $('#rawItemId').val('');
        });

        // Edit Raw Item Modal
        $(document).on('click', '.editRawItem', function() {
            var id = $(this).data('id');
            $.get('/raw-items/' + id + '/edit', function(data) {
                $('#rawItemModal').modal('show');
                $('#rawItemModalLabel').text('Edit Raw Item');
                $('#rawItemId').val(data.id);
                $('#rawItemName').val(data.raw_item_name);
                $('#rawItemPrice').val(data.raw_item_price);
            });
        });

        // Save Raw Item (Add or Update)
        $('#rawItemForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#rawItemId').val();
            var url = id ? '/raw-items/' + id : '/raw-items';
            var method = id ? 'PUT' : 'POST';
            // console.log(id);
            // console.log(method);
            // console.log(url);
            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#rawItemModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    alert("Error: " + error);  // Menangani error, jika ada
                }
            });
        });

        // Delete Raw Item
        $(document).on('click', '.deleteRawItem', function() {
            var id = $(this).data('id');
            console.log(id);
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: '/raw-items/delete/' + id,
                    method: 'GET',
                    success: function(response) {
                        table.ajax.reload(); // Reload DataTable
                        toastr.success(response.message);
                    }
                });
            }
        });
    });
</script>

@endsection
