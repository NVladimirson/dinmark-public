

<a href="{!! $notification->data['link']?$notification->data['link']:'javascript:;' !!}" class="dropdown-item media">
    <div class="media-left">
        @if(array_key_exists('user', $notification->data))
            @if($notification->data['user']['photo'])
                <img class="media-object" src="{{env('DINMARK_URL')}}images/profile/{{$notification->data['user']['photo']}}" alt="{{$notification->data['user']['name']}}" />
            @else
                <img class="media-object" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$notification->data['user']['name']}}" />
            @endif
        @endif

        @if(array_key_exists('icon',$notification->data))
        <i class="{{$notification->data['icon']}} @if(array_key_exists('user', $notification->data)) media-object-icon @else media-object bg-silver-darker @endif"></i>
        @endif
    </div>
    <div class="media-body">
        <h6 class="media-heading">
            @if(array_key_exists($notification->data['name'], trans('notification')))
                {{ trans('notification.'.$notification->data['name']) }}
            @else
                {{$notification->data['name']}}
            @endif
        </h6>
        @if(array_key_exists('text', $notification->data))
            <p>{{$notification->data['text']}}</p>
        @endif
        <div class="text-muted f-s-10">{{ \Carbon\Carbon::parse($notification->created_at)->format('H:i d.m.Y') }}</div>
    </div>
</a>