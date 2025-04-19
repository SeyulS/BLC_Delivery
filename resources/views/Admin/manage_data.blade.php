@extends('layout.admin_home')
@section('container')

<div class="container py-5">

    <div class="row g-4">
        <!-- Raw Items Card -->
        <div class="col-md-4">
            <a href="/raw-items" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-3 me-3">
                            <i class="fas fa-box-open fa-lg text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-dark mb-1">Raw Items</h5>
                            <p class="card-text text-muted mb-0 small">Raw materials</p>
                        </div>
                        <i class="fas fa-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Items Card -->
        <div class="col-md-4">
            <a href="/items" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-3 me-3">
                            <i class="fas fa-cubes fa-lg text-success"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-dark mb-1">Items</h5>
                            <p class="card-text text-muted mb-0 small">Finished products</p>
                        </div>
                        <i class="fas fa-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Machine Card -->
        <div class="col-md-4">
            <a href="/machine" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-3 me-3">
                            <i class="fas fa-industry fa-lg text-warning"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-dark mb-1">Machine</h5>
                            <p class="card-text text-muted mb-0 small">Equipment management</p>
                        </div>
                        <i class="fas fa-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.icon-wrapper {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hover-card {
    transition: transform 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
}

/* Ensure text remains dark for better contrast */
.text-dark {
    color: #212529 !important;
}

/* Enhanced shadow for better depth */
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
}
</style>

@endsection