@extends('layouts.default', ['contentFullWidth' => true])

@section('title', 'Form Elements')
@push('css')
    <link href="/assets/plugins/superbox/superbox.min.css" rel="stylesheet" />
    <link href="/assets/plugins/lity/dist/lity.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div id="wrapProfile" @click="randomClick">
        <div class="profile" style="margin-top: -1px">
            <div class="profile-header">
                <!-- BEGIN profile-header-cover -->
                <div class="profile-header-cover"></div>
                <!-- END profile-header-cover -->
                <!-- BEGIN profile-header-content -->
                <div class="profile-header-content">
                    <!-- BEGIN profile-header-img -->
                    <div class="profile-header-img">
                        @if(auth()->user()->photo)
                            <img src="https://dinmark.com.ua/images/profile/{{auth()->user()->photo}}" alt="{{auth()->user()->name}}" />
                        @else
                            <img src="https://dinmark.com.ua/images/empty-avatar.png" alt="{{auth()->user()->name}}" />
                        @endif
                    </div>

                    <!-- END profile-header-img -->
                    <!-- BEGIN profile-header-info -->
                    <div class="profile-header-info">
                        <h4 class="mt-0 mb-1">{{auth()->user()->name}}</h4>
                        <p class="mb-2">{{auth()->user()->getCompany->name}}</p>
                        <p class="mb-0"><strong>@lang('sidebar.manager'):</strong> {{auth()->user()->getCompany->getManager->name}}</p>
                    </div>
                    <!-- END profile-header-info -->
                </div>
            </div>
        </div>

        <h1 class="page-header">@lang('user.edit_page_name')</h1>
        <div class="profile-content p-t-0">
            <div class="row">
            <div class="col-xl-6">
                <!-- begin panel -->

                <div class="panel panel-primary">
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">@lang('user.edit_personal_data')</h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <form action="{{route('user.profile.update_data')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">@lang('user.edit_name')</label>
                                <div class="col-md-9">
                                    <input type="text" name="name" class="form-control m-b-5 @error('name') is-invalid @enderror" placeholder="@lang('user.edit_name')" value="{{auth()->user()->name}}"/>
                                    @error('name')
                                    <span class="invalid-feedback " role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">@lang('user.edit_birthday')</label>
                                <div class="col-lg-9">
                                    <input type="text" name="birthday" class="form-control @error('birthday') is-invalid @enderror" id="datepicker-default" placeholder="@lang('user.edit_birthday')" autocomplete="off"  value="{{ (auth()->user()->info->firstWhere('field','birthday'))? auth()->user()->info->firstWhere('field','birthday')->value : '' }}"/>
                                    @error('birthday')
                                    <span class="invalid-feedback " role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="input-group mb-3 @error('photo') is-invalid @enderror">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="uploadPhotoAddon">@lang('user.edit_photo')</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="photo" class="custom-file-input @error('photo') is-invalid @enderror" id="uploadPhoto" aria-describedby="uploadPhotoAddon">
                                        <label class="custom-file-label" for="uploadPhoto">@lang('user.edit_select_photo')</label>
                                    </div>
                                </div>
                                @error('photo')
                                <span class="invalid-feedback " role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('user.edit_save')</button>
                        </form>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <div class="col-xl-6">
                <div class="panel panel-primary">
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">@lang('user.edit_password_block')</h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <form action="{{route('user.profile.update_password')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">@lang('user.edit_password')</label>
                                <div class="col-md-9">
                                    <input type="password" name="password" class="form-control m-b-5 @error('password') is-invalid @enderror" placeholder="@lang('user.edit_password')" />
                                    @error('password')
                                    <span class="invalid-feedback " role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">@lang('user.edit_password_repeat')</label>
                                <div class="col-lg-9">
                                    <input type="password" name="password_confirmation" class="form-control m-b-5" placeholder="@lang('user.edit_password_repeat')" />
                                </div>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('user.edit_save')</button>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-xl-6">
                <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('user.edit_send_request_data')</h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <form action="{{route('user.profile.change_request')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('user.edit_email')</label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control m-b-5 @error('email') is-invalid @enderror" placeholder="@lang('user.edit_email')" value="{{auth()->user()->email}}"/>
                                <small class="f-s-12 text-grey-darker">@lang('user.edit_email_note')</small>
                                @error('email')
                                <span class="invalid-feedback " role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('user.edit_phone')</label>
                            <div class="col-md-9">
                                <input type="text" name="phone" class="form-control m-b-5 @error('phone') is-invalid @enderror" placeholder="@lang('user.edit_phone')"  value="{{ (auth()->user()->info->firstWhere('field','phone'))? auth()->user()->info->firstWhere('field','phone')->value : '' }}"/>
                                @error('phone')
                                <span class="invalid-feedback " role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary m-r-5 m-b-15">@lang('user.edit_request')</button>
                    </form>
                </div>
            </div>
            </div>
            <div class="col-xl-6">
                <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('user.addresses')</h4>
                </div>
                </div>
                <div class="panel-body">
                <div>
                    <div v-if="deliveryAdress.length">
                        <label for="savedAdress">Вибрати збережену адресу доставки</label>
                        <select class="form-control mb-3" id="savedAdress">
                            <option v-for="adress of deliveryAdress" value="adress">@{{ adress }}</option>
                        </select>
                    </div>
                    <p class="col-form-label col-md-3" v-else>Збережених адресів немає</p>
                </div>
                <div v-if="!createAdress" class="mt-3 d-flex justify-content-around">
                    <button @click="createAdress = !createAdress"  class="btn btn-green mb-3">Додати нову адресу</button>
                    <button @click="" class="btn btn-primary mb-3">Зберегти обране</button>
                </div>
                    <form @submit.prevent="addAdress" action="" v-else id="form-adress" method="post" enctype="multipart/form-data">
                    <h4>Створити адресу доставки</h4>
                        <ul id="nova_poshta_tab" class="nav nav-pills">
                            <li @click="toggleTypeDelivery = true; reset()" class="nav-item col p-0 text-center">
                                <a href="#wherhouse-tab" data-toggle="tab" class="nav-link"><span>На відділення</span></a>
                            </li>
                            <li @click="toggleTypeDelivery = false; reset()" class="nav-item col p-0 text-center">
                                <a href="#curier-tab" data-toggle="tab" class="nav-link active"><span>Кур'єром</span></a>
                            </li>
                        </ul>

                        <div v-if="toggleTypeDelivery" class="tab-pane warehouse">

                            <div class="m-b-5">
                                <label class="m-b-0">Введіть населений пункт</label>
                                <input required id="searchCity" type="text" class="form-control m-b-5" @input="searchCity" v-model="city" placeholder="Введіть населений пункт">
                                <div v-show="citiesResult.length" class="wrap-select">
                                    <div class="city-select">
                                        <div @click="city = cityName.Present; selectCity(cityName.Ref)" v-for="cityName of citiesResult" class="city-item">@{{ cityName.Present }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="m-b-5">
                                <label class="m-b-0">Адреса відділення</label>
                                <input required id="searchStreet" @click="searchWarehouse" v-model="street" type="text" class="form-control m-b-5" placeholder="Номер відділення/вулиця">
                                <div v-show="streetsResult.length" class="wrap-select">
                                    <div class="city-select">
                                        <div @click="street = warehouse.Description" class="city-item" v-for="warehouse of streetsResult.filter(filterWarehouse)">@{{ warehouse.Description }}</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div v-else class="tab-pane curier">

                            <div class="m-b-5">
                                <label class="m-b-0">Введіть населений пункт</label>
                                <input required id="searchCity" type="text" class="form-control m-b-5" @input="searchCity" v-model="city" placeholder="Введіть населений пункт">
                                <div v-show="citiesResult.length" class="wrap-select">
                                    <div class="city-select">
                                        <div @click="city = cityName.Present; selectCity(cityName.Ref)" v-for="cityName of citiesResult" class="city-item">@{{ cityName.Present }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="m-b-5">
                                <label class="m-b-0">Адреса доставки</label>
                                <input required id="searchStreet" @input="searchStreets" v-model="street" type="text" class="form-control m-b-5" placeholder="Вулиця, квартал">
                                <div v-show="streetsResult.length" class="wrap-select">
                                    <div class="city-select">
                                        <div @click="street = streetDel.Present" class="city-item" v-for="streetDel of streetsResult">@{{ streetDel.Present }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="m-b-5">
                                <label class="m-b-0">Номер будинку / і квартири</label>
                                <input v-model="house" required type="text" class="form-control m-b-5" placeholder="Номер будинку / і квартири">
                            </div>

                        </div>
                        
                        <div class="mt-3 d-flex justify-content-around">
                                <button class="btn btn-primary">Зберегти</button>
                                <button @click.prevent="createAdress = false; reset()" class="btn btn-danger">Скасувати</button>
                            </div>
                    </form>



                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-xl-6">
                @if(auth()->user()->export_key)
                <div class="panel panel-primary">
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">@lang('user.feeds')</h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <p>@lang('user.feed_message')</p>
                        <p><strong>@lang('user.feed_link_ua'):</strong> <a href="https://dinmark.com.ua/shop/export_prom?userKey={{auth()->user()->export_key}}" target="_blank">https://dinmark.com.ua/shop/export_prom?userKey={{auth()->user()->export_key}}</a></p>
                        <p><strong>@lang('user.feed_link_ru'):</strong> <a href="https://dinmark.com.ua/ru/shop/export_prom?userKey={{auth()->user()->export_key}}" target="_blank">https://dinmark.com.ua/ru/shop/export_prom?userKey={{auth()->user()->export_key}}</a></p>
                    </div>
                </div>
                @endif
            </div>
            </div>
        </div>
    </div>
    @endsection

@push('scripts')
    <script src="/assets/plugins/highlight.js/highlight.min.js"></script>
    <script src="/assets/js/demo/render.highlight.js"></script>
    <script src="/assets/plugins/select2/dist/js/vue.min.js"></script>
    <script>
        new Vue({
            el: '#wrapProfile',
            data: {
                createAdress: false,
                toggleTypeDelivery: false,
                city: '',
                street: '',
                house: '',
                citiesResult: [],
                streetsResult: [],
                deliveryAdress: [],
                elementRef: ''
                
            },
            methods: {
                addAdress() {
                    let adress = `${this.city} ${this.street} ${this.house}`;
                    this.deliveryAdress.push(adress);
                    this.reset();
                },
                filterWarehouse(w) {
                    if(typeof this.street === "number") {
                        return w.Number.includes(this.street);
                    } else if(typeof this.street === "string") {
                        return w.Description.toLowerCase().includes(this.street.toLowerCase());
                    } else {
                        return w;
                    }
                },
                searchWarehouse() {
                    let requestBody = {
                        apiKey: "f50ab08faaad28c3a612bf9e97fb1c8a",
                        modelName: "Address",
                        calledMethod: "getWarehouses",
                            methodProperties: {
                                SettlementRef: this.elementRef,
                                Limit: 99
                            } 
                        } 
                        fetch('https://api.novaposhta.ua/v2.0/json/', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(requestBody)
                        })
                        .then(response => response.json())
                        .then(results => this.streetsResult = results.data);
                },
                searchStreets() {
                    let requestBody = {
                        apiKey: "f50ab08faaad28c3a612bf9e97fb1c8a",
                        modelName: "Address",
                        calledMethod: "searchSettlementStreets",
                        methodProperties: {
                            StreetName: this.street,
                            SettlementRef: this.elementRef,
                            Limit: 99
                        }   
                    }
                    if(this.street.length > 2) {
                        fetch('https://api.novaposhta.ua/v2.0/json/', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(requestBody)
                    })
                    .then(response => response.json())
                    .then(results => this.streetsResult = results.data[0].Addresses);
                    }
                },
                selectCity(ref) {
                    this.elementRef = ref;
                },
                randomClick(event) {
                    if(event.target.id !== 'searchCity') {
                        this.citiesResult = [];
                    }  
                    if(event.target.id !== 'searchStreet') {
                        this.streetsResult = [];
                    } 
                },
                reset() {
                        this.citiesResult = [];
                        this.streetsResult = [];
                        this.city = '';
                        this.street = '';
                        this.house = '';
                },
                searchCity() {
                    let requestBody = {
                        apiKey: "f50ab08faaad28c3a612bf9e97fb1c8a",
                        modelName: "Address",
                            calledMethod: "searchSettlements",
                            methodProperties: {
                                CityName: this.city,
                                Limit: 99
                            }
                        }
                    if(this.city.length > 2) {
                        fetch('https://api.novaposhta.ua/v2.0/json/', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(requestBody)
                        })
                        .then(response => response.json())
                        .then(results => this.citiesResult = results.data[0].Addresses);
                    }
                }
            }
        })
    </script>
    <script>
		$('#datepicker-default').datepicker({
			todayHighlight: true
		});

		document.querySelector('.custom-file-input').addEventListener('change',function(e){
			var fileName = document.getElementById("uploadPhoto").files[0].name;
			var nextSibling = e.target.nextElementSibling
			nextSibling.innerText = fileName
		})

    </script>
@endpush
