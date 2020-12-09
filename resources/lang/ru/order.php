<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */


    'modal_success' => 'Товар успешно добавлен в заказ',
    // 'modal_success_multiple' => '{1,21} :count товар успешно добавлен в заказ |
    // {2,3,4,22,23,24} :count товара успешно добавлен в заказ |
    // [{5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25},*] :count товаров успешно добавлен в заказ ',
    //'modal_success_multiple' => 'Товаров добавлено: :orders',
    'modal_success_multiple' => 'Товары успешно добавлены в заказ',
    'page_list' => 'Заказы',
    'all_tab_name' 	=> 'Список Заказов',
    'show_tab_name' 	=> 'Заказ',
    'select_status' 	=> 'Выберите статус',
    'filter_select_sender' 	=> 'Выберите отправителя',
    'filter_select_customer' 	=> 'Выберите получателя',

    'select_status_payment'     => 'Выберите статус оплаты',
    'payment_status_none'       => 'Не оплачено',
    'payment_status_partial'    => 'Частично оплачено',
    'payment_status_success'    => 'Полностью оплачено',

    'tab_name_order'        => 'Заказы',
    'tab_name_request'      => 'Заявки',
    'tab_name_archive'      => 'Архив',

    'btn_explanation'       => 'Уточненения по Заказу',
    'btn_explanation_implementation'       => 'Уточненения по Реализации',
    'btn_explanation_reclamation'       => 'Уточненения по Возврату',
    'explanation_message'   => 'Сообщение',
    'explanation_submit'    => 'Отправить',
    'explanation_subject_order'    => 'Уточнение по заказу ',
    'explanation_subject_implementation'    => 'Уточнение по реализации ',
    'explanation_subject_reclamation'    => 'Уточнение по возврату ',
    'explanation_edit_reclamation'    => 'Изменение возврата',
    'explanation_success'    => 'Запрос на уточнение успешно отправлен ',

    'btn_add_order'                 => 'Создать заявку',
    'table_header_number' 			=> 'Номер',
    'table_header_date' 			=> 'Дата',
    'table_header_status' 			=> 'Статус',
    'table_header_status_payment' 	=> 'Статус Оплаты',
    'table_header_total' 			=> 'Сумма',
    'table_header_customer' 		=> 'Отправитель',
    'table_header_user' 			=> 'Получатель',

    'table_footer_pc'               => 'Кл-во:',
    'table_footer_total'            => 'Сумма:',
    'table_footer_discount'         => 'Скидки:',
    'table_footer_payed'            => 'Оплачено:',
    'table_footer_not_payed'        => 'Не оплачено:',

    'table_header_date_create'      => 'Дата создания',
    'table_header_date_update'      => 'Дата последнего изменения',
    'table_header_shipping_method'  => 'Способ доставки',
    'table_header_client'           => 'Клиент',
    'table_header_manager'          => 'Менеджер',

    'table_shipping_method'         => 'Способ доставки',
    'table_shipping_city'           => 'Населенный пункт',
    'table_shipping_warehouse'      => 'Отделение',
    'table_shipping_address'        => 'Адрес',
    'table_shipping_house_float'    => 'Номер дома / и квартиры',
    'table_shipping_address_me'      => 'Номер / адрес отделения',

	'page_create' 				=> 'Новый заказ',
	'page_update' 				=> 'Заказ ',
	'order_number'				=> 'Заявка №',
    'implementation_number'		=> 'Отправление',
    'payment_number'	    	=> 'Оплата',
    'table_payment_date'    	=> 'Дата платежа',
    'table_payment_total'	   	=> 'Сумма',
	'from'						=> 'от',
	'btn_new_request'			=> 'Сохранить Заявку',
	'btn_new_order'				=> 'Оформить Заказ',
	'btn_open_order'			=> 'Возобновить',
	'btn_copy'			        => 'Повторить',
	'btn_cancel_order'			=> 'Отменить',
	'btn_cancel_close'			=> 'Закрыть',
	'select_sender'				=> 'Отправитель',
	'sender_dinmark'			=> 'Dinmark',
	'select_customer'			=> 'Получатель',
	'select_customer_user'		=> 'Пользователи',
	'select_customer_client'	=> 'Клиенты',
	'new_client'				=> 'Новый клиент',
	'form_pdv_label'			=> 'Цена',
	'form_pdv'					=> 'с НДС',
	'form_select_product'		=> 'Выберите продукт',
	'form_quantity_product'		=> 'Количество',
	'form_comment'				=> 'Комментарий к заказу',
    'select_payment'	    	=> 'Оплата',
    'select_payment_cashless'	=> 'На расчётный счёт поставщика',
    'select_address'	        => 'Адрес',
    'select_address_new'        => 'Новый адрес',
    'select_shipping'           => 'Выберите метод доставки',
    'select_shipping_nova_poshta' => 'Новая почта',
    'select_shipping_np_wherhouse' => 'На отделение',
    'select_shipping_np_curier' => 'Курьером',
    'select_city'               => 'Выберите населенный пункт',
    'select_city_input'         => 'Введите населенный пункт',
    'select_warehous'           => 'Выберите отделение',
    'select_adress_input'       => 'Адрес',
    'select_house_float_input'  => 'Номер дома / и квартиры',
    'select_adress_me_input'    => 'Введите номер / адрес отделения',

    'warehouse_grafic'           => 'График работы',
    'date_monday'           => 'Понедельник',
    'date_tuesday'           => 'Вторник',
    'date_wednesday'           => 'Среда',
    'date_thursday'           => 'Четверг',
    'date_friday'           => 'Пятница',
    'date_saturday'           => 'Суббота',
    'date_sunday'           => 'Воскресенье',
    'warehouse_max_weight'           => 'Грузоподъемность',
    'warehouse_weight_kg'           => 'кг',

	'table_new_prodct'		=> 'Товар',
	'table_new_quantity'	=> 'Количество',
	'table_new_storage'     => 'Склад/Остаток',
	'table_new_package'     => 'Упаковка',
	'table_new_weight'      => 'Вес',
	'table_new_price'		=> 'Цена',
	'table_new_total'		=> 'Сумма',


	'import_not_found'			=> 'Следующие товары не найдены:',
	'import_not_available'		=> 'Следующих товаров нет в наличии:',

	'cp_header'			=> 'Коммерческое предложение',
	'cp_price'			=> 'Наценка',
	'cp_user'			=> 'Клиент',
	'cp_btn'			=> 'Сгенерировать PDF',

	'btn_pdf_bill'		=> 'Счёт PDF',
	'act_date_from'		=> 'Дата от',
	'act_date_to'		=> 'Дата до',
	'btn_act_pdf'		=> 'Акт взаиморасчётов PDF',

    'purchases_pagename' => 'Покупки',

    'purchases_startdate' => 'Дата от: ',
    'purchases_enddate' => 'Дата до: ',
    'purchases_status' => 'Статусы заказа',
    'purchases_search_by_code' => 'Поиск по коду',
    'purchases_search_by_data' => 'Поиск по дате',
    'purchases_CSV-export' => 'Загрузить в CSV',
    'purchases_table_total_sum_of_orders_sellings_returns' => 'Общее кол-во в Заказах/Реализациях/Возвратах',
    'purchases_table_amount_of_orders_sellings_returns' => 'Общая сумма заказов в Заказах/Реализациях/Возвратах',
    'purchases_table_total_amount_of_orders' => 'Всего заказов',
    'purchases_table_average_check' => 'Средний чек',
    'purchases_table_average_pricetype' => 'Средний тип цен',

    'purchases_table_code/name' => 'Код/Название',
//    'purchases_table_name' => 'Название',
    'purchases_table_photo' => 'Фото',
    'purchases_table_quantity_in_orders' => 'К-во в Заказах (и к-во заказов)',
    'purchases_table_quantity_in_sellings' => 'К-сть в Реализациях (и к-во реализаций)',
    'purchases_table_quantity_in_returns' => 'К-сть в Возвратах (и к-во возвратов)',
    'purchases_table_sum_of_orders/sellings/reclamations' => 'Cумма заказов/реализаций/рекламаций',
//    'purchases_table_sum_of_sellings' => 'Cумма реализаций',
//    'purchases_table_sum_of_reclamations' => 'Cумма рекламаций',
    'purchases_table_percentage_of_confirmed_orders' => '% Подтвержденных заказов',
    'purchases_table_sellings_weight' => 'Общий вес в реализациях',
];
