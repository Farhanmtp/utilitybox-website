@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3 class="card-title">Categories</h3>
                </div>
                <div class="col-6 text-right">
                    @if(hasPermission('categories.create'))
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Category</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ $html->table() }}
        </div>
    </div>
@endsection
@section('style')
@endsection
@section('script')
    {{ $html->scripts() }}
@endsection

