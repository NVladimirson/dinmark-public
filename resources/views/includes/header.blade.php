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
            <select class="form-control m-b-5" id="global_search" name="product_id"></select>
            <div class="more hexa-plus">
                <i class="fas fa-plus"></i>
            </div>
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


<form @submit.prevent="handlerSubmit" id="filter" class="hide" style="display: block;">
    <div class="container flex">
        <div class="column flex-wrap"><p>Стандарт (DIN, ГОСТ, AN, ISO)</p>
        <p class="listChoice d-flex flex-wrap">
          <span @click="removeQuery(index, 'standart')" v-for="(choice, index) of queryList.standart" class="choice"><span class="times">&times;</span>@{{choice}}</span>
        </p>
        <input @click="handlerSubmit(event)" @change="addQuery(data.standart, 'standart'); data.standart = ''"  @input="handlerSubmit(event)" v-model="data.standart" style="width: 225px;border-radius:5px;height:34px;" type="text" list="standart">
            <datalist   id="standart"  id="header_filter-64-standart-d" name="64-standart"
            style="width: 100%" data-placeholder="(DIN, ГОСТ, AN, ISO)"
            data-allow-clear="true" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-64-standart-d">
                <option id="index" v-for="(item, index) of info.standart" data-select2-id="72" :value="item">@{{item}}</option>
            </datalist>
            <!-- <span class="select2 select2-container select2-container--default"
            dir="ltr" data-select2-id="71" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--single"
                    role="combobox" aria-haspopup="true"
                    aria-expanded="false" tabindex="0"
                    aria-disabled="false"
                    aria-labelledby="select2-header_filter-64-standart-container">
                        <span class="select2-selection__rendered"
                        id="select2-header_filter-64-standart-container"
                        role="textbox" aria-readonly="true">
                            <span class="select2-selection__placeholder">(DIN, ГОСТ, AN, ISO)
                            </span>
                        </span>
                        <span class="select2-selection__arrow" role="presentation">
                            <b role="presentation"></b>
                        </span>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span> -->
         </div>
         <div>
            <p>Діаметр (мм)</p>
            <p class="listChoice">
          <span @click="removeQuery(index, 'diametr')" v-for="(choice, index) of queryList.diametr" class="choice"><span class="times">&times;</span>@{{choice}}</span>
        </p>
        <input @click="handlerSubmit(event)" @change="addQuery(data.diametr, 'diametr'); data.diametr=''"  @input="handlerSubmit(event)" v-model="data.diametr" style="width: 225px;border-radius:5px;height:34px;" type="text" list="diametr">
            <datalist   id="diametr"  id="header_filter-64-standart-d" name="diametr"
            style="width: 100%" data-placeholder="(DIN, ГОСТ, AN, ISO)"
            data-allow-clear="true" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-64-standart-d">
                <option v-for="(item, index) of info.diametr" data-select2-id="72" :value="item">@{{item}}</option>
            </datalist>
            
            <!-- <select id="header_filter-7-diametr-d" name="7-diametr[]" multiple=""
                style="width: 100%" tabindex="-1" class="select2-hidden-accessible"
                aria-hidden="true" data-select2-id="header_filter-7-diametr-d">
            </select>
            <span class="select2 select2-container select2-container--default"
                dir="ltr" data-select2-id="73" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple"
                        role="combobox" aria-haspopup="true" aria-expanded="false"
                        tabindex="-1" aria-disabled="false">
                        <ul class="select2-selection__rendered">
                            <li class="select2-search select2-search--inline">
                                <input class="select2-search__field" type="search"
                                    tabindex="0" autocomplete="off" autocorrect="off"
                                    autocapitalize="none" spellcheck="false"
                                    role="searchbox" aria-autocomplete="list"
                                    placeholder="" style="">
                            </li>
                        </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span> -->
        </div>
        <div>
            <p>Довжина (мм)</p>
            <p class="listChoice">
          <span @click="removeQuery(index, 'dovzhyna')" v-for="(choice, index) of queryList.dovzhyna" class="choice"><span class="times">&times;</span>@{{choice}}</span>
        </p>
        <input @click="handlerSubmit(event)" @change="addQuery(data.dovzhyna, 'dovzhyna'); data.dovzhyna =''"  @input="handlerSubmit(event)" v-model="data.dovzhyna" style="width: 225px;border-radius:5px;height:34px;" type="text" list="dovzhyna">
            <datalist   id="dovzhyna"  id="header_filter-64-standart-d" name="dovzhyna"
            style="width: 100%" data-placeholder="(DIN, ГОСТ, AN, ISO)"
            data-allow-clear="true" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-64-standart-d">
                <option v-for="(item, index) of info.dovzhyna" data-select2-id="72" :value="item">@{{item}}</option>
            </datalist>
            <!-- <select id="header_filter-26-dovzhyna-d" name="26-dovzhyna[]"
                multiple="" style="width: 100%" tabindex="-1"
                class="select2-hidden-accessible" aria-hidden="true"
                data-select2-id="header_filter-26-dovzhyna-d">
            </select>
            <span class="select2 select2-container select2-container--default"
            dir="ltr" data-select2-id="74" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple"
                    role="combobox" aria-haspopup="true" aria-expanded="false"
                    tabindex="-1" aria-disabled="false">
                    <ul class="select2-selection__rendered">
                        <li class="select2-search select2-search--inline">
                        <input class="select2-search__field" type="search" tabindex="0"
                        autocomplete="off" autocorrect="off" autocapitalize="none"
                        spellcheck="false" role="searchbox" aria-autocomplete="list"
                        placeholder="" style="">
                        </li>
                    </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span> -->
        </div>
        <div>
            <p>Матеріал </p>
            <p class="listChoice">
          <span @click="removeQuery(index, 'material')" v-for="(choice, index) of queryList.material" class="choice"><span class="times">&times;</span>@{{choice}}</span>
        </p>
        <input @click="handlerSubmit(event)" @change="addQuery(data.material, 'material'); data.material=''"  @input="handlerSubmit(event)" v-model="data.material" style="width: 225px;border-radius:5px;height:34px;" type="text" list="material">
            <datalist   id="material"  id="header_filter-64-standart-d" name="material"
            style="width: 100%" data-placeholder="(DIN, ГОСТ, AN, ISO)"
            data-allow-clear="true" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-64-standart-d">
                <option v-for="(item, index) of info.material" data-select2-id="72" :value="item">@{{item}}</option>
            </datalist>
            <!-- <select id="header_filter-11-material-d" name="11-material[]"
            multiple="" style="width: 100%" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-11-material-d">
            </select>
            <span class="select2 select2-container select2-container--default" dir="ltr"
            data-select2-id="75" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple" role="combobox"
                    aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false">
                    <ul class="select2-selection__rendered">
                        <li class="select2-search select2-search--inline">
                        <input class="select2-search__field" type="search" tabindex="0" autocomplete="off"
                        autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox"
                        aria-autocomplete="list" placeholder="" style="">
                        </li>
                    </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span> -->
        </div>
        <div>
            <p>Клас міцності </p>
            <p class="listChoice">
          <span @click="removeQuery(index, 'klas_micnosti')" v-for="(choice, index) of queryList.klas_micnosti" class="choice"><span class="times">&times;</span>@{{choice}}</span>
        </p>
        <input @click="handlerSubmit(event)" @change="addQuery(data.klas_micnosti, 'klas_micnosti'); data.klas_micnosti=''"  @input="handlerSubmit(event)" v-model="data.klas_micnosti" style="width: 225px;border-radius:5px;height:34px;" type="text" list="klas_micnosti">
            <datalist   id="klas_micnosti"  id="header_filter-64-standart-d" name="klas_micnosti"
            style="width: 100%" data-placeholder="(DIN, ГОСТ, AN, ISO)"
            data-allow-clear="true" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-64-standart-d">
                <option v-for="(item, index) of info.klas_micnosti" data-select2-id="72" :value="item">@{{item}}</option>
            </datalist>

            <!-- <select id="header_filter-28-klas-micnosti-d" name="28-klas-micnosti[]" multiple=""
            style="width: 100%" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true"
            data-select2-id="header_filter-28-klas-micnosti-d">
            </select>
            <span class="select2 select2-container select2-container--default" dir="ltr"
            data-select2-id="76" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple" role="combobox"
                    aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false">
                        <ul class="select2-selection__rendered">
                        <li class="select2-search select2-search--inline">
                        <input class="select2-search__field" type="search" tabindex="0" autocomplete="off"
                        autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox"
                        aria-autocomplete="list" placeholder="" style="">
                        </li>
                    </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span> -->
        </div>
        <div>
            <p>Покриття </p>
            <p class="listChoice">
          <span @click="removeQuery(index, 'pokryttja')" v-for="(choice, index) of queryList.pokryttja" class="choice"><span class="times">&times;</span>@{{choice}}</span>
        </p>
        <input @click="handlerSubmit(event)" @change="addQuery(data.pokryttja, 'pokryttja'); data.pokryttja=''"  @input="handlerSubmit(event)" v-model="data.pokryttja" style="width: 225px;border-radius:5px;height:34px;" type="text" list="pokryttja">
            <datalist   id="pokryttja"  id="header_filter-64-standart-d" name="pokryttja"
            style="width: 100%" data-placeholder="(DIN, ГОСТ, AN, ISO)"
            data-allow-clear="true" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-64-standart-d">
                <option v-for="(item, index) of info.pokryttja" data-select2-id="72" :value="item">@{{item}}</option>
            </datalist>
                <!-- <select id="header_filter-13-pokryttja-d" name="13-pokryttja[]" multiple="" style="width: 100%"
                tabindex="-1" class="select2-hidden-accessible" aria-hidden="true"
                data-select2-id="header_filter-13-pokryttja-d">
                </select>
                <span class="select2 select2-container select2-container--default" dir="ltr"
                data-select2-id="77" style="width: 100%;">
                    <span class="selection">
                        <span class="select2-selection select2-selection--multiple" role="combobox"
                        aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false">
                        <ul class="select2-selection__rendered">
                            <li class="select2-search select2-search--inline">
                                <input class="select2-search__field" type="search" tabindex="0"
                                autocomplete="off" autocorrect="off" autocapitalize="none"
                                spellcheck="false" role="searchbox" aria-autocomplete="list"
                                placeholder="" style="">
                            </li>
                        </ul>
                        </span>
                    </span>
                    <span class="dropdown-wrapper" aria-hidden="true"></span>
                </span> -->
            </div>
        </div>
    <div class="submit">
        <button @click="handlerSubmit" class="hexa">Пошук</button>
    </div>
</form>


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
<div class="mobile-navbar">
    <nav class="navbar-grey">
        <div class="container flex navbar-items">
            <a href="#" class="shop hexa">@lang('global.header_menu_catalog') <i class="fas fa-plus" id="show-catalog-menu"></i></a>

            <div class="flex  hideTab">
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/about" class="hexa" style="margin-left: 7px;">@lang('global.header_menu_about_company')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/affiliate-program" class="hexa">@lang('global.header_menu_affiliate')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/blog" class="hexa">@lang('global.header_menu_blog')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/tender" class="hexa">@lang('global.header_menu_tender')</a>
                <a href="#" class="hexa">@lang('global.header_menu_sale')</a>
                <a href="https://dinmark.com.ua{{trans('global.url_prefix')}}/contacts" class="hexa" style="margin-right: 8px;">@lang('global.header_menu_contact')</a>
            </div>

        <nav class="mainmenu" style="display: none;">
	<div>
        <a href="https://dinmark.com.ua/shop/bolty">
            <div>
                <img src="https://dinmark.com.ua/images/groups/bolty.svg">
            </div>
            <div>Болти</div></a><div class="submenu">
            <a href="https://dinmark.com.ua/shop/bolty/bolty-z-shestyhrannou-holovkou">Болти з шестигранною головкою</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-vnutrishnim-shestryhrannykom">Болти з внутрішнім шестигранником</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-potaynou-holovkou">Болти з потайною головкою</a><a href="https://dinmark.com.ua/shop/bolty/bolty-t-podibni">Болти Т-подібні</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-kvadratnou-holovkou-pidholovnykom">Болти з квадратною головкою / підголовником</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-neylonovym-pokryttjam-din-267-28-klf">Болти з нейлоновим покриттям DIN 267-28 KLF</a><a href="https://dinmark.com.ua/shop/bolty/bolty-z-kilcem">Болти з кільцем</a></div></div><div><a href="https://dinmark.com.ua/shop/hayky"><div><img src="https://dinmark.com.ua/images/groups/hayky.svg"></div><div>Гайки</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/hayky/hayky-shestyhranni">Гайки шестигранні</a><a href="https://dinmark.com.ua/shop/hayky/hayky-samokontrjaschi">Гайки самоконтрящі</a><a href="https://dinmark.com.ua/shop/hayky/hayky-pryvarni">Гайки приварні</a><a href="https://dinmark.com.ua/shop/hayky/hayky-barashkovi">Гайки барашкові</a><a href="https://dinmark.com.ua/shop/hayky/hayky-koronchati">Гайки корончаті</a><a href="https://dinmark.com.ua/shop/hayky/hayky-kovpachkovi">Гайки ковпачкові</a><a href="https://dinmark.com.ua/shop/hayky/hayky-shlicevi">Гайки шліцеві</a><a href="https://dinmark.com.ua/shop/hayky/hayky-z-flancem">Гайки з фланцем</a><a href="https://dinmark.com.ua/shop/hayky/hayky-z-nakatkou">Гайки з накаткою</a><a href="https://dinmark.com.ua/shop/hayky/rym-hayky">Рим-гайки</a><a href="https://dinmark.com.ua/shop/hayky/presmasljanky">Пресмаслянки</a><a href="https://dinmark.com.ua/shop/hayky/zahlushky-probky-rizbovi">Заглушки / Пробки різьбові</a><a href="https://dinmark.com.ua/shop/hayky/vstavky-vtulky">Вставки / Втулки</a><a href="https://dinmark.com.ua/shop/hayky/inshi-hayky">Інші гайки</a></div></div><div><a href="https://dinmark.com.ua/shop/hvynty"><div><img src="https://dinmark.com.ua/images/groups/hvynty.svg"></div><div>Гвинти</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-potaynou-holovkou">Гвинти з потайною головкою</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-napivkruhlou-holovkou">Гвинти з напівкруглою головкою</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-barashkovi">Гвинти барашкові</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-ustanovochni">Гвинти установочні</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-napivpotaynou-holovkou">Гвинти з напівпотайною головкою</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-pryvarni">Гвинти приварні</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-antyvandalni">Гвинти антивандальні</a><a href="https://dinmark.com.ua/shop/hvynty/hvynty-z-nakatkou">Гвинти з накаткою</a><a href="https://dinmark.com.ua/shop/hvynty/inshi-hvynty">Інші гвинти</a></div></div><div><a href="https://dinmark.com.ua/shop/samorizy"><div><img src="https://dinmark.com.ua/images/groups/samorizy.svg"></div><div>Саморізи</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-potaynou-holovkou">Саморізи з потайною головкою</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-napivpotaynou-holovkou">Саморізи з напівпотайною головкою</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-shestyhrannou-holovkou">Саморізи з шестигранною головкою</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-napivkruhlou-holovkou">Саморізи з напівкруглою головкою</a><a href="https://dinmark.com.ua/shop/samorizy/samorizy-z-burom">Саморізи з буром</a></div></div><div><a href="https://dinmark.com.ua/shop/shayby"><div><img src="https://dinmark.com.ua/images/groups/shayby.svg"></div><div>Шайби</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shayby/shayby-ploski">Шайби плоскі</a><a href="https://dinmark.com.ua/shop/shayby/shayby-kosi">Шайби косі</a><a href="https://dinmark.com.ua/shop/shayby/shayby-pruzhynni">Шайби пружинні</a><a href="https://dinmark.com.ua/shop/shayby/shayby-nord-lock">Шайби Nord-Lock</a><a href="https://dinmark.com.ua/shop/shayby/shayby-stoporni">Шайби стопорні</a><a href="https://dinmark.com.ua/shop/shayby/shayby-starlock">Шайби Starlock</a><a href="https://dinmark.com.ua/shop/shayby/inshi-shayby">Інші шайби</a><a href="https://dinmark.com.ua/shop/shayby/stoporni-kilcja">Стопорні кільця</a></div></div><div><a href="https://dinmark.com.ua/shop/shurupy"><div><img src="https://dinmark.com.ua/images/groups/shurupy.svg"></div><div>Шурупи</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-potaynou-holovkou">Шурупи з потайною головкою</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-napivkruhlou-holovkou">Шурупи з напівкруглою головкою</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-shestyhrannou-holovkou">Шурупи з шестигранною головкою</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-napivpotaynou-holovkou">Шурупи з напівпотайною головкою</a><a href="https://dinmark.com.ua/shop/shurupy/shurupy-z-kilcem-hachkom">Шурупи з кільцем / гачком</a></div></div><div><a href="https://dinmark.com.ua/shop/zaklepky"><div><img src="https://dinmark.com.ua/images/groups/zaklepky.svg"></div><div>Заклепки</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/zaklepky/zaklepky-vytjazhni">Заклепки витяжні</a><a href="https://dinmark.com.ua/shop/zaklepky/zaklepky-pid-molotok">Заклепки під молоток</a><a href="https://dinmark.com.ua/shop/zaklepky/klepalni-hayky">Клепальні гайки</a></div></div><div><a href="https://dinmark.com.ua/shop/shpylky-rizbovi"><div><img src="https://dinmark.com.ua/images/groups/shpylky-rizbovi.svg"></div><div>Шпильки різьбові</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-975-shpylka-rizbova">DIN 975 Шпилька різьбова</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-975-shpylka-z-trapecepodibnou-rizbou">DIN 975 Шпилька з трапецеподібною різьбою</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-938-shpylka-rizbova-z-dopuskom-1d">DIN 938 Шпилька різьбова з допуском 1d</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-835-shpylka-rizbova-z-dopuskom-2d">DIN 835 Шпилька різьбова з допуском 2D</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-2510-nf-shpylka-dlja-flancevykh-zednan">DIN 2510 NF Шпилька для фланцевих з'єднань</a><a href="https://dinmark.com.ua/shop/shpylky-rizbovi/din-939-shpylka-rizbova-z-dopuskom-125d">DIN 939 Шпилька різьбова з допуском 1,25d</a></div></div><div><a href="https://dinmark.com.ua/shop/shtifty"><div><img src="https://dinmark.com.ua/images/groups/shtifty.svg"></div><div>Штіфти</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shtifty/shtifty-konusni">Штіфти конусні</a><a href="https://dinmark.com.ua/shop/shtifty/shtifty-cylindrychni">Штіфти циліндричні</a><a href="https://dinmark.com.ua/shop/shtifty/shponky">Шпонки</a></div></div><div><a href="https://dinmark.com.ua/shop/shplinty"><div><img src="https://dinmark.com.ua/images/groups/shplinty.svg"></div><div>Шплінти</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/shplinty/an-75-shplint-pruzhynnyy-holchastyy-forma-e">AN 75 Шплінт пружинний голчастий форма E</a><a href="https://dinmark.com.ua/shop/shplinty/an-77-shplint-z-kilcem-pruzhynnyy">AN 77 Шплінт з кільцем пружинний</a><a href="https://dinmark.com.ua/shop/shplinty/din-94-shplint-rozvidnyy">DIN 94 Шплінт розвідний</a><a href="https://dinmark.com.ua/shop/shplinty/art-7383-kilce-shplint">ART 7383 Кільце-шплінт</a><a href="https://dinmark.com.ua/shop/shplinty/an-76-shplint-pruzhynnyy-holchastyy-forma-d">AN 76 Шплінт пружинний голчастий форма D</a><a href="https://dinmark.com.ua/shop/shplinty/din-11024-shplint-pruzhynnyy-forma-d">DIN 11024 Шплінт пружинний Форма D</a><a href="https://dinmark.com.ua/shop/shplinty/din-11024-shplint-pruzhynnyy-forma-e">DIN 11024 Шплінт пружинний Форма E</a></div></div><div><a href="https://dinmark.com.ua/shop/ankery"><div><img src="https://dinmark.com.ua/images/groups/ankery.svg"></div><div>Анкери, Дюбелі</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/ankery/an-214-anker-v-beton-z-vnutrishnou-rizbou">AN 214 Анкер в бетон з внутрішньою різьбою</a><a href="https://dinmark.com.ua/shop/ankery/an-215-anker-zabyvnyy">AN 215 Анкер забивний</a><a href="https://dinmark.com.ua/shop/ankery/an-217-anker-klynovyy">AN 217 Анкер клиновий</a><a href="https://dinmark.com.ua/shop/ankery/an-228-anker-zakrytyy-z-potaynou-holovkou">AN 228 Анкер закритий з потайною головкою</a><a href="https://dinmark.com.ua/shop/ankery/an-253-anker-molly">AN 253 Анкер MOLLY</a><a href="https://dinmark.com.ua/shop/ankery/an-257-anker-etaf">AN 257 Анкер ETAF</a><a href="https://dinmark.com.ua/shop/ankery/an-305-anker-dlja-fiksacii">AN 305 Анкер для фіксації</a><a href="https://dinmark.com.ua/shop/ankery/an-233-hvynt-hx-po-betonu">AN 233 Гвинт HX по бетону</a><a href="https://dinmark.com.ua/shop/ankery/an-234-hvynt-turbo-">AN 234 Гвинт TURBO </a><a href="https://dinmark.com.ua/shop/ankery/an-260-strubcyna-dlja-montazhu-na-stalnykh-balkakh">AN 260 Струбцина для монтажу на стальних балках</a><a href="https://dinmark.com.ua/shop/ankery/an-237-dubel-dlja-betonu-z-shurupom-">AN 237 Дюбель для бетону з шурупом </a><a href="https://dinmark.com.ua/shop/ankery/an-245-dubel-dlja-cehly">AN 245 Дюбель для цегли</a><a href="https://dinmark.com.ua/shop/ankery/an-246-dubel-dlja-cehly-iz-shurupom">AN 246 Дюбель для цегли із шурупом</a><a href="https://dinmark.com.ua/shop/ankery/an-289-dubel-plastykovyy-dlja-izoljacii">AN 289 Дюбель пластиковий для ізоляції</a><a href="https://dinmark.com.ua/shop/ankery/an-301-dubel-neylonovyy-dlja-betonu">AN 301 Дюбель нейлоновий для бетону</a><a href="https://dinmark.com.ua/shop/ankery/an-303-dubel-dlja-betonu">AN 303 Дюбель для бетону</a><a href="https://dinmark.com.ua/shop/ankery/an-304-dubel-polipropilenovyy-dlja-betonu">AN 304 Дюбель поліпропіленовий для бетону</a><a href="https://dinmark.com.ua/shop/ankery/an-410-dubel-z-hvyntom-i-shestyhrannou-holovkou">AN 410 Дюбель з гвинтом і шестигранною головкою</a></div></div><div><a href="https://dinmark.com.ua/shop/khomuty"><div><img src="https://dinmark.com.ua/images/groups/khomuty.svg"></div><div>Хомути</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/khomuty/din-3016-khomut-z-pidkladkou-epdm">DIN 3016 Хомут з підкладкою EPDM</a><a href="https://dinmark.com.ua/shop/khomuty/din-3017-1-khomut-chervjachnyy">DIN 3017-A Хомут черв'ячний</a><a href="https://dinmark.com.ua/shop/khomuty/din-3017-khomut-sylovyy-c1">DIN 3017-C Хомут силовий</a><a href="https://dinmark.com.ua/shop/khomuty/art-7354-khomut">ART 7354 Хомут</a><a href="https://dinmark.com.ua/shop/khomuty/din-3567">DIN 3567 Хомут трубний</a><a href="https://dinmark.com.ua/shop/khomuty/din-3570-khomut-u-podibnyy">DIN 3570 Хомут U-подібний</a><a href="https://dinmark.com.ua/shop/khomuty/art-7460-khomut">ART 7460 Хомут</a><a href="https://dinmark.com.ua/shop/khomuty/an-114-kabelna-stjazhka">AN 114 Кабельна стяжка</a><a href="https://dinmark.com.ua/shop/khomuty/art-7461-khomut">ART 7461 Хомут</a></div></div><div><a href="https://dinmark.com.ua/shop/takelazh"><div><img src="https://dinmark.com.ua/images/groups/takelazh.svg"></div><div>Такелаж</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/takelazh/trosy-">Троси </a><a href="https://dinmark.com.ua/shop/takelazh/lancuhy">Ланцюги</a><a href="https://dinmark.com.ua/shop/takelazh/talrepy">Талрепи</a><a href="https://dinmark.com.ua/shop/takelazh/karabiny">Карабіни</a><a href="https://dinmark.com.ua/shop/takelazh/zazhymy-dlja-trosu">Зажими для тросу</a><a href="https://dinmark.com.ua/shop/takelazh/skoby">Скоби</a></div></div><div><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht"><div><img src="https://dinmark.com.ua/images/groups/osnastka-dlja-jakht.svg"></div><div>Оснастка для яхт</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/okovky-i-kronshteyny">Оковки і кронштейни</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/takelazhni-bloky">Такелажні блоки</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/nakonechnyky">Наконечники</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/utky">Утки</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/knekhty-i-kluzy">Кнехти і клюзи</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/haky">Гаки</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/petli">Петлі</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/zamky-">Замки </a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/obushky">Обушки</a><a href="https://dinmark.com.ua/shop/osnastka-dlja-jakht/inshe">Інше</a></div></div><div><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley"><div><img src="https://dinmark.com.ua/images/groups/kriplennja-dlja-montazhu-sonjachnykh-paneley.svg"></div><div>Кріплення для монтажу сонячних панелей</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9211-hvynt-shurup-kombinovanyy-(santekhshpylka)">ART 9211 Гвинт-шуруп комбінований (сантехшпилька)</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-601-hayka-dlja-profiliv">AN 601 Гайка для профілів</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-602-hayka-pryzhymna-dlja-profilja">AN 602 Гайка прижимна для профіля</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-604-hayka-pryzhymna-dlja-profilja">AN 604  Гайка прижимна для профіля</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-606-hayka-pryzhymna-dlja-profilja">AN 606 Гайка прижимна для профіля</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9082-hvynt-shurup-kombinovanyy-(santekhshpylka)">ART 9082 Гвинт-шуруп комбінований (сантехшпилька)</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9082-shayba-epdm-">ART 9082 Шайба EPDM </a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-607-hayka-pryzhymna-dlja-profilja">AN 607 Гайка прижимна для профіля</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/an-612-bolt-t-podibnyy">AN 612 Болт Т-подібний</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-613-hayka-kvadratna">ART 613 Гайка монтажна</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9521-kronshteyn-kriplennja-dlja-sonjachnykh-system">ART 9521 Кронштейн кріплення для сонячних систем</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9525-kronshteyn-kriplennja-dlja-sonjachnykh-system">ART 9525 Кронштейн кріплення для сонячних систем</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9543-plastyna-perekhidna">ART 9543 Пластина перехідна</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9583-zazhym-pid-kutom">ART 9583 Зажим під кутом</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9583-zatyskach-dlja-shviv-pid-kutom">ART 9583 Затискач для швів під кутом</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9742-modul-dlja-skla-aluf22-epdm">ART 9742 Модуль для скла AluF22 EPDM</a><a href="https://dinmark.com.ua/shop/kriplennja-dlja-montazhu-sonjachnykh-paneley/art-9745-modul-dlja-skla-aluf22-epdm">ART 9745 Модуль для скла AluF22 EPDM</a></div></div><div><a href="https://dinmark.com.ua/shop/duymove-kriplennja"><div><img src="https://dinmark.com.ua/images/groups/duymove-kriplennja.svg"></div><div>Дюймове кріплення</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-934-hayka-shestyhranna-z-duymovou-rizbou">DIN 934 Гайка шестигранна з дюймовою різьбою</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-933-109-uns-bolt-z-shestyhrannou-holovkou-i-povnou-rizbou-duymovou-rizbou">DIN 933 Болт з шестигранною головкою і повною різьбою, дюймовою різьбою</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-931-bolt-z-shestyhrannou-holovkou-i-chastkovou-rizbou-duymovou-rizbou">DIN 931 Болт з шестигранною головкою і частковою різьбою, дюймовою різьбою</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-912-bolt-z-cylindrychnou-holovkou-i-vnutrishnim-shestyhrannykom-z-duymovou-rizbou">DIN 912 Болт з циліндричною головкою і внутрішнім шестигранником з дюймовою різьбою</a><a href="https://dinmark.com.ua/shop/duymove-kriplennja/din-975-shpylka-rizbova-z-duymovou-rizbou">DIN 975 Шпилька різьбова з дюймовою різьбою</a></div></div><div><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja"><div><img src="https://dinmark.com.ua/images/groups/konstrukciyne-kriplennja.svg"></div><div>Конструкційне кріплення</div></a><div class="submenu"><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-6914-bolt-vysokomicnyy-z-shestyhrannou-holovkou">DIN 6914 Болт високоміцний з шестигранною головкою</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-6915-hayka-vysokomicna-shestyhranna">DIN 6915 Гайка високоміцна шестигранна</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-6916-shayba-vysokomicna">DIN 6916 Шайба високоміцна</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/en-14399-4-komplekt-vysokomicnyy-hv">EN 14399-4 Комплект високоміцний HV</a><a href="https://dinmark.com.ua/shop/konstrukciyne-kriplennja/din-186-46-bolt-t-podibnyy-din-980">DIN 7999 Болт високоміцний з шестигранною головкою</a></div></div><div><a href="https://dinmark.com.ua/shop/kljaymery"><div><img src="https://dinmark.com.ua/images/groups/kljaymery.svg"></div><div>Кляймери</div></a></div></nav>        </div>
    </nav>

</div>
<div class="filter--wrapper">


<form action="https://dinmark.com.ua/search/filter" id="mobile-filter"
class="hide" style="display: block;">
    <div class="container flex">
        <div><p>Стандарт (DIN, ГОСТ, AN, ISO)</p>
            <select id="header_filter-64-standart" name="64-standart"
            style="width: 100%" data-placeholder="(DIN, ГОСТ, AN, ISO)"
            data-allow-clear="true" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-64-standart">
                <option data-select2-id="72"></option>
            </select>
            <span class="select2 select2-container select2-container--default"
            dir="ltr" data-select2-id="71" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--single"
                    role="combobox" aria-haspopup="true"
                    aria-expanded="false" tabindex="0"
                    aria-disabled="false"
                    aria-labelledby="select2-header_filter-64-standart-container">
                        <span class="select2-selection__rendered"
                        id="select2-header_filter-64-standart-container"
                        role="textbox" aria-readonly="true">
                            <span class="select2-selection__placeholder">(DIN, ГОСТ, AN, ISO)
                            </span>
                        </span>
                        <span class="select2-selection__arrow" role="presentation">
                            <b role="presentation"></b>
                        </span>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span>
         </div>
         <div>
            <p>Діаметр (мм)</p>
            <select id="header_filter-7-diametr" name="7-diametr[]" multiple=""
                style="width: 100%" tabindex="-1" class="select2-hidden-accessible"
                aria-hidden="true" data-select2-id="header_filter-7-diametr">
            </select>
            <span class="select2 select2-container select2-container--default"
                dir="ltr" data-select2-id="73" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple"
                        role="combobox" aria-haspopup="true" aria-expanded="false"
                        tabindex="-1" aria-disabled="false">
                        <ul class="select2-selection__rendered">
                            <li class="select2-search select2-search--inline">
                                <input class="select2-search__field" type="search"
                                    tabindex="0" autocomplete="off" autocorrect="off"
                                    autocapitalize="none" spellcheck="false"
                                    role="searchbox" aria-autocomplete="list"
                                    placeholder="" style="">
                            </li>
                        </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span>
        </div>
        <div>
            <p>Довжина (мм)</p>
            <select id="header_filter-26-dovzhyna" name="26-dovzhyna[]"
                multiple="" style="width: 100%" tabindex="-1"
                class="select2-hidden-accessible" aria-hidden="true"
                data-select2-id="header_filter-26-dovzhyna">
            </select>
            <span class="select2 select2-container select2-container--default"
            dir="ltr" data-select2-id="74" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple"
                    role="combobox" aria-haspopup="true" aria-expanded="false"
                    tabindex="-1" aria-disabled="false">
                    <ul class="select2-selection__rendered">
                        <li class="select2-search select2-search--inline">
                        <input class="select2-search__field" type="search" tabindex="0"
                        autocomplete="off" autocorrect="off" autocapitalize="none"
                        spellcheck="false" role="searchbox" aria-autocomplete="list"
                        placeholder="" style="">
                        </li>
                    </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span>
        </div>
        <div>
            <p>Матеріал </p>
            <select id="header_filter-11-material" name="11-material[]"
            multiple="" style="width: 100%" tabindex="-1" class="select2-hidden-accessible"
            aria-hidden="true" data-select2-id="header_filter-11-material">
            </select>
            <span class="select2 select2-container select2-container--default" dir="ltr"
            data-select2-id="75" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple" role="combobox"
                    aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false">
                    <ul class="select2-selection__rendered">
                        <li class="select2-search select2-search--inline">
                        <input class="select2-search__field" type="search" tabindex="0" autocomplete="off"
                        autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox"
                        aria-autocomplete="list" placeholder="" style="">
                        </li>
                    </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span>
        </div>
        <div>
            <p>Клас міцності </p>
            <select id="header_filter-28-klas-micnosti" name="28-klas-micnosti[]" multiple=""
            style="width: 100%" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true"
            data-select2-id="header_filter-28-klas-micnosti">
            </select>
            <span class="select2 select2-container select2-container--default" dir="ltr"
            data-select2-id="76" style="width: 100%;">
                <span class="selection">
                    <span class="select2-selection select2-selection--multiple" role="combobox"
                    aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false">
                        <ul class="select2-selection__rendered">
                        <li class="select2-search select2-search--inline">
                        <input class="select2-search__field" type="search" tabindex="0" autocomplete="off"
                        autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox"
                        aria-autocomplete="list" placeholder="" style="">
                        </li>
                    </ul>
                    </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </span>
        </div>
        <div>
            <p>Покриття </p>
                <select id="header_filter-13-pokryttja" name="13-pokryttja[]" multiple="" style="width: 100%"
                tabindex="-1" class="select2-hidden-accessible" aria-hidden="true"
                data-select2-id="header_filter-13-pokryttja">
                </select>
                <span class="select2 select2-container select2-container--default" dir="ltr"
                data-select2-id="77" style="width: 100%;">
                    <span class="selection">
                        <span class="select2-selection select2-selection--multiple" role="combobox"
                        aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false">
                        <ul class="select2-selection__rendered">
                            <li class="select2-search select2-search--inline">
                                <input class="select2-search__field" type="search" tabindex="0"
                                autocomplete="off" autocorrect="off" autocapitalize="none"
                                spellcheck="false" role="searchbox" aria-autocomplete="list"
                                placeholder="" style="">
                            </li>
                        </ul>
                        </span>
                    </span>
                    <span class="dropdown-wrapper" aria-hidden="true"></span>
                </span>
            </div>
        </div>
    <div class="submit">
        <button class="hexa">Пошук</button>
    </div>
</form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="/assets/plugins/select2/dist/js/vue.min.js"></script>
<script>
    let filter = new Vue({
        el: "#header",
        data() {
            return {
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
              }
            }
        },
        methods: {
            removeQuery(index, array) {
              this.queryList[array].splice(index);
            },
            addQuery(param, prop) {
              let array = this.queryList[prop];
              let flag = 0;
              let hasParam = false;
              array.forEach(item => {
                  item == param ? flag++ : false;
                });

              if(param.length) {    
                flag === 0 ? array.push(param) : false;
              }
            },
            handlerSubmit(event) {
                let input = event.target.value;
				let active = this.queryList.active = event.target.list.id;
                const param = this.queryList;
                let queryStr = `?standart=${param.standart}&diametr=${param.diametr}&dovzhyna=${param.dovzhyna}&material=${param.material}&klas_micnosti=${param.klas_micnosti}&pokryttja=${param.pokryttja}&active=${param.active}`;

				fetch('{{route('extendedSearch')}}' + queryStr).then(res => !!res ? res.json() : false).then(arr => this.info[active] = arr);
            }
        }
    });
</script>
<script>
    const burger = document.querySelector(".sidebar-minify-btn.navbar-toggle");
    const closeBurger = document.querySelector("#close-burger-menu");
    const toggleBurger = (el) => {
        el.classList.toggle("hide");
    }
    const listenClick1 = burger.addEventListener('click', ()=> {
        toggleBurger(burger);
    })
    const listenClick2 = closeBurger.addEventListener('click', ()=> {
        toggleBurger(burger);
    })
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

            $('#global_search').select2({
                placeholder: "@lang('global.global_search.placeholder')",
                minimumInputLength: 3,
                ajax: {
                    url: function () {
                        return '{{route('globalsearch')}}'
                    },
                    dataType: 'json',
                    data: function (params) {
                        return {
                            name: params.term,
														onlywithstorage: '0'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: [
                                {
                                    "text": "@lang('global.global_search.article')",
                                    "children" : data['products']
                                },
                                {
                                    "text": "@lang('global.global_search.order')",
                                    "children" : data['orders']
                                },
                                {
                                    "text": "@lang('global.global_search.implementation')",
                                    "children" : data['implementations']
                                },
                                {
                                    "text": "@lang('global.global_search.reclamation')",
                                    "children" : data['reclamations']
                                },

                            ],
                        };
                    },
                    cache: true
                },
            });
        });
    })(jQuery);
</script>
