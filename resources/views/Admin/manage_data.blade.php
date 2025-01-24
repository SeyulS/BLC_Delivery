@extends('layout.admin_home')
@section('container')

<div class="container mt-5">
        <h1 class="text-center mb-4">CRUD Table Overview</h1>
        <div class="row g-4">
            <!-- Raw Items -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-box-open fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Raw Items</h5>
                        <p class="card-text">Manage all raw materials in the system.</p>
                        <a href="/raw-items" class="btn btn-primary">Go to Raw Items</a>
                    </div>
                </div>
            </div>
            <!-- Items -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-cubes fa-3x mb-3 text-success"></i>
                        <h5 class="card-title">Items</h5>
                        <p class="card-text">Manage finished items ready for use.</p>
                        <a href="/items" class="btn btn-success">Go to Items</a>
                    </div>
                </div>
            </div>
            <!-- Machine -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-industry fa-3x mb-3 text-warning"></i>
                        <h5 class="card-title">Machine</h5>
                        <p class="card-text">Manage machine data for operations.</p>
                        <a href="/machine" class="btn btn-warning">Go to Machine</a>
                    </div>
                </div>
            </div>
            <!-- Pengiriman -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-truck fa-3x mb-3 text-info"></i>
                        <h5 class="card-title">Pengiriman</h5>
                        <p class="card-text">Track and manage deliveries.</p>
                        <a href="/pengiriman" class="btn btn-info">Go to Pengiriman</a>
                    </div>
                </div>
            </div>
            <!-- Pinjaman -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-hand-holding-usd fa-3x mb-3 text-danger"></i>
                        <h5 class="card-title">Pinjaman</h5>
                        <p class="card-text">Manage loans and borrowing details.</p>
                        <a href="/pinjaman" class="btn btn-danger">Go to Pinjaman</a>
                    </div>
                </div>
            </div>
            <!-- Deck -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-layer-group fa-3x mb-3 text-dark"></i>
                        <h5 class="card-title">Deck</h5>
                        <p class="card-text">Manage deck-related data and configurations.</p>
                        <a href="/deck" class="btn btn-dark">Go to Deck</a>
                    </div>
                </div>
            </div>
            <!-- Demand -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x mb-3 text-secondary"></i>
                        <h5 class="card-title">Demand</h5>
                        <p class="card-text">Track and manage demand patterns.</p>
                        <a href="/demand" class="btn btn-secondary">Go to Demand</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
