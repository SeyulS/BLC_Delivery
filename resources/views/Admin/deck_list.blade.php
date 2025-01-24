@extends('layout.admin_home')

@section('container')


    @foreach($decks as $deck)
    <div class="col-md-3">
        <div class="card text-dark bg-light mb-3">
            <div class="card-header text-center">{{ $deck->deck_name }}</div>
            <div class="card-body text-center">
                <ul class="list-unstyled">Total Demands : {{ is_array(json_decode($deck->deck_list)) ? count(json_decode($deck->deck_list)) : 0 }} </ul>
                <div class="d-flex justify-content-center">
                    <a href="/manageDeck/{{ $deck->deck_id }}">
                        <button class = "btn btn-primary" type="button" class="btn btn-info btn-sm">Manage</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach


@endsection