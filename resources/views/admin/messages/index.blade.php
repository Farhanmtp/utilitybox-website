@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3 class="card-title">Messages</h3>
                </div>
                <div class="col-6 text-right">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="filters" class="row mb-5">
                <div class="col-md-12 form-inline">
                    <div class="form-group filter-label">
                        Filters:
                    </div>
                    @if($types->count())
                        <div class="form-group ">
                            <select class="form-control" name="type">
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if($sub_types->count())
                        <div class="form-group ">
                            <select class="form-control" name="sub_type">
                                <option value="">Select Sub Type</option>
                                @foreach($sub_types as $stype)
                                    <option value="{{ $stype }}">{{ ucwords($stype) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
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

