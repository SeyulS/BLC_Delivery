@extends('layout.admin_home')

@section('script')
<!-- Bootstrap CSS (if not already loaded) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('container')


<div class="container mt-5">
    <h3 class="mb-4">Deck Name : {{ $deck_name }}</h3>
    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th scope="col">Demand ID</th>
                <th scope="col">Tujuan</th>
                <th scope="col">Need Day</th>
                <th scope="col">Item Index</th>
                <th scope="col">Quantity</th>
                <th scope="col">Revenue</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>

            @foreach($demands as $demand)
            <tr>
                <td>{{ $demand->demand_id }}</td>
                <td>{{ $demand->tujuan_pengiriman }}</td>
                <td>{{ $demand->need_day }}</td>
                <td>{{ $demand->item_index }}</td>
                <td>{{ $demand->quantity }}</td>
                <td>${{ $demand->revenue }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection