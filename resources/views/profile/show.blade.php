@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Profile</h1>

        <p>
            <b>Name: </b>{{ auth()->user()->name }}
        </p>

        <p>
            <b>E-mail: </b>{{ auth()->user()->email }}
        </p>
    </div>
@endsection