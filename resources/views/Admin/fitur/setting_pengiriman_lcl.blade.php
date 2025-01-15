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
    <h3>Pengiriman LCL</h3>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <select class="form-select form-select-lg mb-3" id="team-select" aria-label="Large select example">
                    <option value="" selected disabled>Select Team</option> 
                    @foreach($players as $player) 
                        <option value="{{ $player->player_username }}">{{ $player->player_username }}</option> 
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select form-select-lg mb-3" id="demand-select" aria-label="Large select example">
                    <option value="" selected disabled>Select Demand</option> 
                </select>
            </div>
        </div>
        <br>
        <button class="btn btn-danger">Deliver</button>
    </div>

</div>
<script>
    $(document).ready(function() {
        $('#team-select').select2({
            placeholder: "Select Team",
            allowClear: true,
            width: '100%',
            height: '100%'
        });
    });

    $(document).ready(function() {
        $('#demand-select').select2({
            placeholder: "Select Demand",
            allowClear: true,
            width: '100%',
            height: '100%'
        });
    });
    

    $('#team-select').on('change', function() {
        const username = $(this).val();
        if (username) {
            $.ajax({
                url: `/getDemand/${username}`,
                method: 'GET',
                success: function(demands) {
                    $('#demand-select').empty();
                    if (demands.length > 0) {
                        demands.forEach(demand => {
                            $('#demand-select').append(
                                `<option value="${demand.demand_id}">${demand.demand_id} - ${demand.tujuan_pengiriman}</option>`
                                );
                        });
                    } else {
                        $('#demand-select').append(`<option value="" disabled>No demands available</option>`);
                    }
                },
                error: function(){
                    $('#demand-select').empty(); 
                    $('#demand-select').append(`<option value="" disabled>Failed to retrieve demands</option>`);
                }
            })
        }

        else{
            $('#demands-select').empty();
            $('#demands-select').append(`<option value="" select disabled>Select Demand</option>`);

        }
    })
</script>
@endsection