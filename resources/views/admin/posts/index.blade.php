@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3 class="card-title">Posts</h3>
                </div>
                <div class="col-6 text-right">
                    @if(hasPermission('posts.create'))
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Post</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="filters" class="row mb-5">
                <div class="col-md-12 form-inline">
                    <div class="form-group filter-label">
                        Filters:
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="category">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-content="{{ $category->title }}"
                                >{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group ">
                        <select class="form-control" name="status">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">In-Active</option>
                        </select>
                    </div>
                </div>
            </div>
            {{ $html->table() }}
        </div>
    </div>
@endsection
@section('style')
@endsection
@section('script')
    {{ $html->scripts() }}
@endsection

