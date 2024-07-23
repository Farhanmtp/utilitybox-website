@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.categories.update',$category->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $category->title }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.categories.index') }}"><i class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $category->id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ data_get($category,'title') }}" placeholder="Enter title">
                        </div>
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ data_get($category,'slug') }}" placeholder="Enter slug">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="status" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ data_get($category,'status')?'checked' :'' }} id="status">
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
                                      placeholder="Enter description">{{ data_get($category,'description') }}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="{{ data_get($category,'meta_title') }}" placeholder="Enter meta title">
                        </div>
                        <div class="form-group">
                            <label for="name">Meta Keyword</label>
                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control"
                                   value="{{ data_get($category,'meta_keyword') }}" placeholder="Enter meta keyword">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="form-control"
                                      placeholder="Enter meta description">{{ data_get($category,'meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar">Image</label>
                            <input type="text" name="image" id="image" class="form-control filemanager"
                                   value="{{ data_get($category,'image') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="{{ route('admin.categories.index') }}">Cancel</a>
                @if(hasPermission('categories.edit'))
                    <button type="submit" class="btn btn-success">Update</button>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('script')

@endsection
