<div class="widget-list widget-list-rounded">
    <div class="widget-list-item widget-list-item_transparent">
        <div class="widget-list-media">
            @if($user->photo)
                <img class="rounded-corner" src="{{env('DINMARK_URL')}}images/profile/{{$user->photo}}" alt="{{$user->name}}" />
            @else
                <img class="rounded-corner" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$user->name}}" />
            @endif
        </div>
        <div class="widget-list-content">
            <h4 class="widget-list-title">{{$user->name}}</h4>
        </div>
    </div>
</div>
