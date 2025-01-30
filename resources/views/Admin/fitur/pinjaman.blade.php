@extends('layout.admin_room')

@section('script')
<!-- Load jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load Select2 setelah jQuery -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('container')
<div class="container mt-4">
    @if(session('success'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ session("success") }}');
        });
    </script>
    @endif

    @if(session('fail'))
    <script>
        $(document).ready(function() {
            toastr.error('{{ session("fail") }}');
        });
    </script>
    @endif
    <div class="container">
        <div class="row">
            <!-- Column 1: Form Input Pinjaman -->
            <div class="col-md-4">
                <div class="p-4 shadow-sm" style="background-color: white; border-radius: 8px;">
                    <h4>Form Pinjaman</h4>
                    <hr>
                    <form id="pinjaman-form">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                        <!-- Select Player -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="player-select" class="form-label">Player</label>
                                <select class="form-select form-select-lg" id="player-select" name="player" aria-label="Select Player">
                                    <option value="" selected disabled>Select Player</option>
                                    @foreach($players as $player)
                                    <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Nominal Pinjaman -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="loan-amount" class="form-label">Nominal Pinjaman</label>
                                <input type="number" class="form-control" id="loan-amount" name="loan_amount" min="1" required placeholder="Enter loan amount">
                            </div>
                        </div>

                        <!-- Bunga Pinjaman -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="loan-interest" class="form-label">Bunga Pinjaman (%)</label>
                                <input type="number" class="form-control" id="loan-interest" name="loan_interest" min="0" step="0.1" required placeholder="Enter loan interest rate">
                            </div>
                        </div>

                        <!-- Durasi Pinjaman -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="loan-duration" class="form-label">Durasi Pinjaman (Hari)</label>
                                <input type="number" class="form-control" id="loan-duration" name="loan_duration" min="1" required placeholder="Enter loan duration in days">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-danger w-100">Set Pinjaman</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-4 shadow-sm mt-4" style="background-color: white; border-radius: 8px;">
                <h4>Rule Base Pinjaman</h4>
                    <hr>

                </div>

            </div>

            <!-- Column 2: History Set Pinjaman -->
            <div class="col-md-8">
                <div class="p-4" style="background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                    <h4>History Pinjaman</h4>
                    <hr>
                    <table class="table table-bordered mt-3" id="pinjamanHistory">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Day</th>
                                <th>Nominal Pinjaman</th>
                                <th>Bunga Pinjaman (%)</th>
                                <th>Durasi Pinjaman (Hari)</th>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        const table = $('#pinjamanHistory').DataTable();

        // Inisialisasi Select2
        $('#player-select').select2({
            placeholder: "Select Player",
            allowClear: true,
            width: '100%'
        });

        // Validasi dan pengiriman data dengan Swal konfirmasi
        $('#pinjaman-form').on('submit', function(event) {
            event.preventDefault(); // Mencegah reload halaman

            // Ambil data input dari form
            const selectedPlayer = $('#player-select').val();
            const loanAmount = $('#loan-amount').val();
            const loanInterest = $('#loan-interest').val();
            const loanDuration = $('#loan-duration').val();

            // Validasi data input
            if (!selectedPlayer || !loanAmount || !loanInterest || !loanDuration) {
                toastr.error('Harap isi semua kolom.');
                return;
            }

            // Tampilkan swal konfirmasi
            Swal.fire({
                title: 'Konfirmasi Ke Player',
                html: `
                <div class="row mb-3">
    <div class="col-md-12 d-flex justify-content-center">
        <div class="d-flex w-50">
            <div class="fw-bold text-start" style="width: 40%;">
                Pinjaman
            </div>
            <div class="text-start" style="width: 60%;">
                <span class="text-danger"> : Rp. ${loanAmount}</span>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12 d-flex justify-content-center">
        <div class="d-flex w-50">
            <div class="fw-bold text-start" style="width: 40%;">
                Bunga
            </div>
            <div class="text-start" style="width: 60%;">
                <span class="text-warning"> : ${loanInterest}%</span>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12 d-flex justify-content-center">
        <div class="d-flex w-50">
            <div class="fw-bold text-start" style="width: 40%;">
                Durasi
            </div>
            <div class="text-start" style="width: 60%;">
                <span class="text-info"> : ${loanDuration} Hari</span>
            </div>
        </div>
    </div>
</div>

`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Set Pinjaman!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan aksi setelah konfirmasi
                    $.ajax({
                        url: '/setPinjaman',
                        method: 'POST',
                        data: {
                            player_username: selectedPlayer,
                            loanAmount: loanAmount,
                            loanInterest: loanInterest,
                            loanDuration: loanDuration,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastr.success(response.message);
                                table.row.add([
                                    response.player_username,
                                    response.day,
                                    response.loanAmount,
                                    response.loanInterest + '%',
                                    response.loanDuration + ' Hari'
                                ]).draw(false);

                                $('#pinjaman-form')[0].reset();
                                $('#player-select').val(null).trigger('change');
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Gagal menambahkan pinjaman. Silakan coba lagi.');
                        }
                    });
                }
            });

        });
    });
</script>

@endsection