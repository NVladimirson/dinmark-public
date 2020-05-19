@php
	$sidebarClass = (!empty($sidebarTransparent)) ? 'sidebar-transparent' : '';
@endphp
<!-- begin #sidebar -->
<div id="sidebar" class="sidebar {{ $sidebarClass }}">
	<!-- begin sidebar scrollbar -->
	<div data-scrollbar="true" data-height="100%">
		@if (!$sidebarSearch)
		<!-- begin sidebar user -->
		<ul class="nav">
			<li class="nav-profile">
				<a href="javascript:;" data-toggle="nav-profile">
					<div class="cover with-shadow"></div>
					<div class="image">
						<img src="{{$logo}}" alt="{{session('current_company_name')}}" />
					</div>
					<div class="info">
						@if(isset($companies))
						<b class="caret pull-right"></b>
						@endif
						{{auth()->user()->getCompany? session('current_company_name') : auth()->user()->role->title}}
					</div>
				</a>
			</li>
			@if(isset($companies))
			<li>
				<ul class="nav nav-profile">
					@foreach($companies as $company)
					<li><a href="{{route('user.change_company',[$company->id])}}">{{$company->name}}</a></li>
					@endforeach
					<div class="dropdown-divider"></div>
					<li><a href="{{route('company')}}"><i class="fa fa-cog"></i> @lang('sidebar.edit_company')</a></li>
				</ul>
			</li>
			@endif
		</ul>
		<!-- end sidebar user -->
		@endif
		<!-- begin sidebar nav -->
		<ul class="nav">
			@if ($sidebarSearch)
			<li class="nav-search">
        <input type="text" class="form-control" placeholder="Sidebar menu filter..." data-sidebar-search="true" />
			</li>
			@endif
			<li class="nav-header">@lang('sidebar.sidebar_title')</li>
			@php
				$currentUrl = (Request::path() != '/') ? '/'. Request::path() : '/';
				
				function renderSubMenu($value, $currentUrl) {
					$subMenu = '';
					$GLOBALS['sub_level'] += 1 ;
					$GLOBALS['active'][$GLOBALS['sub_level']] = '';
					$currentLevel = $GLOBALS['sub_level'];
					foreach ($value as $key => $menu) {
						$GLOBALS['subparent_level'] = '';
						
						$subSubMenu = '';
						$hasSub = (!empty($menu['sub_menu'])) ? 'has-sub' : '';
						$hasCaret = (!empty($menu['sub_menu'])) ? '<b class="caret pull-right"></b>' : '';
						$hasTitle = (!empty($menu['title'])) ? trans($menu['title']) : '';
						$hasHighlight = (!empty($menu['highlight'])) ? '<i class="fa fa-paper-plane text-theme m-l-5"></i>' : '';
						
						if (!empty($menu['sub_menu'])) {
							$subSubMenu .= '<ul class="sub-menu">';
							$subSubMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
							$subSubMenu .= '</ul>';
						}
						
						$active = ($currentUrl == $menu['url']) ? 'active' : '';
						
						if ($active) {
							$GLOBALS['parent_active'] = true;
							$GLOBALS['active'][$GLOBALS['sub_level'] - 1] = true;
						}
						if (!empty($GLOBALS['active'][$currentLevel])) {
							$active = 'active';
						}
						$subMenu .= '
							<li class="'. $hasSub .' '. $active .'">
								<a href="'. (($menu['url'] ==  'javascript:;')?'javascript:;':route($menu['url'])) .'">'. $hasCaret . $hasTitle . $hasHighlight .'</a>
								'. $subSubMenu .'
							</li>
						';
					}
					return $subMenu;
				}
				
				foreach (config('sidebar.menu') as $key => $menu) {
					$GLOBALS['parent_active'] = '';
					
					$hasSub = (!empty($menu['sub_menu'])) ? 'has-sub' : '';
					$hasCaret = (!empty($menu['caret'])) ? '<b class="caret"></b>' : '';
					$hasIcon = (!empty($menu['icon'])) ? '<i class="'. $menu['icon'] .'"></i>' : '';
					$hasImg = (!empty($menu['img'])) ? '<div class="icon-img"><img src="'. $menu['img'] .'" /></div>' : '';
					$hasLabel = (!empty($menu['label'])) ? '<span class="label label-theme m-l-5">'. $menu['label'] .'</span>' : '';
					$hasTitle = (!empty($menu['title'])) ? '<span>'. trans($menu['title']) . $hasLabel .'</span>' : '';
					$hasBadge = (!empty($menu['badge'])) ? '<span class="badge pull-right">'. $menu['badge'] .'</span>' : '';
					
					$subMenu = '';
					
					if (!empty($menu['sub_menu'])) {
						$GLOBALS['sub_level'] = 0;
						$subMenu .= '<ul class="sub-menu">';
						$subMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
						$subMenu .= '</ul>';
					}
					$active = ($currentUrl == $menu['url']) ? 'active' : '';
					$active = (empty($active) && !empty($GLOBALS['parent_active'])) ? 'active' : $active;
					echo '
						<li class="'. $hasSub .' '. $active .'">
							<a href="'. (($menu['url'] ==  'javascript:;')?'javascript:;':route($menu['url'])) .'">
								'. $hasImg .'
								'. $hasBadge .'
								'. $hasCaret .'
								'. $hasIcon .'
								'. $hasTitle .'
							</a>
							'. $subMenu .'
						</li>
					';
				}
			@endphp
			<!-- begin sidebar minify button -->
			<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			<!-- end sidebar minify button -->
		</ul>
		<!-- end sidebar nav -->
			@if(auth()->user()->getCompany)
				@php
					$manager = auth()->user()->getCompany->getManager;
				@endphp
			<ul class="nav">
				<li class="media media-xs nav-profile">
					<div class="media-left">
						@if($manager->photo)
							<img class="media-object rounded-corner" src="{{env('DINMARK_URL')}}images/profile/{{$manager->photo}}" alt="{{$manager->name}}" />
						@else
							<img class="media-object rounded-corner" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$manager->name}}" />
						@endif
					</div>
					<div class="media-body">
						<h5 class="media-heading">@lang('sidebar.manager')</h5>
						<p>{{$manager->name}}</p>
						<p class="mb-0">
							<a href="{{route('ticket')}}" class="btn btn-block btn-primary">@lang('sidebar.message') <span class="badge badge-light">{{$countMessage}}</span></a>
						</p>
					</div>
				</li>
				<li class="media media-xs nav-manager">
					<a href="{{route('ticket')}}">
					@if($manager->photo)
						<img class="media-object rounded-corner" src="{{env('DINMARK_URL')}}images/profile/{{$manager->photo}}" alt="{{$manager->name}}" />
					@else
						<img class="media-object rounded-corner" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$manager->name}}" />
					@endif
					</a>
				</li>
			</ul>
			@endif
	</div>
	<!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->

