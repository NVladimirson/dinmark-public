@php
	$headerClass = (!empty($headerInverse)) ? 'navbar-inverse ' : 'navbar-default ';
	$headerMenu = (!empty($headerMenu)) ? $headerMenu : '';
	$headerMegaMenu = (!empty($headerMegaMenu)) ? $headerMegaMenu : '';
	$headerTopMenu = (!empty($headerTopMenu)) ? $headerTopMenu : '';
@endphp
<!-- begin #header -->
<div @click="selectRender"  id="wrap">
<div id="header" class="header navbar-inverse ">
	<!-- begin navbar-header -->
	<div class="navbar-header">
		@if ($sidebarTwo)
		<button type="button" class="navbar-toggle pull-left" data-click="right-sidebar-toggled data-click="sidebar-minify"">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		@endif
		<a href="{{route('home')}}" class="navbar-brand"><img src="{{asset('assets/img/logo/logo.png')}}" alt="Dinmark Logo" class="dinmark-logo"></a>
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
	<div class="navbar-nav navbar-right flex-wrap navbar-form">

		<!-- <li class="navbar-form"> -->
        <!-- new search -->
        <!-- <form action="{{route('products.find')}}" method="GET" name="search_form" class="hexa">
            @csrf
                <input type="text" name="search" placeholder="@lang('global.header_search')" min="3"
                required value="{{request()->has('search')?request()->input('search'):''}}">
            <select class="form-control m-b-5" id="global_search" name="product_id">
            </select>

                <div class="more hexa-plus">
                    <i class="fas fa-plus"></i>
                </div>
        </form> -->

        <!-- <div class="more hexa-plus">
        <i class="fas fa-plus"></i>
        </div> -->

        <div id="global_search_wrap">
            <!-- <select class="form-control m-b-5" id="global_search" name="product_id"></select> -->


            <input id="globalInput" placeholder="@lang('global.header_search')" class="form-control m-b-5" @input="getResults" v-model="globalSearch" type="text">
            <i v-show="globalSearch.length" @click="reset" id="chross">&#215;</i>
            <div class="wrap-global-result" v-if="globalSearch.length > 2">
                <div v-if="showGlobalSearch" id="wrap-form-select" class="form-select">
										<div v-show="globalResult.products.length" class="group">
												<h4 class="pt-2 select-head">@lang('global.global_search.products')</h4>
												<div class="select-item" @click="openItem('products', item.id)" v-for="item of globalResult.products" v-html="item.text"></div>
												<div class="text-center select-footer mt-1"><span  @click="viewSearchResult('products')">@lang('global.global_search.all_results')</span></div>
										</div>
                    <div v-show="globalResult.orders.length" class="group">
                        <h4 class="pt-2 select-head">@lang('global.global_search.orders')</h4>
                        <div class="select-item" @click="openItem('orders', item.id)" v-for="item of globalResult.orders" v-html="item.text"></div>
                        <div class="text-center select-footer mt-1"><span  @click="viewSearchResult('orders')">@lang('global.global_search.all_results')</span></div>
                    </div>
										<div v-show="globalResult.implementations.length" class="group">
												<h4 class="pt-2 select-head">@lang('global.global_search.implementations')</h4>
												<div class="select-item" @click="openItem('implementations', item.id, item.product_id)" v-for="item of globalResult.implementations" v-html="item.text"></div>
												<div class="text-center select-footer mt-1"><span  @click="viewSearchResult('implementations')">@lang('global.global_search.all_results')</span></div>
										</div>
                    <div v-show="globalResult.reclamations.length" class="group">
                        <h4 class="pt-2 select-head">@lang('global.global_search.reclamations')</h4>
                        <div class="select-item" @click="openItem('reclamations', item.id, item.product_id)" v-for="item of globalResult.reclamations" v-html="item.text"></div>
                        <div class="text-center select-footer mt-1"><span  @click="viewSearchResult('reclamations')">@lang('global.global_search.all_results')</span></div>
                    </div>
                </div>

                <div v-else-if="!showGlobalSearch && !failedSearch" class="form-select">
                    <div class="select-item">??????????...</div>
                </div>

            </div>


            {{-- <div class="more hexa-plus">
                <i class="fas fa-plus"></i>
            </div> --}}
        </div>

        </div>
        <!-- </li> -->
        <!-- new search -->

        <!-- <li> -->
            <div id="debt" class="">
            <span class="debt-text">{{__('global.header_debt',['debt'=>number_format($debt,2,',',' ')])}}</span>
                <a href="{{route('balance')}}" class="debt-btn hideTab" >@lang('global.header_debt_btn')</a>
            </div>
        <!-- </li> -->

	</ul>
    <div class="right-actions-block--wrapper ">
        <div class="right-actions-block">
            {{-- <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14 " id="new_notifications">
                    <i id="notificationbell" class="fa fa-bell"></i>
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
            </li> --}}
            <li class="dropdown navbar-language">
                <a href="#" class="dropdown-toggle pr-2 pl-2 pr-sm-4 pl-sm-4" data-toggle="dropdown">
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
                        <img src="https://dinmark.com.ua/images/profile/{{auth()->user()->photo}}"
                        alt="{{auth()->user()->name}}" />
                        @else
                        <img src="https://dinmark.com.ua/images/empty-avatar.png" alt="{{auth()->user()->name}}" />
                    @endif

                    <span class="d-none d-md-inline">{{auth()->user()->name}}</span> <b class="caret"></b>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{route('user.profile')}}" class="dropdown-item">@lang('user.edit_link')</a>
                    <a href="{{route('user.log')}}" class="dropdown-item">@lang('user.log_link')</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{route('auth.login_to_site')}}" class="dropdown-item"><img src="{{asset('assets/img/dinmark.png')}}" alt="Dinmark" style="width: 16px; height: 16px; margin: 0; float: none"> @lang('user.dinmark_link')</a>
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
        </div>
        <div class="actions flex right-upper-counters">

        @if( $wishlists_count > 0)
                            <a href="{{route('catalogs')}}" class="likes-active" title="@lang('global.upper_right_butons.likes')">
                        @else
                            <a href="{{route('catalogs')}}" class="likes" title="@lang('global.upper_right_butons.likes')">
                        @endif
                            <i class="catalog-count">{{$wishlists_count}}</i>
                        </a>
                        <!--  -->
                        @if( $implementation_count > 0)
                            <a href="{{route('implementations')}}" class="comparison-active" title="@lang('global.upper_right_butons.comparison')">
                        @else
                            <a href="{{route('implementations')}}" class="comparison" title="@lang('global.upper_right_butons.comparison')">
                        @endif
                            <i class="implementation-count">{{$implementation_count}}</i>
                        </a>
                        <!--  -->
                        @if( $orders_count > 0)
                            <a href="{{route('orders')}}" class="cart-active" title="@lang('global.upper_right_butons.cart')">
                        @else
                            <a href="{{route('orders')}}" class="cart" title="@lang('global.upper_right_butons.cart')">
                        @endif
                            <i class="order-count">{{$orders_count}}</i>
                        </a>
            <!-- <a href="#" class="likes"><i>{{$wishlists_count}}</i></a>
            <a href="#" class="comparison"><i>{{$implementation_count}}</i></a>
            <a href="#" class="cart"><i>{{$orders_count}}</i></a> -->
            <div id="compareGroups"></div>
        </div>
	<!-- end header navigation right -->
    </div>

{{--
    <form @submit.prevent="handlerSubmit" id="filter" class="hide" style="display: block;">
        <div class="container flex flex-wrap">
            <div class="col-xl-2 col-lg-4 flex-wrap">
                <p class="first">???????????????? <span>(DIN, ????????, AN, ISO)</span></p>
                <div class="extend-search">
                    <div class="d-flex flex-wrap">
                        <span @click="removeQuery(index, 'standart')" v-for="(choice, index) of queryList.standart"
                              class="choice"><span class="times">&times;</span>@{{ choice }}</span>
                        <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.standart"
                               type="text" id="standart" class="search-input">
                    </div>
                    <div v-show="render.standart" @click="hasItem" id="standart" style="width: 100%" class="form-select">
                        <div @click="addQuery(item, 'standart'); data.standart = '';" class="select-item"
                             v-for="(item, index) of info.standart">@{{ item }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 flex-wrap">
                <p>?????????????? (????)</p>
                <div class="extend-search">
                    <div class="d-flex flex-wrap">
                        <span @click="removeQuery(index, 'diametr')" v-for="(choice, index) of queryList.diametr"
                              class="choice"><span class="times">&times;</span>@{{ choice }}</span>
                        <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.diametr"
                               type="text" id="diametr" class="search-input">
                    </div>
                    <div v-show="render.diametr" @click="hasItem" id="diametr" style="width: 100%" class="form-select">
                        <div @click="addQuery(item, 'diametr'); data.diametr = '';" class="select-item"
                             v-for="(item, index) of info.diametr">@{{ item }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 flex-wrap">
                <p>?????????????? (????)</p>
                <div class="extend-search">
                    <div class="d-flex flex-wrap">
                        <span @click="removeQuery(index, 'dovzhyna')" v-for="(choice, index) of queryList.dovzhyna"
                              class="choice"><span class="times">&times;</span>@{{ choice }}</span>
                        <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.dovzhyna"
                               type="text" id="dovzhyna" class="search-input">
                    </div>
                    <div v-show="render.dovzhyna" @click="hasItem" id="dovzhyna" style="width: 100%" class="form-select">
                        <div @click="addQuery(item, 'dovzhyna'); data.dovzhyna = '';" class="select-item"
                             v-for="(item, index) of info.dovzhyna">@{{ item }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 flex-wrap">
                <p>???????????????? </p>
                <div class="extend-search">
                    <div class="d-flex flex-wrap">
                        <span @click="removeQuery(index, 'material')" v-for="(choice, index) of queryList.material"
                              class="choice"><span class="times">&times;</span>@{{ choice }}</span>
                        <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.material"
                               type="text" id="material" class="search-input">
                    </div>
                    <div v-show="render.material" @click="hasItem" id="material" style="width: 100%" class="form-select">
                        <div @click="addQuery(item, 'material'); data.material = '';" class="select-item"
                             v-for="(item, index) of info.material">@{{ item }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 flex-wrap">
                <p>???????? ???????????????? </p>
                <div class="extend-search">
                    <div class="d-flex flex-wrap">
                        <span @click="removeQuery(index, 'klas_micnosti')"
                              v-for="(choice, index) of queryList.klas_micnosti" class="choice"><span
                                class="times">&times;</span>@{{ choice }}</span>
                        <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.klas_micnosti"
                               type="text" id="klas_micnosti" class="search-input">
                    </div>
                    <div v-show="render.klas_micnosti" @click="hasItem" id="klas_micnosti" style="width: 100%"
                         class="form-select">
                        <div @click="addQuery(item, 'klas_micnosti'); data.klas_micnosti = '';" class="select-item"
                             v-for="(item, index) of info.klas_micnosti">@{{ item }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 flex-wrap">
                <p>???????????????? </p>
                <div class="extend-search">
                    <div class="d-flex flex-wrap">
                        <span @click="removeQuery(index, 'pokryttja')" v-for="(choice, index) of queryList.pokryttja"
                              class="choice"><span class="times">&times;</span>@{{ choice }}</span>
                        <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.pokryttja"
                               type="text" id="pokryttja" class="search-input">
                    </div>
                    <div v-show="render.pokryttja" @click="hasItem" id="pokryttja" style="width: 100%" class="form-select">
                        <div @click="addQuery(item, 'pokryttja'); data.pokryttja = '';" class="select-item"
                             v-for="(item, index) of info.pokryttja">@{{ item }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="submit">
            <button @click="handlerSubmitGo" class="hexa">??????????</button>
        </div>
    </form>
--}}

<!-- end #header -->
</div>

<div id="mobile-header" class="mobile-header tab-and-mob-visible">
    <div class="row">
        <div class="container">
            <div class="col">
                <div class="top-actions-block--wrapper">
                    <a href="javascript:;" id="close-burger-menu" class="hide"
                    data-click="sidebar-minify1">
                        <i id="fa-times" class="fas fa-times"></i>
                    </a>
                    <div class="top-actions-block">
                            <div class="debt-item">
                                <span class="debt-text">
                                    {{__('global.header_debt',['debt'=>number_format($debt,2,',',' ')])}}
                                </span>
                            </div>
                            <div class="right-action-block">
                                <a class="dropdown">
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
                                </a>
                                <a class="dropdown navbar-language">
                                    <a href="#" class="dropdown-toggle pr-2 pl-2 pr-sm-4 pl-sm-4" data-toggle="dropdown">
                                        <span class="flag-icon flag-icon-{{ LaravelLocalization::getCurrentLocale() }}" title="{{ LaravelLocalization::getCurrentLocale() }}"></span>
                                        <span class="name d-none d-sm-inline">{{ mb_strtoupper(LaravelLocalization::getCurrentLocale()) }}</span> <b class="caret"></b>
                                    </a>
                                    <div class="dropdown-menu">
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            <a rel="alternate"  hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="dropdown-item"><span class="flag-icon flag-icon-{{$localeCode}}" title="{{$localeCode}}"></span> {{ $properties['native'] }}</a>
                                    @endforeach
                                    </div>
                                </a>
                                <a class="dropdown navbar-user">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        @if(auth()->user()->photo)
                                            <img class="navbar-user-img" src="{{\Config::get('values.dinmarkurl')}}images/profile/{{auth()->user()->photo}}"
                                            alt="{{auth()->user()->name}}" />
                                            @else
                                            <img class="navbar-user-img" src="{{\Config::get('values.dinmarkurl')}}images/empty-avatar.png" alt="{{auth()->user()->name}}" />
                                        @endif

                                        <span class="d-none d-md-inline">{{auth()->user()->name}}</span> <b class="caret"></b>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{route('user.profile')}}" class="dropdown-item">@lang('user.edit_link')</a>
                                        <a href="{{route('user.log')}}" class="dropdown-item">@lang('user.log_link')</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{{route('auth.login_to_site')}}" class="dropdown-item">
                                        <img src="{{asset('assets/img/dinmark.png')}}" alt="Dinmark" style="width: 16px; height: 16px; margin: 0; float: none"> @lang('user.dinmark_link')</a>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item" class="btn btn-link">@lang('user.logout')</button>
                                        </form>
                                    </div>
                                </a>
                                @if($sidebarTwo)
                                <a class="divider d-none d-md-block"></a>
                                <a class="d-none d-md-block">
                                    <a href="javascript:;" data-click="right-sidebar-toggled" class="f-s-14">
                                        <i class="fa fa-th"></i>
                                    </a>
                                </a>
                                @endif
                            </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="navbar-mobile-header ml-5">
                    @if ($sidebarTwo)
                    <a  href="javascript:;"class="sidebar-minify-btn navbar-toggle pull-left" data-click="sidebar-minify">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    @endif
                    @if (!$sidebarHide && !$headerTopMenu)
                    <a  href="javascript:;"class="sidebar-minify-btn navbar-toggle" data-click="sidebar-minify">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    @endif
                    @if ($headerTopMenu)
                        <a  href="javascript:;"class="sidebar-minify-btn navbar-toggle" data-click="sidebar-minify">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                    @endif
                    <a href="{{route('home')}}" class="navbar-brand"><img src="{{asset('assets/img/logo/logo.png')}}" alt="Dinmark Logo" class="dinmark-logo"></a>
                    <div class="actions flex ">
                        <!--  -->
                        @if( $wishlists_count > 0)
                            <a href="#" class="hideMobile likes-active">
                        @else
                            <a href="#" class="likes">
                        @endif
                            <i>{{$wishlists_count}}</i>
                        </a>
                        <!--  -->
                        @if( $implementation_count > 0)
                            <a href="#" class="hideMobile comparison-active">
                        @else
                            <a href="#" class="comparison">
                        @endif
                            <i>{{$implementation_count}}</i>
                        </a>
                        <!--  -->
                        @if( $orders_count > 0)
                            <a href="#" class="cart-active">
                        @else
                            <a href="#" class="cart">
                        @endif
                            <i>{{$orders_count}} </i>
                        </a>
                        <!--  -->
                        <div id="compareGroups"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="navbar-nav navbar-right flex-wrap navbar-form">
                    <!-- <form action="{{route('products.find')}}" method="GET" name="search_form" class="hexa">
                        @csrf
                        <input type="text" name="search" placeholder="@lang('global.header_search')" min="3"
                        required value="{{request()->has('search')?request()->input('search'):''}}">

                        <div class="more hexa-plus">
                            <i class="fas fa-plus"></i>
                        </div>
                    </form> -->

                    <div id="global_search_wrap">
            <select class="form-control m-b-5" id="global_search" name="product_id"></select>
            <div class="more hexa-plus">
        <i class="fas fa-plus"></i>
        </div>
        </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{--
<div class="mobile-navbar">
    <nav class="navbar-grey">
        <div class="container flex navbar-items">
            <a href="#" class="shop hexa">@lang('global.header_menu_catalog') <i class="fas fa-plus" id="show-catalog-menu"></i></a>

            <div class="flex  hideTab">
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/about" class="hexa" style="margin-left: 7px;">@lang('global.header_menu_about_company')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/affiliate-program" class="hexa">@lang('global.header_menu_affiliate')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/blog" class="hexa">@lang('global.header_menu_blog')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/tender" class="hexa">@lang('global.header_menu_tender')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/promo" class="hexa">@lang('global.header_menu_sale')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/contacts" class="hexa" style="margin-right: 8px;">@lang('global.header_menu_contact')</a>
            </div>

        <nav class="mainmenu" style="display: none;">
	<div>
        <a href="https://dinmark.com.ua/shop/bolty">
            <div>
                <img src="https://dinmark.com.ua/images/groups/bolty.svg">
            </div>
            <div>??????????</div></a><div class="submenu">
            <a href="https://dinmark.com.ua/shop/bolty/bolty-z-shestyhrannou-holovkou">?????????? ?? ???????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-vnutrishnim-shestryhrannykom">?????????? ?? ???????????????????? ????????????????????????????</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-potaynou-holovkou">?????????? ?? ???????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/bolty/bolty-t-podibni">?????????? ??-??????????????</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-kvadratnou-holovkou-pidholovnykom">?????????? ?? ???????????????????? ???????????????? / ??????????????????????????</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-neylonovym-pokryttjam-din-267-28-klf">?????????? ?? ???????????????????? ?????????????????? DIN 267-28 KLF</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-kilcem">?????????? ?? ??????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/hayky"><div><img src="https://dinmark.com.ua/images/groups/hayky.svg"></div><div>??????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/hayky/hayky-shestyhranni">?????????? ??????????????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-samokontrjaschi">?????????? ????????????????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-pryvarni">?????????? ????????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-barashkovi">?????????? ??????????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-koronchati">?????????? ??????????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-kovpachkovi">?????????? ????????????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-shlicevi">?????????? ??????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-z-flancem">?????????? ?? ??????????????</a><a href="https://dinmark.com.ua/shop/hayky/hayky-z-nakatkou">?????????? ?? ????????????????</a><a href="https://dinmark.com.ua/shop/hayky/rym-hayky">??????-??????????</a><a href="https://dinmark.com.ua/shop/hayky/presmasljanky">????????????????????????</a><a href="https://dinmark.com.ua/shop/hayky/zahlushky-probky-rizbovi">???????????????? / ???????????? ????????????????</a><a href="https://dinmark.com.ua/shop/hayky/vstavky-vtulky">?????????????? / ????????????</a><a href="https://dinmark.com.ua/shop/hayky/inshi-hayky">???????? ??????????</a></div></div><div><a href="https://dinmark.com.ua/shop/hvynty"><div><img src="https://dinmark.com.ua/images/groups/hvynty.svg"></div><div>????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-potaynou-holovkou">???????????? ?? ???????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-napivkruhlou-holovkou">???????????? ?? ???????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-barashkovi">???????????? ??????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-ustanovochni">???????????? ??????????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-napivpotaynou-holovkou">???????????? ?? ?????????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-pryvarni">???????????? ????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-antyvandalni">???????????? ??????????????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-nakatkou">???????????? ?? ????????????????</a><a href="https://dinmark.com.ua/shop/hvynty/inshi-hvynty">???????? ????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/samorizy"><div><img src="https://dinmark.com.ua/images/groups/samorizy.svg"></div><div>????????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-potaynou-holovkou">???????????????? ?? ???????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-napivpotaynou-holovkou">???????????????? ?? ?????????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-shestyhrannou-holovkou">???????????????? ?? ???????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-napivkruhlou-holovkou">???????????????? ?? ???????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-burom">???????????????? ?? ??????????</a></div></div><div><a href="https://dinmark.com.ua/shop/shayby"><div><img src="https://dinmark.com.ua/images/groups/shayby.svg"></div><div>??????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shayby/shayby-ploski">?????????? ????????????</a><a href="https://dinmark.com.ua/shop/shayby/shayby-kosi">?????????? ????????</a><a href="https://dinmark.com.ua/shop/shayby/shayby-pruzhynni">?????????? ????????????????</a><a href="https://dinmark.com.ua/shop/shayby/shayby-nord-lock">?????????? Nord-Lock</a><a href="https://dinmark.com.ua/shop/shayby/shayby-stoporni">?????????? ????????????????</a><a href="https://dinmark.com.ua/shop/shayby/shayby-starlock">?????????? Starlock</a><a href="https://dinmark.com.ua/shop/shayby/inshi-shayby">???????? ??????????</a><a href="https://dinmark.com.ua/shop/shayby/stoporni-kilcja">???????????????? ????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/shurupy"><div><img src="https://dinmark.com.ua/images/groups/shurupy.svg"></div><div>????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-potaynou-holovkou">???????????? ?? ???????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-napivkruhlou-holovkou">???????????? ?? ???????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-shestyhrannou-holovkou">???????????? ?? ???????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-napivpotaynou-holovkou">???????????? ?? ?????????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-kilcem-hachkom">???????????? ?? ?????????????? / ????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/zaklepky"><div><img src="https://dinmark.com.ua/images/groups/zaklepky.svg"></div><div>????????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/zaklepky/zaklepky-vytjazhni">???????????????? ??????????????</a><a href="https://dinmark.com.ua/shop/zaklepky/zaklepky-pid-molotok">???????????????? ?????? ??????????????</a><a href="https://dinmark.com.ua/shop/zaklepky/klepalni-hayky">?????????????????? ??????????</a></div></div><div><a href="https://dinmark.com.ua/shop/shpylky-rizbovi"><div><img src="https://dinmark.com.ua/images/groups/shpylky-rizbovi.svg"></div><div>?????????????? ????????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-975-shpylka-rizbova">DIN 975 ?????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-975-shpylka-z-trapecepodibnou-rizbou">DIN 975 ?????????????? ?? ?????????????????????????????? ??????????????</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-938-shpylka-rizbova-z-dopuskom-1d">DIN 938 ?????????????? ???????????????? ?? ???????????????? 1d</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-835-shpylka-rizbova-z-dopuskom-2d">DIN 835 ?????????????? ???????????????? ?? ???????????????? 2D</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-2510-nf-shpylka-dlja-flancevykh-zednan">DIN 2510 NF ?????????????? ?????? ?????????????????? ??'????????????</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-939-shpylka-rizbova-z-dopuskom-125d">DIN 939 ?????????????? ???????????????? ?? ???????????????? 1,25d</a></div></div><div><a href="https://dinmark.com.ua/shop/shtifty"><div><img src="https://dinmark.com.ua/images/groups/shtifty.svg"></div><div>????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shtifty/shtifty-konusni">???????????? ??????????????</a><a href="https://dinmark.com.ua/shop/shtifty/shtifty-cylindrychni">???????????? ??????????????????????</a><a href="https://dinmark.com.ua/shop/shtifty/shponky">????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/shplinty"><div><img src="https://dinmark.com.ua/images/groups/shplinty.svg"></div><div>??????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shplinty/an-75-shplint-pruzhynnyy-holchastyy-forma-e">AN 75 ???????????? ?????????????????? ?????????????????? ?????????? E</a><a href="https://dinmark.com.ua/shop/shplinty/an-77-shplint-z-kilcem-pruzhynnyy">AN 77 ???????????? ?? ?????????????? ??????????????????</a><a href="https://dinmark.com.ua/shop/shplinty/din-94-shplint-rozvidnyy">DIN 94 ???????????? ??????????????????</a><a href="https://dinmark.com.ua/shop/shplinty/art-7383-kilce-shplint">ART 7383 ????????????-????????????</a><a href="https://dinmark.com.ua/shop/shplinty/an-76-shplint-pruzhynnyy-holchastyy-forma-d">AN 76 ???????????? ?????????????????? ?????????????????? ?????????? D</a><a href="https://dinmark.com.ua/shop/shplinty/din-11024-shplint-pruzhynnyy-forma-d">DIN 11024 ???????????? ?????????????????? ?????????? D</a><a href="https://dinmark.com.ua/shop/shplinty/din-11024-shplint-pruzhynnyy-forma-e">DIN 11024 ???????????? ?????????????????? ?????????? E</a></div></div><div><a href="https://dinmark.com.ua/shop/ankery"><div><img src="https://dinmark.com.ua/images/groups/ankery.svg"></div><div>????????????, ????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/ankery/an-214-anker-v-beton-z-vnutrishnou-rizbou">AN 214 ?????????? ?? ?????????? ?? ?????????????????????? ??????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-215-anker-zabyvnyy">AN 215 ?????????? ????????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-217-anker-klynovyy">AN 217 ?????????? ????????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-228-anker-zakrytyy-z-potaynou-holovkou">AN 228 ?????????? ???????????????? ?? ???????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-253-anker-molly">AN 253 ?????????? MOLLY</a><a href="https://dinmark.com.ua/shop/ankery/an-257-anker-etaf">AN 257 ?????????? ETAF</a><a href="https://dinmark.com.ua/shop/ankery/an-305-anker-dlja-fiksacii">AN 305 ?????????? ?????? ????????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-233-hvynt-hx-po-betonu">AN 233 ?????????? HX ???? ????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-234-hvynt-turbo-">AN 234 ?????????? TURBO </a><a href="https://dinmark.com.ua/shop/ankery/an-260-strubcyna-dlja-montazhu-na-stalnykh-balkakh">AN 260 ?????????????????? ?????? ?????????????? ???? ???????????????? ????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-237-dubel-dlja-betonu-z-shurupom-">AN 237 ???????????? ?????? ???????????? ?? ?????????????? </a><a href="https://dinmark.com.ua/shop/ankery/an-245-dubel-dlja-cehly">AN 245 ???????????? ?????? ??????????</a><a href="https://dinmark.com.ua/shop/ankery/an-246-dubel-dlja-cehly-iz-shurupom">AN 246 ???????????? ?????? ?????????? ???? ??????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-289-dubel-plastykovyy-dlja-izoljacii">AN 289 ???????????? ?????????????????????? ?????? ????????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-301-dubel-neylonovyy-dlja-betonu">AN 301 ???????????? ???????????????????? ?????? ????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-303-dubel-dlja-betonu">AN 303 ???????????? ?????? ????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-304-dubel-polipropilenovyy-dlja-betonu">AN 304 ???????????? ???????????????????????????????? ?????? ????????????</a><a href="https://dinmark.com.ua/shop/ankery/an-410-dubel-z-hvyntom-i-shestyhrannou-holovkou">AN 410 ???????????? ?? ?????????????? ?? ???????????????????????? ????????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/khomuty"><div><img src="https://dinmark.com.ua/images/groups/khomuty.svg"></div><div>????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/khomuty/din-3016-khomut-z-pidkladkou-epdm">DIN 3016 ?????????? ?? ???????????????????? EPDM</a><a href="https://dinmark.com.ua/shop/khomuty/din-3017-1-khomut-chervjachnyy">DIN 3017-A ?????????? ????????'??????????</a><a href="https://dinmark.com.ua/shop/khomuty/din-3017-khomut-sylovyy-c1">DIN 3017-C ?????????? ??????????????</a><a href="https://dinmark.com.ua/shop/khomuty/art-7354-khomut">ART 7354 ??????????</a><a href="https://dinmark.com.ua/shop/khomuty/din-3567">DIN 3567 ?????????? ??????????????</a><a href="https://dinmark.com.ua/shop/khomuty/din-3570-khomut-u-podibnyy">DIN 3570 ?????????? U-????????????????</a><a href="https://dinmark.com.ua/shop/khomuty/art-7460-khomut">ART 7460 ??????????</a><a href="https://dinmark.com.ua/shop/khomuty/an-114-kabelna-stjazhka">AN 114 ???????????????? ????????????</a><a href="https://dinmark.com.ua/shop/khomuty/art-7461-khomut">ART 7461 ??????????</a></div></div><div><a href="https://dinmark.com.ua/shop/takelazh"><div><img src="https://dinmark.com.ua/images/groups/takelazh.svg"></div><div>??????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/takelazh/trosy-">?????????? </a><a href="https://dinmark.com.ua/shop/takelazh/lancuhy">??????????????</a><a href="https://dinmark.com.ua/shop/takelazh/talrepy">??????????????</a><a href="https://dinmark.com.ua/shop/takelazh/karabiny">????????????????</a><a href="https://dinmark.com.ua/shop/takelazh/zazhymy-dlja-trosu">???????????? ?????? ??????????</a><a href="https://dinmark.com.ua/shop/takelazh/skoby">??????????</a></div></div><div><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht"><div><img src="https://dinmark.com.ua/images/groups/osnastka-dlja-jakht.svg"></div><div>???????????????? ?????? ??????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/okovky-i-kronshteyny">???????????? ?? ????????????????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/takelazhni-bloky">?????????????????? ??????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/nakonechnyky">??????????????????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/utky">????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/knekhty-i-kluzy">???????????? ?? ??????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/haky">????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/petli">??????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/zamky-">?????????? </a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/obushky">????????????</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/inshe">????????</a></div></div><div><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley"><div><img src="https://dinmark.com.ua/images/groups/kriplennja-dlja-montazhu-sonjachnykh-paneley.svg"></div><div>?????????????????? ?????? ?????????????? ???????????????? ??????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9211-hvynt-shurup-kombinovanyy-(santekhshpylka)">ART 9211 ??????????-?????????? ???????????????????????? (??????????????????????????)</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-601-hayka-dlja-profiliv">AN 601 ?????????? ?????? ????????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-602-hayka-pryzhymna-dlja-profilja">AN 602 ?????????? ???????????????? ?????? ??????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-604-hayka-pryzhymna-dlja-profilja">AN 604  ?????????? ???????????????? ?????? ??????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-606-hayka-pryzhymna-dlja-profilja">AN 606 ?????????? ???????????????? ?????? ??????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9082-hvynt-shurup-kombinovanyy-(santekhshpylka)">ART 9082 ??????????-?????????? ???????????????????????? (??????????????????????????)</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9082-shayba-epdm-">ART 9082 ?????????? EPDM </a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-607-hayka-pryzhymna-dlja-profilja">AN 607 ?????????? ???????????????? ?????? ??????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-612-bolt-t-podibnyy">AN 612 ???????? ??-????????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-613-hayka-kvadratna">ART 613 ?????????? ????????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9521-kronshteyn-kriplennja-dlja-sonjachnykh-system">ART 9521 ?????????????????? ?????????????????? ?????? ???????????????? ????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9525-kronshteyn-kriplennja-dlja-sonjachnykh-system">ART 9525 ?????????????????? ?????????????????? ?????? ???????????????? ????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9543-plastyna-perekhidna">ART 9543 ???????????????? ??????????????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9583-zazhym-pid-kutom">ART 9583 ?????????? ?????? ??????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9583-zatyskach-dlja-shviv-pid-kutom">ART 9583 ???????????????? ?????? ???????? ?????? ??????????</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9742-modul-dlja-skla-aluf22-epdm">ART 9742 ???????????? ?????? ???????? AluF22 EPDM</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9745-modul-dlja-skla-aluf22-epdm">ART 9745 ???????????? ?????? ???????? AluF22 EPDM</a></div></div><div><a href="https://dinmark.com.ua/shop/duymove-kriplennja"><div><img src="https://dinmark.com.ua/images/groups/duymove-kriplennja.svg"></div><div>?????????????? ??????????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-934-hayka-shestyhranna-z-duymovou-rizbou">DIN 934 ?????????? ?????????????????????? ?? ???????????????? ??????????????</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-933-109-uns-bolt-z-shestyhrannou-holovkou-i-povnou-rizbou-duymovou-rizbou">DIN 933 ???????? ?? ???????????????????????? ???????????????? ?? ???????????? ??????????????, ???????????????? ??????????????</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-931-bolt-z-shestyhrannou-holovkou-i-chastkovou-rizbou-duymovou-rizbou">DIN 931 ???????? ?? ???????????????????????? ???????????????? ?? ?????????????????? ??????????????, ???????????????? ??????????????</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-912-bolt-z-cylindrychnou-holovkou-i-vnutrishnim-shestyhrannykom-z-duymovou-rizbou">DIN 912 ???????? ?? ???????????????????????? ???????????????? ?? ???????????????????? ???????????????????????????? ?? ???????????????? ??????????????</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-975-shpylka-rizbova-z-duymovou-rizbou">DIN 975 ?????????????? ???????????????? ?? ???????????????? ??????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja"><div><img src="https://dinmark.com.ua/images/groups/konstrukciyne-kriplennja.svg"></div><div>?????????????????????????? ??????????????????</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-6914-bolt-vysokomicnyy-z-shestyhrannou-holovkou">DIN 6914 ???????? ???????????????????????? ?? ???????????????????????? ????????????????</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-6915-hayka-vysokomicna-shestyhranna">DIN 6915 ?????????? ?????????????????????? ??????????????????????</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-6916-shayba-vysokomicna">DIN 6916 ?????????? ??????????????????????</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/en-14399-4-komplekt-vysokomicnyy-hv">EN 14399-4 ???????????????? ???????????????????????? HV</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-186-46-bolt-t-podibnyy-din-980">DIN 7999 ???????? ???????????????????????? ?? ???????????????????????? ????????????????</a></div></div><div><a href="https://dinmark.com.ua/shop/kljaymery"><div><img src="https://dinmark.com.ua/images/groups/kljaymery.svg"></div><div>????????????????</div></a></div></nav>        </div>
    </nav>
</div>
<div class="filter--wrapper">

<form @submit.prevent="handlerSubmit" id="mobile-filter" class="hide" style="display: block;">
    <div class="container flex">
        <div class="column flex-wrap">
        <p>???????????????? (DIN, ????????, AN, ISO)</p>
        <div class="extend-search">
        <div class="d-flex flex-wrap">
            <span @click="removeQuery(index, 'standart')" v-for="(choice, index) of queryList.standart" class="choice"><span class="times">&times;</span>@{{choice}}</span>
            <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.standart" type="text" id="standart" class="search-input">
        </div>
        <div v-show="render.standart" @click="hasItem" id="standart" style="width: 100%" class="form-select">
            <div @click="addQuery(item, 'standart'); data.standart = '';" class="select-item" v-for="(item, index) of info.standart">@{{item}}
            </div>
        </div>
        </div>
         </div>
         <div>
            <p>?????????????? (????)</p>
            <div class="extend-search">
        <div class="d-flex flex-wrap">
            <span @click="removeQuery(index, 'diametr')" v-for="(choice, index) of queryList.diametr" class="choice"><span class="times">&times;</span>@{{choice}}</span>
            <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.diametr" type="text" id="diametr" class="search-input">
        </div>
        <div v-show="render.diametr" @click="hasItem" id="diametr" style="width: 100%" class="form-select">
            <div @click="addQuery(item, 'diametr'); data.diametr = '';" class="select-item" v-for="(item, index) of info.diametr">@{{item}}</div>
        </div>
        </div>
        </div>
        <div>
            <p>?????????????? (????)</p>
            <div class="extend-search">
        <div class="d-flex flex-wrap">
            <span @click="removeQuery(index, 'dovzhyna')" v-for="(choice, index) of queryList.dovzhyna" class="choice"><span class="times">&times;</span>@{{choice}}</span>
            <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.dovzhyna" type="text" id="dovzhyna" class="search-input">
        </div>
        <div v-show="render.dovzhyna" @click="hasItem" id="dovzhyna" style="width: 100%" class="form-select">
            <div @click="addQuery(item, 'dovzhyna'); data.dovzhyna = '';" class="select-item" v-for="(item, index) of info.dovzhyna">@{{item}}</div>
        </div>
        </div>
        </div>
        <div>
            <p>???????????????? </p>
            <div class="extend-search">
        <div class="d-flex flex-wrap">
            <span @click="removeQuery(index, 'material')" v-for="(choice, index) of queryList.material" class="choice"><span class="times">&times;</span>@{{choice}}</span>
            <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.material" type="text" id="material" class="search-input">
        </div>
        <div v-show="render.material" @click="hasItem" id="material" style="width: 100%" class="form-select">
            <div @click="addQuery(item, 'material'); data.material = '';" class="select-item" v-for="(item, index) of info.material">@{{item}}</div>
        </div>
        </div>
        </div>
        <div>
            <p>???????? ???????????????? </p>
            <div class="extend-search">
        <div class="d-flex flex-wrap">
            <span @click="removeQuery(index, 'klas_micnosti')" v-for="(choice, index) of queryList.klas_micnosti" class="choice"><span class="times">&times;</span>@{{choice}}</span>
            <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.klas_micnosti" type="text" id="klas_micnosti" class="search-input">
        </div>
        <div v-show="render.klas_micnosti" @click="hasItem" id="klas_micnosti" style="width: 100%" class="form-select">
            <div @click="addQuery(item, 'klas_micnosti'); data.klas_micnosti = '';" class="select-item" v-for="(item, index) of info.klas_micnosti">@{{item}}</div>
        </div>
        </div>
        </div>
        <div>
            <p>???????????????? </p>
            <div class="extend-search">
        <div class="d-flex flex-wrap">
            <span @click="removeQuery(index, 'pokryttja')" v-for="(choice, index) of queryList.pokryttja" class="choice"><span class="times">&times;</span>@{{choice}}</span>
            <input @click="handlerSubmit(event);" @input="handlerSubmit(event);" v-model="data.pokryttja" type="text" id="pokryttja" class="search-input">
        </div>
        <div v-show="render.pokryttja" @click="hasItem" id="pokryttja" style="width: 100%" class="form-select">
            <div @click="addQuery(item, 'pokryttja'); data.pokryttja = '';" class="select-item" v-for="(item, index) of info.pokryttja">@{{item}}</div>
        </div>
        </div>
            </div>
        </div>
    <div class="submit">
        <button @click="handlerSubmitGo" class="hexa">??????????</button>
    </div>
</form>

</div>
--}}
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="/assets/plugins/select2/dist/js/vue.min.js"></script>
<script>

    new Vue({
        el: "#wrap",
        data() {
            return {
              render: {
                standart: false,
                diametr: false,
                dovzhyna: false,
                material: false,
                klas_micnosti: false,
                pokryttja: false
              },
              info: {
                standart: [],
                diametr: [],
                dovzhyna:[],
                material: [],
                klas_micnosti: [],
                pokryttja: []
              },
              data: {
                standart: '',
                diametr: '',
                dovzhyna: '',
                material: '',
                klas_micnosti: '',
                pokryttja: '',
                active: ''
              },
              queryList: {
                standart: [],
                diametr: [],
                dovzhyna: [],
                material: [],
                klas_micnosti: [],
                pokryttja: [],
                active: ''
              },
              globalSearch: '',
              globalResult: {
                implementations: [],
                orders: [],
                products: [],
                reclamations: []
              },
              showGlobalSearch: false,
              failedSearch: false,
              debounce: null,
              detailText: "aaa 98 vvv ddd 7 98"
            }
        },
        computed: {
            list: () => this.info.standart
        },
        methods: {
            reset() {
                this.globalSearch = '';
                this.globalResult = {
                        implementations: [],
                        orders: [],
                        products: [],
                        reclamations: []
                    };
                this.showGlobalSearch = false;
            },
            viewSearchResult(category) {
                window.location = '{{ route('products') }}' +'/find?search='+ `${this.globalSearch}#${category}`;
            },
            openItem(category, id, product_id) {
                if(category === 'orders') {
                    window.location = '{{ route('orders') }}' + '/' + id;
                } else if(category === 'products') {
                    window.location = '{{ route('products') }}' + '/' + id;
                } else if(category === 'reclamations') {
                    window.location = '{{ route('reclamations') }}' + '/show?reclamation=' + id + '&focus=' + product_id;
                } else if(category === 'implementations') {
                    //window.location = '{{ route('implementations') }}' + '/' + id;
										window.location = '{{ route('implementations') }}' + '/show?implementation=' + id + '&focus=' + product_id;
                }
            },
            getResults() {
                clearTimeout(this.debounce);
                this.debounce = setTimeout(() => {
                    asyncRequest();
                }, 500);

                const asyncRequest = async () => {
                if(this.globalSearch.length > 2) {
                    this.failedSearch = false;
                   await fetch('{{route('globalsearch')}}' + `?name=${this.globalSearch}`).then(response => response.json()).then(data => this.globalResult = data);
                   this.globalRender();
                } else if(this.globalSearch === '') {
                    this.globalResult = {
                        implementations: [],
                        orders: [],
                        products: [],
                        reclamations: []
                    };
                        this.showGlobalSearch = false;
                    }
                }
            },
            globalRender() {
                if (this.globalResult.implementations.length > 0 || this.globalResult.orders.length > 0 || this.globalResult.products.length > 0 || this.globalResult.reclamations.length > 0) {
                    this.showGlobalSearch = true;
                } else {
                    this.showGlobalSearch = false;
                    this.failedSearch = true;
                }
                // if(this.globalResult.implementations.length) {
                // this.globalResult.implementations.forEach(item => {
                //     item.searchText = this.replacer(item.text, this.globalSearch, `<b>${this.globalSearch}</b>`) || item.text;
                // })}
                // if(this.globalResult.orders.length) {
                // this.globalResult.orders.forEach(item => {
                //     item.searchText = this.replacer(item.text, this.globalSearch, `<b>${this.globalSearch}</b>`) || item.text;
                // })}
                // if(this.globalResult.products.length) {
                //     this.globalResult.products.forEach(item => {
                //         let b = new RegExp(this.globalSearch);
                //         item.searchText = this.replacer(item.text, this.globalSearch, `<b>${this.globalSearch}</b>`) || item.text;
                //     }
                // )}
                // if(this.globalResult.implementations.length) {
                // this.globalResult.implementations.forEach(item => {
                //     item.searchText = this.replacer(item.text, this.globalSearch, `<b>${this.globalSearch}</b>`) || item.text;
                // })}
            },
            hasItem(e) {
               if(e.target.className != 'form-select') {
                   e.target.classList.add('selected');
               }
            },
            searchFilter(word, prop) {
                let a = prop.filter(item => item.includes(word))
                return a;

            },
            selectRender(e) {
                for (const key in this.render) {
                    if (Object.hasOwnProperty.call(this.render, key)) {
                        e.target.id === key && e.target.id !== undefined ? this.render[key] = true : this.render[key] = false;
                    }
                }
            },
            removeQuery(index, array) {
              const items = [...document.querySelectorAll('.selected')];
              for (let i = 0; i < items.length; i++) {
                if(items[i].innerText == this.queryList[array][index]) {
                      items[i].classList.remove('selected');
                  }
              }
              this.queryList[array].splice(index, 1);
              this.handlerSubmit();
            },
            addQuery(param, prop) {
              let array = this.queryList[prop];
              let flag = 0;
              let hasParam = false;
              array.forEach(item => {
                  item == param ? flag++ : false;
                });
              if(!!param && flag === 0) {
                 array.push(param);
              }
              if(!!param && array.length === 0) {
                array.push(param);
              }
              this.handlerSubmit();
            },
            handlerSubmit(event) {
                this.selectRender(event)
                let input = event ? event.target.value : '';
				let active = this.queryList.active = event ? event.target.id : this.queryList.active;
                const param = this.queryList;
                let queryStr = `?standart=${param.standart}&diametr=${param.diametr}&dovzhyna=${param.dovzhyna}&material=${param.material}&klas_micnosti=${param.klas_micnosti}&pokryttja=${param.pokryttja}&active=${param.active}`;

				fetch('{{route('extendedSearch')}}' + queryStr).then(res => !!res ? res.json() : false).then(arr => !!event.data ? this.info[active] = arr.filter(item => item.toString().toLowerCase().includes(this.data[event.target.id].toString().toLowerCase())) : this.info[active] = arr);
            },
            handlerSubmitGo() {
                const param = this.queryList;
                let queryStr = `?standart=${param.standart}&diametr=${param.diametr}&dovzhyna=${param.dovzhyna}&material=${param.material}&klas_micnosti=${param.klas_micnosti}&pokryttja=${param.pokryttja}`;
                window.location = '{{route('products.find')}}/' + queryStr;
            },
            replacer(sting, oldS, newS) {
                return sting.split(oldS).join(newS);
            }
        },
        mounted() {
        }

    });
</script>
<script>
    const burger = document.querySelector(".sidebar-minify-btn.navbar-toggle");
    const closeBurger = document.querySelector("#close-burger-menu");
    const toggleBurger = (el) => el.classList.toggle("hide");
    const listenClick1 = burger.addEventListener('click', ()=> toggleBurger(burger));
    const listenClick2 = closeBurger.addEventListener('click', ()=> toggleBurger(burger));
</script>

<script>
		function changeUpperCounter(action){
			//catalog-count,implementation-count,order-count
			let UpperCounter = document.getElementsByClassName('right-upper-counters')[0];

			if(action === 'catalog'){
				UpperCounter.children[0].children[0].innerText = parseInt(UpperCounter.children[2].children[0].innerText) + 1;
			}
			if(action === 'implementation'){
				UpperCounter.children[1].children[0].innerText = parseInt(UpperCounter.children[2].children[0].innerText) + 1;
			}
			if(action === 'order'){
				UpperCounter.children[2].children[0].innerText = parseInt(UpperCounter.children[2].children[0].innerText) + 1;
			}
		}
    (function ($) {
        "use strict";
        $(document).ready(function() {
            const showFilter = (id, el) => {
                const filter = $(id)
                const btnFilter = el
                filter.hasClass("hide") ? filter.removeClass("hide").addClass("show") : filter.removeClass("show").addClass("hide")
                btnFilter.hasClass("fas fa-plus") ? btnFilter.removeClass("fas fa-plus").addClass("fas fa-minus") : btnFilter.removeClass("fas fa-minus").addClass("fas fa-plus")
            }
            $('.more.hexa-plus').click((e) => {
                if(window.innerWidth > 1050) {
                    showFilter('#filter', $(e.target).children('.fas'))
                }
            })
            $('.more.hexa-plus').click((e) => {
                if(window.innerWidth < 1051) {
                    showFilter('#mobile-filter', $(e.target).children('.fas'))
                }
            })

        });
    })(jQuery);
</script>
