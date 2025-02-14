@extends('layout.admin_room')

@section('container')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #2ea44f;
        --warning-color: #f7b731;
        --danger-color: #dc3545;
        --dark-color: #1e2a35;
        --light-color: #f8f9fa;
        --border-color: #e2e8f0;
    }

    .dashboard-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: transform 0.2s ease;
        border: none;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        height: 45px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
    }

    .select2-container--default .select2-selection--multiple {
        height: auto;
        min-height: 45px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 45px;
        padding-left: 15px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px;
    }

    .btn-shipping {
        background: var(--secondary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-shipping:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(63, 55, 201, 0.3);
        color: white;
    }

    .shipping-history-table thead th {
        background: #f8f9fa;
        color: var(--dark-color);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .form-label {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .progress {
        height: 0.8rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .progress-bar {
        background-color: var(--primary-color);
    }

    .progress-bar.bg-danger {
        background-color: var(--danger-color) !important;
    }
</style>

<div class="dashboard-container">
    <div class="container">
        <div class="row">
            <!-- Air Delivery Form -->
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h4 class="mb-3">Air Delivery</h4>
                    <hr>
                    <form id="pengiriman-form">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->room_id }}">

                        <div class="mb-4">
                            <label for="team-select" class="form-label">Player</label>
                            <select class="form-select" id="team-select" name="player_username">
                                <option value="" selected disabled>Select Team</option>
                                @foreach($players as $player)
                                <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="demand-select" class="form-label">Demand</label>
                            <select class="form-select" id="demand-select" required>
                                <option value="" selected disabled>Select Demand</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-shipping w-100">
                                <i class="fas fa-plane me-2"></i>Set Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- History Table -->
            <div class="col-md-8">
                <div class="card p-4">
                    <h4 class="mb-3">History Pengiriman</h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-hover shipping-history-table" id="pengirimanLCLHistory">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Destination</th>
                                    <th>Player</th>
                                    <th>Demand ID</th>
                                    <th>Delivery Cost</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $h)
                                <tr>
                                    <td>{{ $h->day }}</td>
                                    <td>{{ $h->destination }}</td>
                                    <td>{{ $h->player_username }}</td>
                                    <td>{{ $h->demand_id }}</td>
                                    <td>{{ $h->delivery_cost }}</td>
                                    <td>{{ $h->revenue }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacity Cards -->
        <div class="row mt-4">
            <!-- Banjarmasin Card -->
            <div class="col-md-4">
                <div class="card p-4">
                    <h6>{{ $bjm->destination }}</h6>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label"><strong>Kapasitas Volume</strong></label>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($bjm->current_volume_capacity / $bjm->max_volume_capacity) * 100 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $bjm->current_volume_capacity }}/{{ $bjm->max_volume_capacity}}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Kapasitas Berat</strong></label>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ ($bjm->current_weight_capacity / $bjm->max_weight_capacity) * 100 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $bjm->current_weight_capacity }}/{{ $bjm->max_weight_capacity}}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Harga</strong></label>
                        <p class="mb-0">{{ $bjm->price }} / m3</p>
                    </div>
                </div>
            </div>

            <!-- Manado Card -->
            <div class="col-md-4">
                <div class="card p-4">
                    <h6>{{ $mnd->destination }}</h6>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label"><strong>Kapasitas Volume</strong></label>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($mnd->current_volume_capacity / $mnd->max_volume_capacity) * 100 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $mnd->current_volume_capacity }}/{{ $mnd->max_volume_capacity}}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Kapasitas Berat</strong></label>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ ($mnd->current_weight_capacity / $mnd->max_weight_capacity) * 100 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $mnd->current_weight_capacity }}/{{ $mnd->max_weight_capacity}}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Harga</strong></label>
                        <p class="mb-0">{{ $mnd->price }} / m3</p>
                    </div>
                </div>
            </div>

            <!-- Makassar Card -->
            <div class="col-md-4">
                <div class="card p-4">
                    <h6>{{ $mks->destination }}</h6>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label"><strong>Kapasitas Volume</strong></label>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($mks->current_volume_capacity / $mks->max_volume_capacity) * 100 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $mks->current_volume_capacity }}/{{ $mks->max_volume_capacity}}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Kapasitas Berat</strong></label>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ ($mks->current_weight_capacity / $mks->max_weight_capacity) * 100 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $mks->current_weight_capacity }}/{{ $mks->max_weight_capacity}}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Harga</strong></label>
                        <p class="mb-0">{{ $mks->price }} / m3</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#pengirimanLCLHistory').DataTable({
            "pageLength": 3,
            "lengthChange": false
        });

        $('#team-select').select2({
            placeholder: "Select Team",
            allowClear: true,
            width: '100%',
            height: '100%'
        });

        $('#demand-select').select2({
            placeholder: "Select Demand",
            allowClear: true,
            width: '100%',
            height: '100%'
        });

        $('#team-select').on('change', function() {
            var playerUsername = $(this).val();
            if (playerUsername) {
                $.ajax({
                    url: '/getDemands',
                    method: 'POST',
                    data: {
                        player_username: playerUsername,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#demand-select').empty();
                        $('#demand-select').append('<option value="" selected disabled>Select Demand</option>');

                        $.each(response, function(index, demand) {
                            $('#demand-select').append('<option value="' + demand.demand_id + '">' + demand.demand_id + ' - ' + demand.tujuan_pengiriman + '</option>');
                        });

                        $('#demand-select').trigger('change');
                    },
                    error: function(xhr) {
                        toastr.error('Failed to fetch demands:', xhr.responseText);
                    }
                });
            } else {
                $('#demand-select').empty();
                $('#demand-select').append('<option value="" selected disabled>Select Demand</option>');
            }
        });
        $('#pengiriman-form').on('submit', function(e) {
            e.preventDefault();

            var playerUsername = $('#team-select').val();
            var demandId = $('#demand-select').val();
            var demandText = $('#demand-select option:selected').text(); // Get the selected demand text

            if (!playerUsername || !demandId) {
                toastr.error('Please select both Team and Demand.');
                return;
            }

            // SweetAlert confirmation with demand details
            Swal.fire({
                title: 'Are you sure?',
                html: `
            <strong>Player : </strong> ${playerUsername} <br>
            <strong>Demand : </strong> ${demandText} <br>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(playerUsername, demandId);

                    $.ajax({
                        url: '/kirimUdara',
                        method: 'POST',
                        data: {
                            player_username: playerUsername,
                            demand_id: demandId,
                            room_id: '{{ $room->room_id }}',
                            current_day: '{{ $room->recent_day }}',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == 'fail') {
                                toastr.error(response.message);
                            } else {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Delivery Success!',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log('Failed to send data:', xhr.responseText);
                        }
                    });
                } else {
                    // If canceled, just log that the submission was canceled
                    console.log('Submission canceled');
                }
            });
        });

    });
</script>


@endsection