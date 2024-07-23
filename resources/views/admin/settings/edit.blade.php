@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $setting->name }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.settings.index') }}">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="key" value="{{ $setting->key }}">

                @includeIf('admin.settings.forms.'.$setting->key)
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="{{ route('admin.settings.index') }}">Cancel</a>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
    {{--@dump(settings())--}}
@endsection
