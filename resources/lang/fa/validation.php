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

    'accepted' => ':attribute باید پذیرفته شده باشد.',
    'accepted_if' => ':attribute باید پذیرفته شده باشد وقتی :other برابر :value است.',
    'active_url' => 'آدرس :attribute معتبر نیست.',
    'after' => ':attribute باید تاریخی بعد از :date باشد.',
    'after_or_equal' => ':attribute باید تاریخی بعد از :date، یا مطابق با آن باشد.',
    'alpha' => ':attribute باید فقط حروف الفبا باشد.',
    'alpha_dash' => ':attribute باید فقط حروف الفبا، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num' => ':attribute باید فقط حروف الفبا و اعداد باشد.',
    'array' => ':attribute باید آرایه باشد.',
    'before' => ':attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal' => ':attribute باید تاریخی قبل از :date، یا مطابق با آن باشد.',
    'between' => [
        'array' => ':attribute باید بین :min و :max آیتم باشد.',
        'file' => ':attribute باید بین :min و :max کیلوبایت باشد.',
        'numeric' => ':attribute باید بین :min و :max باشد.',
        'string' => ':attribute باید بین :min و :max کاراکتر باشد.',
    ],
    'boolean' => 'فیلد :attribute فقط می‌تواند true و یا false باشد.',
    'confirmed' => ':attribute با فیلد تکرار مطابقت ندارد.',
    'current_password' => 'رمزعبور اشتباه است.',
    'date' => ':attribute یک تاریخ معتبر نیست.',
    'date_equals' => ':attribute باید یک تاریخ برابر با تاریخ :date باشد.',
    'date_format' => ':attribute با الگوی :format مطابقت ندارد.',
    'declined' => ':attribute باید رد شده باشد.',
    'declined_if' => ':attribute باید رد شده باشد وقتی :other برابر :value است.',
    'different' => ':attribute و :other باید از یکدیگر متفاوت باشند.',
    'digits' => ':attribute باید :digits رقم باشد.',
    'digits_between' => ':attribute باید بین :min و :max رقم باشد.',
    'dimensions' => 'ابعاد تصویر :attribute قابل قبول نیست.',
    'distinct' => 'فیلد :attribute مقدار تکراری دارد.',
    'email' => ':attribute باید یک ایمیل معتبر باشد.',
    'ends_with' => 'فیلد :attribute باید با یکی از مقادیر زیر خاتمه یابد: :values',
    'enum' => ':attribute انتخاب شده اشتباه است.',
    'exists' => ':attribute انتخاب شده، معتبر نیست.',
    'file' => ':attribute باید یک فایل معتبر باشد.',
    'filled' => 'فیلد :attribute باید مقدار داشته باشد.',
    'gt' => [
        'array' => ':attribute باید بیشتر از :value آیتم داشته باشد.',
        'file' => ':attribute باید بزرگتر از :value کیلوبایت باشد.',
        'numeric' => ':attribute باید بزرگتر از :value باشد.',
        'string' => ':attribute باید بیشتر از :value کاراکتر داشته باشد.',
    ],
    'gte' => [
        'array' => ':attribute باید بیشتر یا مساوی :value آیتم داشته باشد.',
        'file' => ':attribute باید بزرگتر یا مساوی :value کیلوبایت باشد.',
        'numeric' => ':attribute باید بزرگتر یا مساوی :value باشد.',
        'string' => ':attribute باید بیشتر یا مساوی :value کاراکتر داشته باشد.',
    ],
    'image' => ':attribute باید یک تصویر معتبر باشد.',
    'in' => ':attribute انتخاب شده، معتبر نیست.',
    'in_array' => 'فیلد :attribute در لیست :other وجود ندارد.',
    'integer' => ':attribute باید عدد صحیح باشد.',
    'ip' => ':attribute باید آدرس IP معتبر باشد.',
    'ipv4' => ':attribute باید یک آدرس معتبر از نوع IPv4 باشد.',
    'ipv6' => ':attribute باید یک آدرس معتبر از نوع IPv6 باشد.',
    'json' => 'فیلد :attribute باید یک رشته از نوع JSON باشد.',
    'lt' => [
        'array' => ':attribute باید کمتر از :value آیتم داشته باشد.',
        'file' => ':attribute باید کوچکتر از :value کیلوبایت باشد.',
        'numeric' => ':attribute باید کوچکتر از :value باشد.',
        'string' => ':attribute باید کمتر از :value کاراکتر داشته باشد.',
    ],
    'lte' => [
        'array' => ':attribute باید کمتر یا مساوی :value آیتم داشته باشد.',
        'file' => ':attribute باید کوچکتر یا مساوی :value کیلوبایت باشد.',
        'numeric' => ':attribute باید کوچکتر یا مساوی :value باشد.',
        'string' => ':attribute باید کمتر یا مساوی :value کاراکتر داشته باشد.',
    ],
    'mac_address' => ':attribute باید یک مک آدرس صحیح باشد.',
    'max' => [
        'array' => ':attribute نباید بیشتر از :max آیتم داشته باشد.',
        'file' => ':attribute نباید بزرگتر از :max کیلوبایت باشد.',
        'numeric' => ':attribute نباید بزرگتر از :max باشد.',
        'string' => ':attribute نباید بیشتر از :max کاراکتر داشته باشد.',
    ],
    'mimes' => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'mimetypes' => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'min' => [
        'array' => ':attribute نباید کمتر از :min آیتم داشته باشد.',
        'file' => ':attribute نباید کوچکتر از :min کیلوبایت باشد.',
        'numeric' => ':attribute نباید کوچکتر از :min باشد.',
        'string' => ':attribute نباید کمتر از :min کاراکتر داشته باشد.',
    ],
    'multiple_of' => 'مقدار :attribute باید مضربی از :value باشد.',
    'not_in' => ':attribute انتخاب شده، معتبر نیست.',
    'not_regex' => 'فرمت :attribute معتبر نیست.',
    'numeric' => ':attribute باید عدد یا رشته‌ای از اعداد باشد.',
    'password' => 'رمزعبور اشتباه است.',
    'present' => 'فیلد :attribute باید در پارامترهای ارسالی وجود داشته باشد.',
    'prohibited' => 'فیلد :attribute ممنوع است.',
    'prohibited_if' => 'فیلد :attribute ممنوع است هنگامیکه مقدار :other برابر :value باشد.',
    'prohibited_unless' => 'فیلد :attribute ممنوع است مگر اینکه مقدار :other در :values باشد.',
    'prohibits' => 'فیلد :attribute اجازه حضور فیلد :other را نمی دهد.',
    'regex' => 'فرمت :attribute معتبر نیست.',
    'required' => 'فیلد :attribute الزامی است.',
    'required_array_keys' => 'فیلد :attribute باید حاوی ورودی های :values باشد.',
    'required_if' => 'هنگامی که :other برابر با :value است، فیلد :attribute الزامی است.',
    'required_unless' => 'فیلد :attribute الزامی است، مگر آنکه :other در :values موجود باشد.',
    'required_with' => 'در صورت وجود فیلد :values، فیلد :attribute نیز الزامی است.',
    'required_with_all' => 'در صورت وجود فیلدهای :values، فیلد :attribute نیز الزامی است.',
    'required_without' => 'در صورت عدم وجود فیلد :values، فیلد :attribute الزامی است.',
    'required_without_all' => 'در صورت عدم وجود هر یک از فیلدهای :values، فیلد :attribute الزامی است.',
    'same' => ':attribute و :other باید همانند هم باشند.',
    'size' => [
        'array' => ':attribute باید شامل :size آیتم باشد.',
        'file' => ':attribute باید برابر با :size کیلوبایت باشد.',
        'numeric' => ':attribute باید برابر با :size باشد.',
        'string' => ':attribute باید برابر با :size کاراکتر باشد.',
    ],
    'starts_with' => ':attribute باید با یکی از این ها شروع شود: :values',
    'string' => 'فیلد :attribute باید متن باشد.',
    'timezone' => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'unique' => ':attribute قبلا انتخاب شده است.',
    'uploaded' => 'بارگذاری فایل :attribute موفقیت آمیز نبود.',
    'url' => ':attribute معتبر نمی‌باشد.',
    'uuid' => ':attribute باید یک UUID معتبر باشد.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'id' => 'شناسه',
        'v' => 'شناسه ویدیو',
    ],

];
