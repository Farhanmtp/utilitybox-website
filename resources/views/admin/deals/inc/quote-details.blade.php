<div class="row">
    @foreach($deal->quoteDetails as $key=>$val)
        {{--@if((!is_array($val) && strlen($val)) || (is_array($val) && !empty($val)))--}}
        @if(!in_array($key,['Preferred','BestDeal']))
            <div class="col-md-6 border-bottom">
                <strong class="control-label">{{ $key }}:</strong>
                @if(is_array($val))
                    <div style="margin-left: 5px;">
                        @foreach($val as $k=>$v)
                            <strong>{{$k}}</strong>: {{$v}} <br>
                        @endforeach
                    </div>
                @else
                    <div>{!! $val !!}</div>
                @endif
            </div>
        @endif
        {{--@endif--}}
    @endforeach
</div>
