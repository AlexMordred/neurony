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

    <h2>
        My Threads
        <a href="{{ route('threads.create') }}" class="btn btn-sm btn-success">New Thread</a>
    </h2>

    <v-profile-threads :data-threads="{{ $threads }}"></v-profile-threads>
</div>
@endsection