@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">Add Category</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.categories.index') }}"><i class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title') }}" placeholder="Enter title">
                        </div>
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug') }}" placeholder="Enter slug">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="status" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status')?'checked' :'' }} id="status">
                                <label class="form-check-label" for="status">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control"
                                      placeholder="Enter description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="{{ old('meta_title') }}" placeholder="Enter meta title">
                        </div>
                        <div class="form-group">
                            <label for="name">Meta Keyword</label>
                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control"
                                   value="{{ old('meta_keyword') }}" placeholder="Enter meta keyword">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="form-control"
                                      placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                        </div>
                                    </div>
                            </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar">Image</label>
                            <input type="text" name="image" id="image" class="form-control filemanager"
                                   value="{{ old('image') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="{{ route('admin.categories.index') }}">Cancel</a>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
@endsection

@section('script')

@endsection
