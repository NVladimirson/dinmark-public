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

    'accepted'             => ':attribute має бути прийнятим.',
    'active_url'           => ':attribute не є дійсним URL.',
    'after'                => ':attribute повинен бути після дати :date.',
    'after_or_equal'       => ':attribute повинен бути датою після або еквівалентною до :date.',
    'alpha'                => ':attribute має містити тільки букви.',
    'alpha_dash'           => ':attribute має містити тільки букви, числа, та тире.',
    'alpha_num'            => ':attribute має містити тільки букви та числа.',
    'array'                => ':attribute поивнен бути масивом.',
    'before'               => 'The :attribute повинно бути датою перед :date.',
    'before_or_equal'      => 'The :attribute повинно бути датою перед або еквівалентною до :date.',
    'between'              => [
        'numeric' => ':attribute повинен бути між :min та :max.',
        'file'    => ':attribute повинен бути між :min та :max кілобайт.',
        'string'  => ':attribute повинна бути між :min та :max символів.',
        'array'   => ':attribute повинен бути між :min та :max елементів.',
    ],
    'boolean'              => ':attribute поле повинно бути правдою або хибою.',
    'confirmed'            => ':attribute підтвердження не відповідає.',
    'date'                 => ':attribute не є дійсною датою.',
    'date_format'          => ':attribute не відповідає формату :format.',
    'different'            => ':attribute та :other повинні бути різними.',
    'digits'               => ':attribute повинні бути :digits цифрами.',
    'digits_between'       => ':attribute повинні бути між :min та :max цифрами.',
    'dimensions'           => ':attribute має недійсні розміри зображення.',
    'distinct'             => ':attribute поле має подвійне значення.',
    'email'                => ':attribute повинна бути дійсна адреса email.',
    'exists'               => 'Обраний :attribute не дійсний.',
    'file'                 => ':attribute повинен бути файлом.',
    'filled'               => ':attribute поле повинно містити значення.',
    'image'                => ':attribute повинен бути зображенням.',
    'in'                   => 'Обраний :attribute не дійсний.',
    'in_array'             => ':attribute поле не існує в :other.',
    'integer'              => ':attribute повинен бути цілим числом.',
    'ip'                   => ':attribute повина бути дійсною IP адресою.',
    'ipv4'                 => ':attribute повина бути дійсною IPv4 адресою.',
    'ipv6'                 => ':attribute повина бути дійсною IPv6 адресою.',
    'json'                 => ':attribute повинен бути дійсним JSON рядком.',
    'max'                  => [
        'numeric' => ':attribute не має бути більшим за :max.',
        'file'    => ':attribute не має бути більшим за :max кілобайт.',
        'string'  => ':attribute не має бути більшим за :max символів.',
        'array'   => ':attribute не має містити більше за :max елементів.',
    ],
    'mimes'                => ':attribute повинен бути файлом з типом: :values.',
    'mimetypes'            => ':attribute повинен бути файлом з типом: :values.',
    'min'                  => [
        'numeric' => ':attribute must be at least :min.',
        'file'    => ':attribute повинен бути як мінімум :min кілобайт.',
        'string'  => ':attribute повинна бути як мінімум :min символів.',
        'array'   => ':attribute повинен містити як мінімум :min елементів.',
    ],
    'not_in'               => 'Обраний :attribute не є дійсним.',
    'numeric'              => ':attribute повинен бути числом.',
    'present'              => ':attribute поле повинно бути присутнім.',
    'regex'                => ':attribute формат недійсний.',
    'required'             => ':attribute поле є обов\'язковим.',
    'required_if'          => ':attribute поле є обов\'язковим коли :other є :value.',
    'required_unless'      => ':attribute поле є обов\'язковим, якщо тільки :other є в :values.',
    'required_with'        => ':attribute поле є обов\'язковим коли :values є present.',
    'required_with_all'    => ':attribute поле є обов\'язковим коли :values є присутнім.',
    'required_without'     => ':attribute поле є обов\'язковим коли :values не є присутнім.',
    'required_without_all' => ':attribute поле є обов\'язковим коли жоден з :values є присутніми.',
    'same'                 => ':attribute та :other повинні відповідати.',
    'size'                 => [
        'numeric' => ':attribute повинен бути :size.',
        'file'    => 'The :attribute повинен бути :size кілобайт.',
        'string'  => 'The :attribute повинен бути :size символів.',
        'array'   => 'The :attribute повинен містити :size елементів.',
    ],
    'string'               => ':attribute повинна бути рядком.',
    'timezone'             => ':attribute повинна бути дійсною зоною.',
    'unique'               => ':attribute вже використано.',
    'uploaded'             => ':attribute не вдалося завантажити.',
    'url'                  => ':attribute формат недійсний.',

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
