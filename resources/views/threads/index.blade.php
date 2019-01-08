@extends('layouts.app')

@section('content')
<div class="container">
    <v-threads-page :all-users="{{ $users }}"></v-threads-page>
</div>
@endsection
