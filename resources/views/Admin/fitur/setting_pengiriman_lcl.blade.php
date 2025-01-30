@extends('layout.admin_room')

@section('container')
<div class="container">
    <div class="row">
        <!-- Column 1: Form Input Pinjaman -->
        <div class="col-md-4">
            <div class="p-4 shadow-sm h-100" style="background-color: white; border-radius: 8px;">
                <h4>Less Container Load</h4>
                <hr>
                <form id="pinjaman-form">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                    <!-- Select Player -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="team-select" class="form-label mt-3">Player</label>
                            <select class="form-select form-select-lg mb-3" id="team-select" aria-label="Large select example" name="player_username">
                                <option value="" selected disabled>Select Team</option>
                                @foreach($players as $player)
                                <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="demand-select" class="form-label mt-3">Demand</label>
                            <select class="form-select form-select-lg mb-3" id="demand-select" aria-label="Large select example">
                                <option value="" selected disabled>Select Demand</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mb-3">
                        <div class="col-md-12 mt-5">
                            <button type="submit" class="btn btn-primary w-100">Set Pengiriman</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Column 2: History Set Pinjaman -->
        <div class="col-md-8">
            <div class="p-4" style="background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <h4>History Pengiriman</h4>
                <hr>
                <table class="table table-bordered mt-3" id="pengirimanLCLHistory">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Demand ID</th>
                            <th>Biaya</th>
                            <th>Denda</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example Hardcoded Data -->
                        <tr>
                            <td>Player1</td>
                            <td>1</td>
                            <td>1000000</td>
                            <td>5</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>Player1</td>
                            <td>2</td>
                            <td>2000000</td>
                            <td>10</td>
                            <td>60</td>
                        </tr>
                        <tr>
                            <td>Player1</td>
                            <td>2</td>
                            <td>2000000</td>
                            <td>10</td>
                            <td>60</td>
                        </tr>
                        <tr>
                            <td>Player1</td>
                            <td>4</td>
                            <td>2000000</td>
                            <td>10</td>
                            <td>60</td>
                        </tr>
                        <tr>
                            <td>Player2</td>
                            <td>2</td>
                            <td>2000000</td>
                            <td>10</td>
                            <td>60</td>
                        </tr>
                        <tr>
                            <td>Player2</td>
                            <td>2</td>
                            <td>2000000</td>
                            <td>10</td>
                            <td>60</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="p-4 shadow-sm mt-4" style="background-color: white; border-radius: 8px;">
                <h6>Balikpapan</h6>
                <hr>
                <!-- Kapasitas Volume -->
                <div class="mb-3">
                    <label for="kapasitas-volume" class="form-label"><strong>Kapasitas Volume</strong></label>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 42.85%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="70"></div>
                    </div>
                    <small class="text-muted">30/70 m³</small> <!-- Current / Max -->
                </div>
                <!-- Kapasitas Berat -->
                <div class="mb-3">
                    <label for="kapasitas-berat" class="form-label"><strong>Kapasitas Berat</strong></label>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">50/100 kg</small> <!-- Current / Max -->
                </div>
                <!-- Harga -->
                <div class="mb-3">
                    <label for="harga" class="form-label"><strong>Harga</strong></label>
                    <p>Rp. 500.000 / m3</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 shadow-sm mt-4" style="background-color: white; border-radius: 8px;">
                <h6>Manado</h6>
                <hr>
                <!-- Kapasitas Volume -->
                <div class="mb-3">
                    <label for="kapasitas-volume" class="form-label"><strong>Kapasitas Volume</strong></label>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 42.85%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="70"></div>
                    </div>
                    <small class="text-muted">30/70 m³</small> <!-- Current / Max -->
                </div>
                <!-- Kapasitas Berat -->
                <div class="mb-3">
                    <label for="kapasitas-berat" class="form-label"><strong>Kapasitas Berat</strong></label>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">50/100 kg</small> <!-- Current / Max -->
                </div>
                <!-- Harga -->
                <div class="mb-3">
                    <label for="harga" class="form-label"><strong>Harga</strong></label>
                    <p>Rp. 500.000 / m3</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 shadow-sm mt-4" style="background-color: white; border-radius: 8px;">
                <h6>Madiun</h6>
                <hr>
                <!-- Kapasitas Volume -->
                <div class="mb-3">
                    <label for="kapasitas-volume" class="form-label"><strong>Kapasitas Volume</strong></label>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 42.85%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="70"></div>
                    </div>
                    <small class="text-muted">30/70 m³</small> <!-- Current / Max -->
                </div>
                <!-- Kapasitas Berat -->
                <div class="mb-3">
                    <label for="kapasitas-berat" class="form-label"><strong>Kapasitas Berat</strong></label>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">50/100 kg</small> <!-- Current / Max -->
                </div>
                <!-- Harga -->
                <div class="mb-3">
                    <label for="harga" class="form-label"><strong>Harga</strong></label>
                    <p>Rp. 500.000 / m3</p>
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
                        console.log(response);
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
    });
</script>


@endsection