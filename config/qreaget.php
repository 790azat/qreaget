<?php

return [
    // Site contacts (move to DB settings later)
    'defaults' => [
        'site_name' => 'Քրեագետ',
        'phone_1' => '+374 95 770272',
        'phone_2' => '+374 10 520262',
        'email' => 'qreaget@mail.ru',
        'address_hy' => 'ք. Երևան, Արշակունյաց 5',
        'address_ru' => 'г. Ереван, ул. Аршакуняц 5',
        'address_en' => '5 Arshakunyats Ave, Yerevan',
        'hours_hy' => 'Երկ–Ուրբ, 10:00–18:00',
        'hours_ru' => 'Пн–Пт, 10:00–18:00',
        'hours_en' => 'Mon–Fri, 10:00–18:00',
        'instagram' => 'https://www.instagram.com/qreaget.expertise',
        'facebook' => 'https://www.facebook.com/share/1dnnSegKQX/',
        'map_query' => 'Arshakunyats 5, Yerevan',
    ],

    // ru / en will be enabled once translations are done
    'locales' => ['hy' => 'Հայ'],

    // Key that protects /install/{key} (runs migrations + seeding without SSH).
    'install_key' => env('INSTALL_KEY'),

];
