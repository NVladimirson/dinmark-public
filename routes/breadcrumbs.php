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

Breadcrumbs::for('product.all', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('product.all_page_name'), route('products'));
});

Breadcrumbs::for('chat', function ($trail) {
	$trail->parent('home');
	$trail->push(trans('chat.page_name'), route('chat'));
});

Breadcrumbs::for('chat.create', function ($trail) {
	$trail->parent('chat');
	$trail->push(trans('chat.create_page_name'), route('chat.create'));
});

Breadcrumbs::for('chat.show', function ($trail, $chat) {
	$trail->parent('chat');
	$trail->push(trans('chat.dialog').': '.$chat->subject, route('chat.show',[$chat->id]));
});