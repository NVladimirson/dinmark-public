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

Breadcrumbs::for('order.all', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('order.page_list'), route('orders'));
});

Breadcrumbs::for('order.create', function ($trail) {
	$trail->parent('order.all');
	$trail->push(trans('order.page_create'), route('orders.create'));
});

Breadcrumbs::for('order.show', function ($trail, $order) {
	$trail->parent('order.all');
	$trail->push(trans('order.page_update').$order->id, route('orders.show',[$order->id]));
});

Breadcrumbs::for('implementations', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('implementation.page_list'), route('implementations'));
});

Breadcrumbs::for('reclamation.all', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('reclamation.page_list'), route('reclamations'));
});

Breadcrumbs::for('reclamation.create', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('reclamation.page_create'), route('reclamations.create'));
});

Breadcrumbs::for('client.all', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('client.page_list'), route('clients'));
});

Breadcrumbs::for('client.create', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('client.page_create'), route('clients.create'));
});

Breadcrumbs::for('client.edit', function ($trail, $client) {
	$trail->parent('home');
	$trail->push($client->name, route('clients.edit',[$client->id]));
});

Breadcrumbs::for('faq', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('faq.page_name'), route('faq'));
});