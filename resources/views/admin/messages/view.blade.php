@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3 class="card-title">
                        {{ ucwords(str_replace(['_','-'],' ',$message->type)) }}
                        @if($message->sub_type)
                            > {{ ucwords(str_replace(['_','-'],' ',$message->sub_type)) }}
                        @endif
                    </h3>
                </div>
                <div class="col-6 text-right">
                    <a class="btn btn-primary" href="{{ route('admin.messages.index') }}"><i
                            class="fa fa-angle-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <input type="hidden" name="id" value="{{ $message->id }}">
            <table class="table">
                <tr>
                    <th style="width:12%">{{ $message->last_name ? 'First ' :'' }}Name</th>
                    <td>{{ data_get($message,'first_name') }}</td>
                </tr>
                @if($message->last_name)
                    <tr>
                        <th>Last Name</th>
                        <td>{{ $message->last_name }}</td>
                    </tr>
                @endif
                @if($message->business_name)
                    <tr>
                        <th>Business Name</th>
                        <td>{{ $message->business_name }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Email</th>
                    <td>{{ data_get($message,'email') }}</td>
                </tr>
                @if($message->phone)
                    <tr>
                        <th>Phone</th>
                        <td>{{ $message->phone }}</td>
                    </tr>
                @endif
                @if($message->address)
                    <tr>
                        <th>Address</th>
                        <td>{{ $message->address}}</td>
                    </tr>
                @endif
                @if($message->city)
                    <tr>
                        <th>City</th>
                        <td>{{ $message->city}}</td>
                    </tr>
                @endif
                @if($message->zipcode)
                    <tr>
                        <th>ZipCode</th>
                        <td>{{ $message->zipcode}}</td>
                    </tr>
                @endif
                @if($message->subject)
                    <tr>
                        <th>Subject</th>
                        <td>{{ $message->subject}}</td>
                    </tr>
                @endif
                @if($message->message)
                    <tr>
                        <th>Message</th>
                        <td>{{ $message->message}}</td>
                    </tr>
                @endif
                @if(!empty($message->attachment))
                    <tr>
                        <th>Attachments</th>
                        <td>
                            @foreach ($message->attachment as $file)
                                <a href="{{ $file }}" target="_blank">
                                    @if(\Illuminate\Support\Str::endsWith($file,'.pdf'))
                                        <i class="fa fa-file-pdf"></i>
                                    @else
                                        <i class="fa fa-file-image"></i>
                                    @endif
                                    {{ $file}}
                                </a>
                                @if(!$loop->last)
                                    <br>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="card-footer text-right">
            <a class="btn btn-danger" href="{{ route('admin.messages.index') }}">Back</a>
        </div>
    </div>
@endsection
