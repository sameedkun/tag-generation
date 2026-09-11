<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Only the rules this application's forms actually trigger are translated
    | below (required, string, max, date_format, after_or_equal). Everything
    | else falls back to lang/en/validation.php via the app's fallback_locale,
    | since no form here can ever produce those messages.
    |
    */

    'after_or_equal' => 'The :attribute field must be a date after or equal to :date.',
    'date' => ':attribute ایک درست تاریخ ہونی چاہیے۔',
    'date_format' => ':attribute کی صورت :format کے مطابق ہونی چاہیے۔',
    'max' => [
        'string' => ':attribute :max حروف سے زیادہ نہیں ہونا چاہیے۔',
    ],
    'required' => ':attribute کا اندراج ضروری ہے۔',
    'string' => ':attribute متن کی صورت میں ہونا چاہیے۔',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'expiresAt' => [
            'after_or_equal' => 'ختم ہونے کی تاریخ تیاری کی تاریخ کے برابر یا اس کے بعد ہونی چاہیے۔',
        ],
        'compactExpiresAt' => [
            'after_or_equal' => 'ختم ہونے کی تاریخ تیاری کی تاریخ کے برابر یا اس کے بعد ہونی چاہیے۔',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'title' => 'عنوان',
        'address' => 'پتہ',
        'manufacturedAt' => 'تیاری کی تاریخ',
        'expiresAt' => 'ختم ہونے کی تاریخ',
        'compactTitle' => 'عنوان',
        'compactAddress' => 'پتہ',
        'compactManufacturedAt' => 'تیاری کی تاریخ',
        'compactExpiresAt' => 'ختم ہونے کی تاریخ',
    ],

];
