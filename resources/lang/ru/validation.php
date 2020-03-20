<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute должен быть принят.',
    'active_url'           => ':attribute не действительный URL.',
    'after'                => ':attribute должен быть датой после :date.',
    'after_or_equal'       => ':attribute должен быть датой после или эквивалентой к :date.',
    'alpha'                => ':attribute может содержать только буквы.',
    'alpha_dash'           => ':attribute может содержать только буквы, числа, и тире.',
    'alpha_num'            => ':attribute может содержать только буквы и числа.',
    'array'                => ':attribute должен быть массивом.',
    'before'               => ':attribute должна быть датой перед :date.',
    'before_or_equal'      => ':attribute должна быть датой перед или эквивалентой к :date.',
    'between'              => [
        'numeric' => ':attribute должен быть между :min и :max.',
        'file'    => ':attribute должен быть между :min и :max килобайт.',
        'string'  => ':attribute должен быть между :min и :max символов.',
        'array'   => ':attribute должен быть между :min и :max элементов.',
    ],
    'boolean'              => ':attribute поле должно быть правда или ложь.',
    'confirmed'            => ':attribute подтверждение недействительно.',
    'date'                 => ':attribute недействительная дата.',
    'date_format'          => ':attribute не соответствует формату :format.',
    'different'            => ':attribute и :other должны быть разными.',
    'digits'               => ':attribute должны быть :digits цифрами.',
    'digits_between'       => ':attribute должны быть между :min и :max цифрами.',
    'dimensions'           => ':attribute имеет недействительный размер изображения.',
    'distinct'             => ':attribute поле имеет двойные значения.',
    'email'                => ':attribute должен быть действительным email адресом.',
    'exists'               => 'Выбранные :attribute недействительны.',
    'file'                 => ':attribute должен быть файлом.',
    'filled'               => ':attribute поле должно иметь значение.',
    'image'                => ':attribute должен быть изображением.',
    'in'                   => 'Выбранные :attribute недействительны.',
    'in_array'             => ':attribute поле не существует в :other.',
    'integer'              => ':attribute должен быть целым числом.',
    'ip'                   => ':attribute должен быть действительным IP адресом.',
    'ipv4'                 => ':attribute должен быть действительным IPv4 адресом.',
    'ipv6'                 => ':attribute должен быть действительным IPv6 адресом.',
    'json'                 => ':attribute должен быть действительным JSON файлом.',
    'max'                  => [
        'numeric' => ':attribute не может быть больше чем :max.',
        'file'    => ':attribute не может быть больше чем :max килобайт.',
        'string'  => ':attribute не может быть больше чем :max символов.',
        'array'   => ':attribute не может содержать больше чем :max элементов.',
    ],
    'mimes'                => ':attribute должен быть файлом типа: :values.',
    'mimetypes'            => ':attribute должен быть файлом типа: :values.',
    'min'                  => [
        'numeric' => ':attribute должен быть как минимум :min.',
        'file'    => ':attribute должен быть как минимум :min килобайт.',
        'string'  => ':attribute должен быть как минимум :min символов.',
        'array'   => ':attribute должен иметь как минимум :min элементов.',
    ],
    'not_in'               => 'Выбранный :attribute недействителен.',
    'numeric'              => ':attribute должен быть числом.',
    'present'              => ':attribute поле должно присутствовать.',
    'regex'                => ':attribute формат недействителен.',
    'required'             => ':attribute поле обязательное.',
    'required_if'          => ':attribute поле обязательное когда :other is :value.',
    'required_unless'      => ':attribute поле обязательное если :other есть в :values.',
    'required_with'        => ':attribute поле обязательное когда :values присутствует.',
    'required_with_all'    => ':attribute поле обязательное когда :values присутствует.',
    'required_without'     => ':attribute поле обязательное когда :values отсутствует.',
    'required_without_all' => ':attribute поле обязательное когда любой из :values присутствуют.',
    'same'                 => ':attribute и :other должны соответствовать.',
    'size'                 => [
        'numeric' => ':attribute должен быть :size.',
        'file'    => ':attributeдолжен быть :size килобайт.',
        'string'  => ':attribute должен быть :size символов.',
        'array'   => ':attribute должен содержать :size элементов.',
    ],
    'string'               => ':attribute должен быть строкой.',
    'timezone'             => ':attribute должна быть действительной зоной.',
    'unique'               => ':attribute уже использовано.',
    'uploaded'             => ':attribute не удалось загрузить.',
    'url'                  => ':attribute неверный формат.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
