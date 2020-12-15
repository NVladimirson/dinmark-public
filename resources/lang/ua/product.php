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

  'all_page_name' => 'Усі товари',
  'in_stock_button_name' => 'Лише доступні',
  'add_to_my_list' => 'Додати в "Мій список"',
  'add_to_order' => 'Додати в Замовлення',
	'search_page_name' => 'Результати пошуку',
	'all_tab_name' => 'Список товарів',
	'search_tab_name' => 'Список товарів',
    'right_widget_name' => 'Категорії і фільтри',
  'all_categories_name' => 'Список категорій',
    'filters' => [
        'header' => 'Фільтри',
        'new' => 'Нові',
        'hits' => 'Хіти',
        'discount' => 'Акціонні пропозиції',
    ],
    'filters-with-properties' => 'Фільтри з властивостями',


  'mass_actions' =>
  [
    'select'=>'Дії з відміченими',
    'add-to-wishlist'=>'Додати в "Мій Список"',
    'add-to-order'=>'Додати до Замовлення',
  ],

  'mass_actions' =>
  [
    'select'=>'Дії з відміченими',
    'add-to-wishlist'=>'Додати в "Мій Список"',
    'add-to-order'=>'Додати до Замовлення',
  ],

	'empty'	=> 'За запитом нічого не знайдено',

	'select_term' => 'Оберіть термін доставки',
  'table_header_info' => 'Інформація',
  'table_header_price_per_100' => 'Ціна(100 шт.)',
  'table_header_calc_price' => 'Калькулятор вартості',
    'table_header_name/article' => 'Назва/Артикул',
	'table_header_name' => 'Назва',
    'table_header_photo' => 'Фото',
	'table_header_article' => 'Артикул',
    'table_header_price_retail' => 'Роздріб',
    'table_header_price' => 'Ваша ціна',
    'table_header_top_price' => 'Сума',
    'table_header_times_in_orders' => 'Разів в Замовленнях',
    'table_header_retail_user_prices' => 'Роздріб/Ваша ціна',
    'table_header_quantity' => 'Кількість',
    'table_header_package_weight' => 'Упак./Вага',
    'table_header_sum_w_taxes' => 'Сума з ПДВ',
    'table_header_price_porog_1' => '-3%',
    'table_header_price_porog_2' => '-7%',
//	'table_header_price_porog_1' => 'Ціна порогу 1',
//	'table_header_price_porog_2' => 'Ціна порогу 2',
	'table_header_price_from' => '(від :quantity шт.)',
	'table_header_storage' => 'Залишок/Термін  доставки',
	'storage_empty' => 'Немає в наявності',
    'storage_choose' => 'Оберіть склад',

	'show_tab_name' => 'Інформація про товар',
    'btn_pdf' => 'Завантажити креслення PDF',
	'header_main_info' => 'Основна інформація',
	'show_article' => 'Артикул',
	'show_price' => 'Роздрібна ціна (100шт)',
	'show_your_price' => 'Ваша ціна (100шт)',
	'show_price_porog_1' => 'Ціна порогу 1',
	'show_price_porog_2' => 'Ціна порогу 2',
	'show_weight' => 'Вага (100шт.)',
	'weight_kg' => 'кг',
	'header_params' => 'Специфікації',
	'header_storage' => 'Склади',
    'header_detail' => 'Технічні характеристики',
	'storage_name' => 'Склад',
	'storage_amount' => 'Залишок',
	'storage_package' => 'Упаковка (шт)',
    'storage_price' => 'Ціна (100шт)',
	'storage_limit_1' => 'Ліміт 1',
	'storage_limit_2' => 'Ліміт 2',
	'storage_term' => 'Термін доставки',
  'storage_quantity' => 'Кількість',
    'storage_total' => 'Сума',
  'storage_term_measure_shortly' => 'дн.',

	'modal_wishlist_header' => 'Оберіть список',
	'modal_order_header' => 'Додавання товару до замовлення',
	'select_order' => 'Оберіть замовлення',
	'new_order' => 'Новий замовлення',
    'modal_order_warning_1' => 'Увага! В наявності на складі ',
    'modal_order_warning_2' => ' шт. Можна сформувати уточнююче запит в наш відділ поставок на додаткову кількість.',
    'quantity_order' => 'Кількість (шт)',
    'quantity_order_request' => 'Уточнити додаткову кількість (шт)',
    'btn_order_request' => 'Додати і уточнити',
    'btn_order' => 'Додати',

    'get_price_success' => 'Запит успішно відправлено',
    'modal_get_price_header' => 'Запит ціни',
    'name_get_price' => 'Ваше ім\'я',
    'phone_get_price' => 'Ваш телефон',
    'quantity_get_price' => 'Бажана кількість',
    'get_price_comment' => 'Ваш коментар',
    'modal_get_price_submit' => 'Надіслати запит',
    'show_card_product' =>'Переглянути картку товара',
    'add_to_wish_list' =>'Додати до списку бажань',
    'add_to_order' =>'Додати до замовлення',

    'storage_filter_name' => [
      'storage_term' => 'Термін доставки',
      'category' => 'Категорія'
    ],

      'global_search' => [
        'result_header' => 'Загальний пошук',
        'name' => 'Назва',
        'orders' => '№ Замовлення',
        'implementations' => 'Реалізація',
        'reclamations' => 'Рекламація',
        'product_search' => [
          'header' => 'Товари',
        ],
        'order_search' => [
          'header' => 'Замовлення',
        ],
        'reclamation_search' => [
          'header' => 'Рекламації',
        ],
        'implementation_search' => [
          'header' => 'Реалізації',
        ],
      ],

      'extended_search' => [
        'result_header' => 'Розширений пошук',
        'name' => 'Найменування',
        'standart' => 'Стандарт',
        'pokryttja' => 'Покриття',
        'diametr' => 'Діаметр',
        'material' => 'Матеріал',
        'klas_micnosti' => 'Клас міцності',
        'dovzhyna' => 'Довжина',
      ],

];
