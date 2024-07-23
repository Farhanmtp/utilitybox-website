@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.suppliers.update',$supplier->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $supplier->name }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.suppliers.index') }}"><i class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $supplier->id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ data_get($supplier,'name') }}" placeholder="Enter name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="powwr_id">Supplier ID</label>
                            <input type="text" name="powwr_id" id="powwr_id" class="form-control"
                                   value="{{ data_get($supplier,'powwr_id') }}" placeholder="Enter supplier id">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Supplier Type</label>
                            <select class="form-control" name="supplier_type" id="supplier_type">
                                <option value="">Select Type</option>
                                <option value="E" {{ data_get($supplier,'supplier_type') == 'E'? 'selected' :'' }} > Electric</option>
                                <option value="G" {{ data_get($supplier,'supplier_type') == 'G'? 'selected' :'' }}> Gas</option>
                                <option value="B" {{ data_get($supplier,'supplier_type') == 'B'? 'selected' :'' }}> Both</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="status" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status',data_get($supplier,'status')) ? 'checked' :'' }} id="status">
                                <label class="form-check-label" for="status">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Supplier Logo</label>
                            <div class="custom-file">
                                <input type="file" name="logo" id="logo" class="custom-file-input">
                                <label class="custom-file-label" for="customFile">Choose Logo</label>
                            </div>
                            @if($supplier->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($supplier->logo))
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($supplier->logo) }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="{{ route('admin.suppliers.index') }}">Cancel</a>
                @if(hasPermission('suppliers.edit'))
                    <button type="submit" class="btn btn-success">Update</button>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/bs-file-input/bs-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init()
        })
    </script>
@endsection