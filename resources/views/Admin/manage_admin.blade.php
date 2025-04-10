@extends('layout.admin_home')

@section('container')
<style>
    .admin-dashboard {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 10px;
        font-size: 1.25rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .form-label {
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .btn-primary {
        background: #0284c7;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #0369a1;
        transform: translateY(-1px);
    }

    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .admin-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .admin-table td {
        padding: 1rem;
        color: #1e293b;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
    }

    .admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f1f5f9;
        border-radius: 20px;
        font-weight: 500;
    }

    .admin-badge i {
        color: #0284c7;
    }

    .btn-danger {
        background: #ef4444;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .toast-success {
        background-color: #10b981 !important;
        border-radius: 8px !important;
    }

    .toast-error {
        background-color: #ef4444 !important;
        border-radius: 8px !important;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px;
        padding: 0.25rem 2rem 0.25rem 0.5rem;
        border-color: #e2e8f0;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
        border-color: #e2e8f0;
    }

    <style>.super-admin-row {
        background: linear-gradient(to right, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.1));
        border-left: 4px solid #3b82f6;
    }

    .super-admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 20px;
        font-weight: 600;
        color: #2563eb;
    }

    .super-admin-badge i {
        color: #2563eb;
    }

    .badge-super-admin {
        background: #2563eb !important;
        color: white;
        font-weight: 500;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0;
    }

    .custom-checkbox input[type="checkbox"] {
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 4px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
    }

    .custom-checkbox input[type="checkbox"]:checked {
        background-color: #0284c7;
        border-color: #0284c7;
    }

    .custom-checkbox label {
        cursor: pointer;
        user-select: none;
        color: #475569;
        font-weight: 500;
    }

</style>
</style>

<div class="admin-dashboard">
    <div class="container">
        @if(session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
        @endif

        @if(session('error'))
        <script>
            toastr.error('{{ session('error') }}');
        </script>
        @endif

        <!-- Create Admin Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h4 class="card-title">Create New Admin</h4>
            </div>

            <form action="/registAdmin" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="admin_username" class="form-label">Username</label>
                            <input type="text" name="admin_username" id="admin_username"
                                class="form-control" placeholder="Enter admin username" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="confirmation_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirmation_password" id="confirmation_password"
                                class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-checkbox">
                            <input type="checkbox" name="super_admin" id="super_admin" value="1">
                            <label for="super_admin">Create as Super Admin</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create Admin
                    </button>
                </div>
            </form>
        </div>

        <!-- Admin List Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4 class="card-title">Admin List</h4>
            </div>

            <div class="table-responsive">
                <table id="AdminListTable" class="admin-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listOfAdmin as $admin)
                        <tr id="admin-row-{{ $admin->admin_username }}"
                            class="{{ $admin->super_admin == 1 ? 'super-admin-row' : '' }}">
                            <td>
                                <div class="{{ $admin->super_admin == 1 ? 'super-admin-badge' : 'admin-badge' }}">
                                    <i class="bi {{ $admin->super_admin == 1 ? 'bi-shield-check' : 'bi-person' }}"></i>
                                    {{ $admin->admin_username }}
                                </div>
                            </td>
                            <td>
                                @if($admin->super_admin == 1)
                                <span class="badge badge-super-admin">
                                    <i class="bi bi-shield me-1"></i>Super Admin
                                </span>
                                @else
                                <span class="badge bg-secondary">Admin</span>
                                @endif
                            </td>
                            <td>
                                @if($admin->super_admin == 0)
                                <button class="btn btn-danger btn-sm delete-admin"
                                    data-username="{{ $admin->admin_username }}">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                                @else
                                <span class="text-muted fst-italic small">Protected Account</span>
                                @endif
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
        const table = $('#AdminListTable').DataTable({
            pageLength: 10,
            dom: '<"table-header"lf>rt<"table-footer"ip>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search admins..."
            }
        });

        $('.delete-admin').click(function() {
            const username = $(this).data('username');
            const row = $(this).closest('tr');
            const isSuperAdmin = $('#super_admin').is(':checked') ? 1 : 0;


            Swal.fire({
                title: 'Delete Admin?',
                text: `Are you sure you want to delete ${username}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/deleteAdmin',
                        type: 'POST',
                        data: {
                            admin_username: username,
                            super: isSuperAdmin,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.row(row).remove().draw(false);
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('An error occurred. Please try again.');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection