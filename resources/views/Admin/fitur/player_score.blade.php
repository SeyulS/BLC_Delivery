@extends('layout.admin_room')

@section('container')
<style>
    :root {
        --primary-color: #2563eb;
        --gold-color: #ffd700;
        --silver-color: #C0C0C0;
        --bronze-color: #CD7F32;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .table {
        margin-bottom: 0;
    }

    .table > :not(caption)>*>* {
        padding: 1rem 1.5rem;
        vertical-align: middle;
    }

    .table>tbody>tr {
        transition: all 0.3s ease;
    }

    .table>tbody>tr:hover {
        background-color: #f8fafc;
        transform: translateY(-2px);
    }

    /* Rank Badge Styles */
    .rank-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
        text-align: center;
        min-width: 120px;
    }

    .rank-badge.gold {
        background: linear-gradient(135deg, var(--gold-color) 0%, #ffac33 100%);
        color: #000;
        box-shadow: 0 2px 4px rgba(255, 215, 0, 0.3);
    }

    .rank-badge.silver {
        background: linear-gradient(135deg, var(--silver-color) 0%, #A9A9A9 100%);
        color: #000;
        box-shadow: 0 2px 4px rgba(192, 192, 192, 0.3);
    }

    .rank-badge.bronze {
        background: linear-gradient(135deg, var(--bronze-color) 0%, #B87333 100%);
        color: #fff;
        box-shadow: 0 2px 4px rgba(205, 127, 50, 0.3);
    }

    .rank-badge.normal {
        background: #f1f5f9;
        color: #64748b;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Score Column */
    td:last-child {
        font-weight: 600;
        color: #1e293b;
    }

    /* Header Styles */
    .lobby-header h2 {
        color: #1e293b;
        font-size: 1.5rem;
    }

    .card-body {
        padding: 0;
    }

    .p-4 h5 {
        color: #1e293b;
        font-size: 1.1rem;
    }

    /* Table Header */
    thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="container py-4">
    <!-- Header Section -->
    <div class="lobby-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Room #{{ $room->room_id }}</h2>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Players List Section -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-users me-2 text-primary"></i>Players in Room
                            </h5>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Player</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result as $player)
                                <tr>
                                    <td>
                                        <span class="rank-badge {{ str_contains($player->rank, '1st') ? 'gold' : 
                    (str_contains($player->rank, '2nd') ? 'silver' : 
                    (str_contains($player->rank, '3rd') ? 'bronze' : 'normal')) }}">
                                            {{ $player->rank }}
                                        </span>
                                    </td>
                                    <td>{{ $player->player_username }}</td>
                                    <td>{{ number_format($player->score) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection