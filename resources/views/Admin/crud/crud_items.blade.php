@extends('layout.admin_home')

@section('container')

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

    .dynamic-row {
        position: relative;
        padding: 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        margin-bottom: 1rem;
        background: white;
    }

    .remove-material {
        position: absolute;
        right: -10px;
        top: -10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ff5252;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.1);
    }

    #dynamicFields {
        max-height: 300px;
        overflow-y: auto;
    }

    .bom-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .bom-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: #f0f9ff;
        border: 1px solid #e0f2fe;
        border-radius: 6px;
        font-size: 0.875rem;
        color: #0369a1;
    }

    .bom-quantity {
        font-weight: 600;
        background: #0ea5e9;
        color: white;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    .bom-empty {
        color: #94a3b8;
        font-style: italic;
        padding: 0.5rem 0;
    }
</style>
<div class="container py-5">
    <!-- Header Section -->
    <div class="mb-4">
        <h1 class="fw-bold text-dark mb-1">Item Management</h1>
        <p class="text-muted mb-0">Create and manage finished products with their bill of materials</p>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Create New Item</h5>
                    <form id="itemForm">
                        @csrf
                        <input type="hidden" id="itemId">

                        <!-- Basic Details Section -->
                        <div class="mb-4">
                            <label for="itemName" class="form-label fw-medium">Item Name</label>
                            <input type="text" class="form-control form-control-lg" id="itemName" name="item_name" required>
                        </div>

                        <!-- Bill of Materials Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-medium mb-0">Bill of Materials</label>
                                <button type="button" class="btn btn-primary btn-sm" id="addMoreFields">
                                    <i class="fas fa-plus"></i> Add Material
                                </button>
                            </div>
                            <div id="dynamicFields" class="border rounded-3 p-3 bg-light">
                                <div class="dynamic-row mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <select class="form-select rawItemSelect" name="raw_item_needed[]">
                                                <option value="">Select Raw Material</option>
                                                @foreach($rawItems as $rawItem)
                                                <option value="{{ $rawItem->id }}">{{ $rawItem->raw_item_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" name="raw_item_quantity_needed[]"
                                                placeholder="Qty" required min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dimensions Section -->
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-3">Item Dimensions</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="item_length"
                                            name="item_length" required min="0" step="0.01" placeholder="Length">
                                        <span class="input-group-text bg-light">m</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="item_width"
                                            name="item_width" required min="0" step="0.01" placeholder="Width">
                                        <span class="input-group-text bg-light">m</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="item_height"
                                            name="item_height" required min="0" step="0.01" placeholder="Height">
                                        <span class="input-group-text bg-light">m</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weight Section -->
                        <div class="mb-4">
                            <label for="itemWeight" class="form-label fw-medium">Item Weight</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="itemWeight"
                                    name="item_weight" required min="0" step="0.01">
                                <span class="input-group-text bg-light">kg</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="itemPrice" class="form-label fw-medium">Item Price</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>

                                <input type="number" class="form-control" id="itemPrice"
                                    name="item_price" required min="0" step="0.01">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table id="itemsTable" class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Bill Of Material</th>
                                <th class="px-4 py-3">Dimensions (L×W×H)</th>
                                <th class="px-4 py-3">Weight</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td class="px-4">{{ $item->item_name }}</td>
                                <td class="px-4">
                                    @if(!empty($item->bom))
                                    <div class="bom-list">
                                        @foreach($item->bom as $bomItem)
                                        <div class="bom-item">
                                            <span class="bom-quantity">{{ explode('x', $bomItem)[0] }}×</span>
                                            <span>{{ trim(explode('x', $bomItem)[1]) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="bom-empty">No materials required</div>
                                    @endif
                                </td>
                                <td class="px-4">
                                    {{ $item->item_length }}m × {{ $item->item_width }}m × {{ $item->item_height }}m
                                </td>
                                <td class="px-4">{{ $item->item_weight }} kg</td>
                                <td class="px-4">Rp {{ number_format($item->item_price) }}</td>
                                <td class="px-4">
                                    
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#itemsTable').DataTable({
            order: [
                [0, 'asc']
            ],
            pageLength: 10,
            responsive: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search items..."
            }
        });

        // Add Material Field
        $('#addMoreFields').on('click', function() {
            const newField = `
            <div class="dynamic-row">
                <div class="row g-3">
                    <div class="col-md-6">
                        <select class="form-select rawItemSelect" name="raw_item_needed[]" required>
                            <option value="">Select Raw Material</option>
                            @foreach($rawItems as $rawItem)
                                <option value="{{ $rawItem->id }}">{{ $rawItem->raw_item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="raw_item_quantity_needed[]" 
                               placeholder="Qty" required min="1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="remove-material">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
            $('#dynamicFields').append(newField);
        });

        // Remove Material Field
        $(document).on('click', '.remove-material', function() {
            $(this).closest('.dynamic-row').fadeOut(300, function() {
                $(this).remove();
            });
        });

        // Form Submission
        $('#itemForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/createItem',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
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
        });
    });
</script>
@endsection