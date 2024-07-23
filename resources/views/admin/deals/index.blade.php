@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3 class="card-title">Settings</h3>
                </div>
                <div class="col-6 text-right">
                    @if(hasPermission('suppliers.create'))
                        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Supplier</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-0" id="filters">
                <div class="col-md-3 col-lg-2">
                    <div class="form-group text-sm ">
                        <label class="font-weight-normal m-0">Current Supplier</label>
                        <select name="supplier" class="form-control form-control-sm">
                            <option value="">All Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier}}">{{$supplier}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <div class="form-group text-sm">
                        <label class="font-weight-normal m-0">New Supplier</label>
                        <select name="new_supplier" class="form-control form-control-sm">
                            <option value="">All Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier}}">{{$supplier}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <div class="form-group text-sm">
                        <label class="font-weight-normal m-0">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="action-required">Action Required</option>
                            <option value="finalized">Finalized</option>
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

