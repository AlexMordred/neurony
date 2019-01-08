@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $thread->title }}</h1>

    <div>
        {{ $thread->content }}
    </div>

    <h2 class="mt-4">Replies</h2>

    <v-thread-replies :thread-id="{{ $thread->id }}"></v-thread-replies>
</div>
@endsection
