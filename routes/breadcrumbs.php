<?php
/**
 * Created by PhpStorm.
 * User: Polmain
 * Date: 19.03.2020
 * Time: 15:37
 */

Breadcrumbs::for('home', function ($trail) {
	$trail->push(trans('dashboard.page_name'), route('home'));
});

Breadcrumbs::for('user.profile', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('user.edit_page_name'), route('user.profile'));
});

Breadcrumbs::for('user.log', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('user.log_page_name'), route('user.log'));
});

Breadcrumbs::for('company', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('company.edit_page_name'), route('company'));
});

Breadcrumbs::for('product.all', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('product.all_page_name'), route('products'));
});

Breadcrumbs::for('product.categories', function ($trail, $categories) {
	$trail->parent('product.all');
	foreach ($categories as $category){
		$trail->push($category['name'], route('products.category',$category['id']));
	}
});

Breadcrumbs::for('product.show', function ($trail, $product, $name) {
	$trail->parent('product.all');
	$trail->push($name, route('products.show',[$product->id]));
});

Breadcrumbs::for('catalogs', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('wishlist.page_list'), route('catalogs'));
});

Breadcrumbs::for('ticket', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('ticket.page_name'), route('ticket'));
});

Breadcrumbs::for('ticket.create', function ($trail) {
	$trail->parent('ticket');
	$trail->push(trans('ticket.create_page_name'), route('ticket.create'));
});

Breadcrumbs::for('ticket.show', function ($trail, $ticket) {
	$trail->parent('ticket');
	$trail->push(trans('ticket.dialog').': '.$ticket->subject, route('ticket.show',[$ticket->id]));
});

Breadcrumbs::for('notification', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('notification.page_name'), route('notification'));
});

Breadcrumbs::for('admin.user.change_data', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('admin_user.change_data_page_name'), route('admin.user.change_request'));
});