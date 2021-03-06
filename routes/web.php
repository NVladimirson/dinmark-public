<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function() {

	Auth::routes(['register' => false]);
	Route::get('/login/as_site_user/','Auth\LoginController@LoginWithKey')->name('auth.login_key');

	Route::group(['middleware'=> ['auth','currentCompany','headerDebt','ticketSeidebar']],function() {

		Route::get('/', 'DashboardController@index')->name('home');
        Route::get('/globalSearch', 'DashboardController@globalSearch')->name('globalsearch');
				Route::get('/extendedSearch', 'DashboardController@extendedSearch')->name('extendedSearch');

		Route::get('/profile','UserController@profile')->name('user.profile');
		Route::post('/profile/data','UserController@updateData')->name('user.profile.update_data');
		Route::post('/profile/password','UserController@updatePassword')->name('user.profile.update_password');
		Route::post('/profile/change-request','UserController@chageRequest')->name('user.profile.change_request');
		Route::get('/profile/change-company/{id}','UserController@changeCompany')->name('user.change_company');
		Route::get('/log','UserController@log')->name('user.log');
		Route::get('/login/to_site','UserController@loginToSite')->name('auth.login_to_site');

		Route::get('company','CompanyController@index')->name('company');
		Route::post('/company/data','CompanyController@updateData')->name('company.update_data');
		Route::post('/company/payment-data','CompanyController@updatePaymentData')->name('company.update_payment_data');
		Route::post('/company/add-price','CompanyController@addPrice')->name('company.add_price');
		Route::post('/company/destroy-price/{id}','CompanyController@destroyPrice')->name('company.destroy_price');
		Route::post('/company/add-document','CompanyController@addDocument')->name('company.add_document');
		Route::post('/company/destroy-document/{id}','CompanyController@destroyDocument')->name('company.destroy_document');
		Route::post('/company/document-request','CompanyController@requestDocument')->name('company.request_document');
        Route::get('/company/users/ajax','CompanyController@usersAjax')->name('company.users_ajax');
        Route::post('/company/users/add','CompanyController@addUser')->name('company.users_add');
        Route::get('/company/login-as-user/{id}','CompanyController@loginAsUser')->name('company.login_as_user');

		Route::get('/products/test','Product\ProductController@test')->name('products.test');
		Route::get('/products','Product\ProductController@index')->name('products');
		Route::get('/products_v2','Product\ProductController@index_v2')->name('products_v2');
		Route::get('/products/category/{id}','Product\ProductController@category')->name('products.category');
		Route::get('/products/all-ajax','Product\ProductController@allAjax')->name('products.all_ajax');
		Route::get('/get-node-ajax/{id}', 'Product\ProductController@getNode')->name('getnode');
		Route::get('/products/option-filters','Product\ProductController@optionFilters')->name('products.optionfilters');
		Route::get('/products/search','Product\ProductController@search')->name('products.search');
		Route::get('/products/find','Product\ProductController@find')->name('products.find');
		Route::get('/products/{id}','Product\ProductController@show')->name('products.show');
		Route::get('/products/{id}/get-price','Product\ProductController@getPrice')->name('products.get_price');
        Route::get('/pricecalc', 'Product\ProductController@priceCalc')->name('priceCalc');

		Route::get('/catalogs/','Product\CatalogController@index')->name('catalogs');
		Route::post('/catalogs/','Product\CatalogController@store')->name('catalogs.store');
		Route::post('/catalogs/import','Product\CatalogController@import')->name('catalogs.import');
		Route::post('/catalogs/add-to-catalog/{id}','Product\CatalogController@addToCatalog')->name('catalogs.add_to_catalog');
		Route::get('/catalogs/change-catalog/{id}','Product\CatalogController@changeCatalog')->name('catalogs.change_catalog');
		Route::post('/catalogs/remove-to-catalog/{id}','Product\CatalogController@removeToCatalog')->name('catalogs.remove_to_catalog');
		Route::get('/catalogs/all-ajax/','Product\CatalogController@allAjax')->name('catalogs.all_ajax');
		Route::get('/catalogs/download-price/{id}','Product\CatalogController@downloadPrice')->name('catalogs.download_price');
		Route::post('/catalogs/{id}','Product\CatalogController@update')->name('catalogs.update');
		Route::post('/catalogs/destroy/{id}','Product\CatalogController@destroy')->name('catalogs.destroy');
		Route::post('/catalogs/change-article/{id}','Product\CatalogController@changeArticle')->name('catalogs.change_article');
		Route::post('/catalogs/set-price/{id}','Product\CatalogController@setPrice')->name('catalogs.change_article');
    // Route::get('/catalogs/add-to-catalog/{ids}','Product\CatalogController@addToCatalogMultiple')->name('catalogs.add_to_catalog_multiple');
		Route::get('/catalogs/change-storage/','Product\CatalogController@changeStorage')->name('catalogs.change_storage');

		Route::get('/orders','Order\OrderController@index')->name('orders');
		Route::get('/orders/create','Order\OrderController@create')->name('orders.create');
		Route::get('/orders/all-ajax/','Order\OrderController@allAjax')->name('orders.all_ajax');
		Route::get('/orders/find/','Order\OrderController@find')->name('orders.find');
		Route::get('/orders/total-data-ajax/','Order\OrderController@totalDataAjax')->name('orders.total_data_ajax');
		Route::post('/orders/add-to-order/{id}','Order\OrderController@addToOrder')->name('orders.add_to_order');
        Route::get('/orders/add-to-order-multiple','Order\OrderController@addToOrderMultiple')->name('catalogs.add_to_order_multiple');
		Route::post('/orders/remove-of-order/{id}','Order\OrderController@removeOfOrder')->name('orders.remove_of_order');
		Route::get('/orders/act-pdf','Order\OrderController@PDFAct')->name('orders.act_pdf');
		Route::get('/orders/{id}','Order\OrderController@show')->name('orders.show');
		Route::post('/orders/{id}','Order\OrderController@update')->name('orders.update');
        Route::get('/orders/{id}/copy','Order\OrderController@copy')->name('orders.copy');
		Route::get('/orders/{id}/bill','Order\OrderController@PDFBill')->name('orders.pdf_bill');
		Route::get('/orders/{id}/to-order','Order\OrderController@toOrder')->name('orders.to_order');
		Route::get('/orders/{id}/to-cancel','Order\OrderController@toCancel')->name('orders.to_cancel');

		Route::get('/implementations','Order\ImplementationController@index')->name('implementations');
		Route::get('/implementations/ajax','Order\ImplementationController@ajax')->name('implementations.ajax');
        Route::get('/implementations/total-data-ajax/','Order\ImplementationController@totalDataAjax')->name('implementations.total_data_ajax');
		Route::get('/implementations/find','Order\ImplementationController@find')->name('implementations.find');
		Route::get('/implementations/products/{id}','Order\ImplementationController@getProductsAjax')->name('implementations.products');
		Route::get('/implementations/pdf/{id}','Order\ImplementationController@generatePDF')->name('implementations.pdf');
		Route::get('/implementations/{id}','Order\ImplementationController@show')->name('implementations.show');

		Route::get('/reclamations','Order\ReclamationController@index')->name('reclamations');
		Route::get('/reclamations/ajax','Order\ReclamationController@ajax')->name('reclamations.ajax');
        Route::get('/reclamations/total-data-ajax/','Order\ReclamationController@totalDataAjax')->name('reclamations.total_data_ajax');
		Route::get('/reclamations/create','Order\ReclamationController@create')->name('reclamations.create');
		Route::get('/reclamations/update/{reclamation_id}','Order\ReclamationController@update')->name('reclamations.update');
		Route::post('/reclamations/create','Order\ReclamationController@store')->name('reclamations.store');
		Route::get('/reclamations/create/{implementation_id}','Order\ReclamationController@createByImplementation')->name('reclamations.store_implementation');
		Route::get('/reclamations/{id}','Order\ReclamationController@show')->name('reclamations.show');

        Route::get('/payments','Finance\PaymentController@index')->name('payments');
        Route::get('/payments/ajax','Finance\PaymentController@ajax')->name('payments.ajax');

        Route::get('/balance','Finance\BalanceController@index')->name('balance');
        Route::get('/balance/ajax','Finance\BalanceController@ajax')->name('balance.ajax');
        Route::get('/balance/ajax-total','Finance\BalanceController@totalDataAjax')->name('balance.ajax_total');

        Route::get('/documents','DocumentController@index')->name('documents');
        Route::get('/documents/get-feeds','DocumentController@getFeeds')->name('documents.get_feeds');

		Route::get('/clients','ClientController@index')->name('clients');
		Route::get('/clients/ajax','ClientController@ajax')->name('clients.ajax');
		Route::get('/clients/create','ClientController@create')->name('clients.create');
		Route::post('/clients/create','ClientController@store')->name('clients.store');
		Route::get('/clients/{id}','ClientController@edit')->name('clients.edit');
		Route::post('/clients/{id}','ClientController@update')->name('clients.update');
		Route::post('/clients/destroy/{id}','ClientController@destroy')->name('clients.destroy');

		Route::get('/news','NewsController@index')->name('news');
		Route::get('/news/{id}','NewsController@show')->name('news.show');

		Route::get('/faq','FAQController@index')->name('faq');

		Route::get('/tickets','TicketController@index')->name('ticket');
		Route::get('/tickets/ajax','TicketController@ajax')->name('ticket.ajax');
		Route::get('/tickets/create','TicketController@create')->name('ticket.create');
		Route::post('/tickets/create','TicketController@store')->name('ticket.store');
        Route::post('/tickets/explanation','TicketController@explanation')->name('ticket.explanation');
		Route::get('/tickets/{id}','TicketController@show')->name('ticket.show');
		Route::post('/tickets/{id}','TicketController@update')->name('ticket.update');
		Route::post('/tickets/{id}/change-status','TicketController@changeStatus')->name('ticket.change_status');



		Route::get('/notifications/','NotificationController@index')->name('notification');
		Route::get('/notifications/mark-read','NotificationController@markRead')->name('notification.mark_read');

		Route::get('/purchases','Purchase\PurchaseController@index')->name('purchases');
        Route::get('/purchases/get-ajax','Purchase\PurchaseController@tableDataAjax')->name('purchases.get_ajax');
				Route::get('/purchases/get-csv','Purchase\PurchaseController@getCSV')->name('purchases.getcsv');

	});
});
