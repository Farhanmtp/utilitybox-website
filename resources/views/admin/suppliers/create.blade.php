@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.suppliers.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $supplier->name?? 'Add Supplier' }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.suppliers.index') }}"><i class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name') }}" placeholder="Enter name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="powwr_id">Supplier ID</label>
                            <input type="text" name="powwr_id" id="powwr_id" class="form-control"
                                   value="{{ old('powwr_id') }}" placeholder="Enter supplier id">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Supplier Type</label>
                            <select class="form-control" name="supplier_type" id="supplier_type">
                                <option value="">Select Type</option>
                                <option value="E" {{ old('supplier_type') == 'E'? 'selected' :'' }} > Electric</option>
                                <option value="G" {{ old('supplier_type') == 'G'? 'selected' :'' }}> Gas</option>
                                <option value="B" {{ old('supplier_type') == 'B'? 'selected' :'' }}> Both</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="status" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status') ? 'checked' :'' }} id="status">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="{{ route('admin.suppliers.index') }}">Cancel</a>
                <button type="submit" class="btn btn-success">Update</button>
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
