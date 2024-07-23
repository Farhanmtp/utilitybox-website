{{-- Logo --}}
<a href="{{ url('/') }}" style="color: #000000;text-decoration: none;" target="_blank">
    <?php
    $email_logo = settings('app.logo');
    $logo = $email_logo ? url($email_logo) : url('images/logo.png');
    $logo_title = settings('app.name', config('app.name'));
    ?>

    @if($logo)
        <img class="image" src="{{$logo}}" data-auto-embed alt="{{ $logo_title}}" title="{{$logo_title}}"
             style="max-width: 200px;max-height: 60px;margin:25px 0 10px 0;"/>
    @else
        <span style="font-size: 24px;
            padding: 12px;
            display: block;
            line-height: 1.2em;
            ">
            {{ $logo_title }}
        </span>
    @endif
</a>
