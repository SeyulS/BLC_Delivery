@extends('layout.main_room')

@section('script')
<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('style')
<style>
    .form-control {
        width: 30%; /* Panjang input hanya 30% */
        text-align: center; /* Teks di dalam input rata tengah */
        margin: 0 auto; /* Pusatkan input */
    }

    .card img {
        max-width: 100%; /* Membuat gambar responsif */
        height: auto;
        margin-bottom: 10px; /* Tambahkan jarak bawah */
    }

    .select-container {
        margin-bottom: 20px; /* Jarak bawah untuk select */
    }

    .row .card {
        margin: 15px 0; /* Jarak antar kartu */
    }
</style>
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

    <h3 class="text-center">Pinjaman</h3>
    <br>

    <form action="/set_bahan_baku" method="POST" id="bahan-baku-form">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room_id }}">

        <!-- Pilih Tim -->
        <div class="select-container text-center">
            <select class="form-select form-select-lg" id="team-select" name="team" aria-label="Large select example">
                <option value="" selected disabled>Select Team</option>
                @foreach($players as $player)
                <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                @endforeach
            </select>
        </div>
        <br>

        <div class="row">

            @foreach($rawItems as $rawItem)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $rawItem }}</h5>
                            <img src="https://tse2.mm.bing.net/th?id=OIP.TJiE4HWiS0s6051Xa63_YAHaFq&pid=Api&P=0&h=220" alt="Kayu">
                            <input type="number" id="typeNumber" class="form-control" name="kayu" placeholder="Quantity" style="width: 35%; margin-top: 15px; margin: 0 auto;">
                        </div>
                    </div>
                </div>
            @endforeach
            
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-danger">Set Pembelian</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#team-select').select2({
            placeholder: "Select Team",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
