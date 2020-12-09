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


    'modal_success' => 'Товар успішно доданий у замовлення',
    // 'modal_success_multiple' => '{1,21} :count товар успешно добавлен в заказ |
    // {2,3,4,22,23,24} :count товара успешно добавлен в заказ |
    // [{5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25},*] :count товаров успешно добавлен в заказ ',
    'modal_success_multiple' => 'Товар успішно додані до замовлення',
	'page_list' => 'Замовлення',
	'all_tab_name' 	=> 'Список Замовлень',
    'show_tab_name' 	=> 'Замовлення',
	'select_status' 	=> 'Оберіть статус',
    'filter_select_sender' 	=> 'Оберіть відправника',
    'filter_select_customer' 	=> 'Оберіть отримувача',

    'select_status_payment'     => 'Оберіть статус оплати',
    'payment_status_none'       => 'Не оплачено',
    'payment_status_partial'    => 'Часткова оплата',
    'payment_status_success'    => 'Оплачено повністю',

    'tab_name_order'        => 'Замовлення',
    'tab_name_request'      => 'Заявки',
    'tab_name_archive'      => 'Архів',

    'btn_explanation'   => 'Уточнення по Замовленню',
    'btn_explanation_implementation'       => 'Уточнення по Реалізації',
    'btn_explanation_reclamation'       => 'Уточнення по Поверненню',
    'explanation_message'   => 'Повідомлення',
    'explanation_submit'    => 'Надіслати',
    'explanation_subject_order'    => 'Уточнення по замовленню ',
    'explanation_subject_implementation'    => 'Уточнення по реалізації ',
    'explanation_subject_reclamation'    => 'Уточнення по поверненню ',
    'explanation_edit_reclamation'    => 'Змінити повернення',
    'explanation_success'    => 'Запит на уточнення успішно відправлено',

    'btn_add_order'                 => 'Створити заявку',
	'table_header_number' 			=> 'Номер',
	'table_header_date' 			=> 'Дата',
	'table_header_status' 			=> 'Статус',
	'table_header_status_payment' 	=> 'Статус Оплати',
	'table_header_total' 			=> 'Сума',
	'table_header_customer' 		=> 'Відправник',
	'table_header_user' 			=> 'Отримувач',

    'table_header_date_create'      => 'Дата створення',
    'table_header_date_update'      => 'Дата останньої зміни',
    'table_header_shipping_method'  => 'Спосіб доставки',
    'table_header_client'           => 'Клієнт',
    'table_header_manager'          => 'Менеджер',

    'table_shipping_method'         => 'Метод доставки',
    'table_shipping_city'           => 'Населений пункт',
    'table_shipping_warehouse'      => 'Відділення',
    'table_shipping_address'        => 'Адреса',
    'table_shipping_house_float'    => 'Номер будинку / і квартири',
    'table_shipping_address_me'     => 'Номер / адреса відділення',

    'table_footer_pc'               => 'К-сть:',
    'table_footer_total'            => 'Сума:',
    'table_footer_discount'         => 'Знижки:',
    'table_footer_payed'            => 'Оплачені:',
    'table_footer_not_payed'        => 'Не оплачені:',

	'page_create' 				=> 'Нове замовлення',
	'page_update' 				=> 'Замовлення ',
	'order_number'				=> 'Заявка №',
	'implementation_number'		=> 'Відправлення',
    'payment_number'	    	=> 'Оплата',
    'table_payment_date'    	=> 'Дата платежу',
    'table_payment_total'	   	=> 'Сума',
	'from'						=> 'від',
	'btn_new_request'			=> 'Зберегти Заявку',
	'btn_new_order'				=> 'Оформити Замовлення',
    'btn_open_order'			=> 'Відновити',
    'btn_copy'			        => 'Повторити',
	'btn_cancel_order'			=> 'Скасувати',
    'btn_cancel_close'			=> 'Закрити',
	'select_sender'				=> 'Відправник',
	'sender_dinmark'			=> 'Dinmark',
	'select_customer'			=> 'Одержувач',
	'select_customer_user'		=> 'Користувачі',
	'select_customer_client'	=> 'Клієнти',
	'new_client'				=> 'Новий клієнт',
	'form_pdv_label'			=> 'Ціна',
	'form_pdv'					=> 'з ПДВ',
	'form_select_product'		=> 'Оберіть продукт',
	'form_quantity_product'		=> 'Кількість',
	'form_comment'				=> 'Коментар до замовлення',
    'select_payment'	    	=> 'Оплата',
    'select_payment_cashless'	=> 'На розрахунковий рахунок постачальника',
    'select_address'	        => 'Адреса',
    'select_address_new'        => 'Нова адреса',
    'select_shipping'           => 'Оберіть метод доставки',
    'select_shipping_nova_poshta' => 'Нова пошта',
    'select_shipping_np_wherhouse' => 'На відділення',
    'select_shipping_np_curier' => 'Кур\'єром',
    'select_city'               => 'Оберіть населений пункт',
    'select_city_input'         => 'Введіть населений пункт',
    'select_warehous'           => 'Оберіть відділення',
    'select_adress_input'       => 'Адреса',
    'select_house_float_input'  => 'Номер будинку / і квартири',
    'select_adress_me_input'    => 'Введіть номер / адресу відділення',

    'warehouse_grafic'           => 'Графік роботи',
    'date_monday'           => 'Понеділок',
    'date_tuesday'           => 'Вівторок',
    'date_wednesday'           => 'Середа',
    'date_thursday'           => 'Четвер',
    'date_friday'           => 'П\'ятниця',
    'date_saturday'           => 'Субота',
    'date_sunday'           => 'Неділя',
    'warehouse_max_weight'           => 'Вантажопідйомність',
    'warehouse_weight_kg'           => 'кг',

	'table_new_prodct'		=> 'Товар',
	'table_new_quantity'	=> 'Кількість',
    'table_new_storage'     => 'Склад/Залишок',
    'table_new_package'     => 'Упаковка',
    'table_new_weight'      => 'Вага',
	'table_new_price'		=> 'Ціна',
	'table_new_total'		=> 'Сума',


	'import_not_found'			=> 'Наступні товари не знайдені:',
	'import_not_available'		=> 'Наступних товарів немає в наявності:',

	'cp_header'			=> 'Комерційна пропозиція',
	'cp_price'			=> 'Націнка',
	'cp_user'			=> 'Клієнт',
	'cp_btn'			=> 'Згенерувати PDF',

	'btn_pdf_bill'		=> 'Рахунок PDF',
	'act_date_from'		=> 'Дата від',
	'act_date_to'		=> 'Дата до',
	'btn_act_pdf'		=> 'Акт взаєморозрахунків PDF',

    'purchases_pagename' => 'Покупки',
    'purchases_startdate' => 'Дата від: ',
    'purchases_enddate' => 'Дата до: ',
    'purchases_status' => 'Статуси замовлення',
    'purchases_search_by_code' => 'Пошук за кодом',
    'purchases_search_by_data' => 'Пошук за датою',
    'purchases_CSV-export' => 'Завантажити у CSV',//UK
    'purchases_table_total_sum_of_orders_sellings_returns' => 'Загальна к-ть в Замовленнях/Реалізаціях/Поверненнях',
    'purchases_table_amount_of_orders_sellings_returns' => 'Загальна сума замовлень в Замовленнях/Реалізаціях/Поверненнях',
    'purchases_table_total_amount_of_orders' => 'Усього заказів',
    'purchases_table_average_check' => 'Середній чек',
    'purchases_table_average_pricetype' => 'Середній тип цін',

    'purchases_table_code/name' => 'Код/Назва',
//    'purchases_table_name' => 'Название',
    'purchases_table_photo' => 'Фото',
    'purchases_table_quantity_in_orders' => 'К-сть в Замовленнях (і к-сть замовлень)',
    'purchases_table_quantity_in_sellings' => 'К-сть в Реалізаціях (і к-сть реалізацій)',
    'purchases_table_quantity_in_returns' => 'К-сть в Поверненнях (і к-сть повернень)',
    'purchases_table_sum_of_orders/sellings/reclamations' => 'Cума замовлень/реалізацій/рекламацій',
//    'purchases_table_sum_of_sellings' => 'Cума реалізацій',
//    'purchases_table_sum_of_reclamations' => 'Cума рекламацій',
    'purchases_table_percentage_of_confirmed_orders' => '% Підтверджених замовлень',
    'purchases_table_sellings_weight' => 'Загальна вага в реалізаціях',
];
