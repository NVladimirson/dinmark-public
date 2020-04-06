@php
	$headerClass = (!empty($headerInverse)) ? 'navbar-inverse ' : 'navbar-default ';
	$headerMenu = (!empty($headerMenu)) ? $headerMenu : '';
	$headerMegaMenu = (!empty($headerMegaMenu)) ? $headerMegaMenu : ''; 
	$headerTopMenu = (!empty($headerTopMenu)) ? $headerTopMenu : '';
@endphp
<!-- begin #header -->
<div id="header" class="header navbar-inverse ">
	<!-- begin navbar-header -->
	<div class="navbar-header">
		@if ($sidebarTwo)
		<button type="button" class="navbar-toggle pull-left" data-click="right-sidebar-toggled">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		@endif
		<a href="{{route('home')}}" class="navbar-brand"><img src="{{asset('assets/img/logo/logo.png')}}" alt="Dinmark Logo"></a>
		@if ($headerMegaMenu)
			<button type="button" class="navbar-toggle pt-0 pb-0 mr-0" data-toggle="collapse" data-target="#top-navbar">
				<span class="fa-stack fa-lg text-inverse">
					<i class="far fa-square fa-stack-2x"></i>
					<i class="fa fa-cog fa-stack-1x"></i>
				</span>
			</button>
		@endif
		@if (!$sidebarHide && $topMenu)
			<button type="button" class="navbar-toggle pt-0 pb-0 mr-0 collapsed" data-click="top-menu-toggled">
				<span class="fa-stack fa-lg text-inverse">
					<i class="far fa-square fa-stack-2x"></i>
					<i class="fa fa-cog fa-stack-1x"></i>
				</span>
			</button>
		@endif
		@if (!$sidebarHide && !$headerTopMenu)
		<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		@endif
		@if ($headerTopMenu)
			<button type="button" class="navbar-toggle" data-click="top-menu-toggled">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		@endif
	</div>
	<!-- end navbar-header -->
	
	@includeWhen($headerMegaMenu, 'includes.header-mega-menu')
	
	<!-- begin header-nav -->
	<ul class="navbar-nav navbar-right">
		<li class="navbar-form">
			<form action="" method="POST" name="search_form">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Enter keyword" />
					<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
				</div>
			</form>
		</li>
		<li class="dropdown">
			<a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14 " id="new_notifications">
				<i class="fa fa-bell"></i>
				@if(auth()->user()->unreadNotifications->count() > 0)
					<input type="hidden" id="last_notification" value="{{auth()->user()->unreadNotifications->first()->created_at}}">
					<span class="label">{{auth()->user()->unreadNotifications->count()}}</span>
				@endif
			</a>
			<div class="dropdown-menu media-list dropdown-menu-right">
				<div class="dropdown-header">@lang('notification.tab_name') ({{auth()->user()->unreadNotifications->count()}})</div>
				@forelse(auth()->user()->unreadNotifications->take(5)->all() as $notification)
					@include('notification.item')
				@empty
					<div class="alert alert-light fade show text-center">@lang('notification.empty_new')</div>
				@endforelse
				<div class="dropdown-footer text-center">
					<a href="{{route('notification')}}">@lang('notification.more')</a>
				</div>
			</div>
		</li>
		<li class="dropdown navbar-language">
			<a href="#" class="dropdown-toggle pr-1 pl-1 pr-sm-3 pl-sm-3" data-toggle="dropdown">
				<span class="flag-icon flag-icon-{{ LaravelLocalization::getCurrentLocale() }}" title="{{ LaravelLocalization::getCurrentLocale() }}"></span>
				<span class="name d-none d-sm-inline">{{ mb_strtoupper(LaravelLocalization::getCurrentLocale()) }}</span> <b class="caret"></b>
			</a>
			<div class="dropdown-menu">
			@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
					<a rel="alternate"  hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="dropdown-item"><span class="flag-icon flag-icon-{{$localeCode}}" title="{{$localeCode}}"></span> {{ $properties['native'] }}</a>
			@endforeach
			</div>
		</li>
		<li class="dropdown navbar-user">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				@if(auth()->user()->photo)
					<img src="{{env('DINMARK_URL')}}images/profile/{{auth()->user()->photo}}" alt="{{auth()->user()->name}}" />
					@else
					<img src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{auth()->user()->name}}" />
				@endif

				<span class="d-none d-md-inline">{{auth()->user()->name}}</span> <b class="caret"></b>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a href="{{route('user.profile')}}" class="dropdown-item">@lang('user.edit_link')</a>
				<a href="{{route('user.log')}}" class="dropdown-item">@lang('user.log_link')</a>
				<div class="dropdown-divider"></div>
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button type="submit" class="dropdown-item" class="btn btn-link">@lang('user.logout')</button>
				</form>
			</div>
		</li>
		@if($sidebarTwo)
		<li class="divider d-none d-md-block"></li>
		<li class="d-none d-md-block">
			<a href="javascript:;" data-click="right-sidebar-toggled" class="f-s-14">
				<i class="fa fa-th"></i>
			</a>
		</li>
		@endif
	</ul>
	<!-- end header navigation right -->
</div>
<!-- end #header -->
