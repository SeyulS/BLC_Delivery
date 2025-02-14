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
        background: var(--danger-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-shipping:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3);
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
</style>

<div class="dashboard-container">
    <div class="container">
        <div class="row">
            <!-- Column 1: Form Input Pengiriman -->
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h4 class="mb-3">Full Container Load</h4>
                    <hr>
                    <form id="pengiriman-form">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->room_id }}">

                        <!-- Select Player -->
                        <div class="mb-4">
                            <label for="player-select" class="form-label">Player</label>
                            <select class="form-select" id="player-select" name="player_username">
                                <option value="" selected disabled>Select Player</option>
                                @foreach($players as $player)
                                <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Destination -->
                        <div class="mb-4">
                            <label for="destination-select" class="form-label">Destination</label>
                            <select class="form-select" id="destination-select" name="destination">
                                <option value="" selected disabled>Select Destination</option>
                                @foreach($destination as $des)
                                <option value="{{ $des }}">{{ $des }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Demand (Multiple Select) -->
                        <div class="mb-4" id="demand-container" style="display: none;">
                            <label for="demand-select" class="form-label">Demand</label>
                            <select class="form-select" id="demand-select" name="demand[]" multiple>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-shipping w-100">
                                <i class="fas fa-truck me-2"></i>Set Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Column 2: History Pengiriman -->
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
                                        <td>{{ $h->list_of_demands }}</td>
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
    </div>
</div>

<script>
    $(document).ready(function() {
        const roomId = "{{ $room->room_id }}";
        var selectedValues = [];

        // Initialize DataTable with custom styling
        $('#pengirimanLCLHistory').DataTable({
            "pageLength": 3,
            "lengthChange": false,
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search Shipment"
            }
        });

        // Initialize Select2 with custom styling
        $('#player-select, #destination-select').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%',
            theme: 'classic'
        });

        $('#demand-select').select2({
            placeholder: "Select Demand",
            allowClear: true,
            width: '100%',
            closeOnSelect: false,
            theme: 'classic'
        });

        // Rest of your existing JavaScript code remains the same
        function fetchDemands() {
            var playerUsername = $('#player-select').val();
            var destination = $('#destination-select').val();

            if (playerUsername && destination) {
                $.ajax({
                    url: '/getDemandsFCL',
                    method: 'POST',
                    data: {
                        player_username: playerUsername,
                        destination: destination,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#demand-select').empty();
                        $.each(response, function(index, demand) {
                            $('#demand-select').append('<option value="' + demand.demand_id + '">' + demand.demand_id + ' - ' + demand.tujuan_pengiriman + '</option>');
                        });
                        $('#demand-container').show();
                        $('#demand-select').trigger('change');
                    },
                    error: function(xhr) {
                        toastr.error('Failed to fetch demands:', xhr.responseText);
                    }
                });
            } else {
                $('#demand-container').hide();
                $('#demand-select').empty();
            }
        }

        // Your existing event handlers remain the same
        $('#player-select, #destination-select').on('change', function() {
            fetchDemands();
            selectedValues = [];
            $('#demand-select').val(null).trigger('change');
        });

        $('#demand-select').on('select2:select', function(e) {
            selectedValues.push(e.params.data.id);
            $('#demand-select option').each(function() {
                if (selectedValues.includes($(this).val())) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
            $(this).trigger('change.select2');
        });

        $('#demand-select').on('select2:unselect', function(e) {
            var removedValue = e.params.data.id;
            selectedValues = selectedValues.filter(function(value) {
                return value !== removedValue;
            });
            $('#demand-select option[value="' + removedValue + '"]').prop('disabled', false);
            $(this).trigger('change.select2');
        });

        // Enhanced form submission with SweetAlert2
        $('#pengiriman-form').on('submit', function(e) {
            e.preventDefault();

            const selectedPlayer = $('#player-select').val();
            const selectedDestination = $('#destination-select').val();
            const selectedDemands = selectedValues;


            Swal.fire({
                title: 'Confirm Shipping Details',
                html: `
                    <div class="text-start">
                        <p><i class="fas fa-user me-2"></i><strong>Player:</strong> ${selectedPlayer}</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i><strong>Destination:</strong> ${selectedDestination}</p>
                        <p><i class="fas fa-box me-2"></i><strong>Selected Demands:</strong> ${selectedDemands.join(', ')}</p>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#718096'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/kirimFCL',
                        method: 'POST',
                        data: {
                            player_username: selectedPlayer,
                            destination: selectedDestination,
                            room_id: roomId,
                            demands: selectedDemands,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.status == 'success'){
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Pengiriman berhasil diset!',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(()=>{
                                    location.reload();
                                });
                            } else {
                                toastr.error(response.message);
                            }
                            
                        },
                        error: function(xhr) {
                            toastr.error('Failed to set pengiriman:', xhr.responseText);
                        }
                    });
                }
            });
        });
    });
</script>
@endsection