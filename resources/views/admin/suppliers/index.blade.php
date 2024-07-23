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

                        <a class="btn btn-primary" href="{{ route('admin.suppliers.sync') }}"><i
                                class="fa fa-sync"></i> Sync</a>
                        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Supplier</a>
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

