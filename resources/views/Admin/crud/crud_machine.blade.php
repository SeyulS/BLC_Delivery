@extends('layout.admin_home')

@section('container')
<div class="container py-5">
    <!-- Header Section -->
    <div class="mb-4">
        <h1 class="fw-bold text-dark mb-1">Machine Management</h1>
        <p class="text-muted mb-0">Register and manage production machines</p>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold mb-0">Add New Machine</h5>
                    </div>

                    <form id="machineForm">
                        @csrf
                        <input type="hidden" id="itemId">

                        <!-- Machine Details Section -->
                        <div class="mb-4">
                            <label for="machine_name" class="form-label fw-medium">Machine Name</label>
                            <input type="text" class="form-control form-control-lg"
                                id="machine_name" name="machine_name" required
                                placeholder="Enter machine name">
                        </div>

                        <!-- Specifications Section -->
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-3">Machine Specifications</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control"
                                            id="machine_size" name="machine_size"
                                            required min="0" step="0.01"
                                            placeholder="Size">
                                        <span class="input-group-text bg-light">m²</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control"
                                            id="production_capacity" name="production_capacity"
                                            required min="1"
                                            placeholder="Production Capacity">
                                        <span class="input-group-text bg-light">units/hr</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="mb-4">
                            <label for="machine_price" class="form-label fw-medium">Machine Price</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" class="form-control"
                                    id="machine_price" name="machine_price"
                                    required min="0" step="0.01"
                                    placeholder="Enter price">
                            </div>
                        </div>

                        <!-- Item Selection -->
                        <div class="mb-4">
                            <label for="item_to_produce" class="form-label fw-medium">Item to Produce</label>
                            <select class="form-select form-select-lg" name="item_to_produce" id="item_to_produce" required>
                                <option value="" selected disabled>Select item to produce</option>
                                @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Register Machine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table id="machinesTable" class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Machine</th>
                                <th class="px-4 py-3">Specifications</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Production Item</th>
                                <th class="px-4 py-3">Action</th>
                                <!-- <th class="px-4 py-3 text-end">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($machines as $machine)
                            <tr>
                                <td class="px-4">
                                    <div class="fw-medium">{{ $machine->machine_name }}</div>
                                </td>
                                <td class="px-4">
                                    <div class="small text-muted mb-1">Size: {{ $machine->machine_size }} m²</div>
                                    <div class="small text-muted">Capacity: {{ $machine->production_capacity }} units/hr</div>
                                </td>
                                <td class="px-4">
                                    Rp {{ number_format($machine->machine_price) }}
                                </td>
                                <td class="px-4">
                                    <span class="badge bg-light text-dark">
                                        {{ $machine->item->item_name }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <button class="btn btn-action btn-delete delete-machine" data-id="{{ $machine->id }}}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                                <!-- <td class="px-4 text-end">
                                    <button class="btn btn-action btn-edit editMachine" data-id="{{ $machine->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-action btn-delete deleteMachine" data-id="{{ $machine->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        margin-left: 0.5rem;
        transition: all 0.2s;
    }

    .btn-edit {
        background-color: #e3f2fd;
        color: #1976d2;
        border: none;
    }

    .btn-edit:hover {
        background-color: #1976d2;
        color: white;
    }

    .btn-delete {
        background-color: #ffebee;
        color: #d32f2f;
        border: none;
    }

    .btn-delete:hover {
        background-color: #d32f2f;
        color: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.1);
    }

    .table>tbody>tr:hover {
        background-color: #f8f9fa;
    }
</style>

<!-- Scripts -->
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#machinesTable').DataTable({
            order: [
                [0, 'asc']
            ],
            pageLength: 10,
            responsive: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search machines..."
            }
        });

        // Form Submission
        $('#machineForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/blc-delivery/createMachine',
                method: 'POST',
                data: formData,
                processData: false, // Tambahkan ini
                contentType: false, // Tambahkan ini
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Machine Registered!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong! Please try again.'
                    });
                }
            });

        });

        // Edit Machine
        $(document).on('click', '.editMachine', function() {
            const machineId = $(this).data('id');
            // Add your edit logic here
            Swal.fire({
                icon: 'info',
                title: 'Edit Machine',
                text: 'Edit functionality to be implemented'
            });
        });

        // Delete Machine
        $(document).on('click', '.deleteMachine', function() {
            const machineId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add your delete logic here
                    $.ajax({
                        url: `/blc-delivery/deleteMachine/${machineId}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        });
    });

    $(document).on('click', '.delete-machine', function() {
        const machineId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/blc-delivery/deleteMachine/${machineId}`,
                    method: 'DELETE',
                    data: {
                        machineId: machineId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong! Please try again.'
                        });
                    }
                });
            }
        });
    });
</script>
@endsection