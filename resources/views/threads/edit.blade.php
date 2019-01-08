@extends('layouts.app')

@section('content')
<div class="container">
    <div class="justify-content-center">
        <h1>New Thread</h1>

        <form action="{{ route('threads.store') }}" method="POST">
            @csrf

            <div class="form-group row">
                <label for="title" class="col-md-2 col-form-label text-md-right">Title</label>

                <div class="col-md-8">
                    <input id="title"
                        type="text"
                        class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                        name="title"
                        value="{{ old('title') }}">

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="content" class="col-md-2 col-form-label text-md-right">Content</label>

                <div class="col-md-8">
                    <textarea id="content"
                        type="text"
                        class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                        name="content">{{ old('content') }}</textarea>

                    @if ($errors->has('content'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
