<?php

return [
    'item_per_page' => 50,
    'role' => [
        'main' => 1,
        'sub' => 2,
        'other' => 3
    ],

    'role_text' => [
        1 => 'メイン管理者',
        2 => 'サブ管理者',
        3 => '権限なし'
    ],

    'attribute' => [
        1 => '個人',
        2 => '会社'
    ],

    'attribute_save' => [
        '個人' => '1',
        '会社' => '2',
    ],

    'phone_type' => [
        '携帯番号' => 1, //mobile
        '仕事' => 2, //work
        '自宅' => 3, //home
        'FAX(仕事)' => 4, //fax work
        'FAX(自宅)' => 5 //fax home
    ],
    
    'phone_type_text' => [
        1 => '携帯番号',
        2 => '仕事',
        3 => '自宅',
        4 => 'FAX(仕事)',
        5 => 'FAX(自宅)'
    ],
    'icon_phone_type' => [
        1 => 'fas fa-address-book',
        2 => 'fas fa-address-card',
        3 => 'fab fa-algolia',
        4 => 'fas fa-ambulance',
        5 => 'fas fa-allergies',
    ],

    'mail_address_type' => [
        '会社' => 1, //company
        '携帯' => 2, //mobile
        '自宅' => 3, //home
    ],
    
    'mail_address_type_text' => [
        1 => '会社',
        2 => '携帯',
        3 => '自宅',
    ],

    'share_setting_type' => [
        'company' => 'company', //company
        'group' => 'group', //group
        'user' => 'user', //mail
    ],

    'icon_mail_address_type' => [
        1 => 'fas fa-address-book',
        2 => 'fas fa-address-card',
        3 => 'fab fa-algolia',
    ],

    'array_key_search_register_confirm' => [
        'name', 'mail_address', 'company_name', 'phone', 'group'
    ],
    'status' => [
        'available' => 1,
        'deleted'   => 2
    ],
    'contact' => [
        'item_per_page' => 50,
        'array_key_search_contact' => [
            'name', 'phone', 'mail_address', 'tag'
        ]
    ],
    'array_key_search_company' => [
        'name'
    ],

    'array_key_phone_type' => [
        4,6,8,10,12
    ],

    'array_key_mail_address_type' => [
        14,16,18,20,22
    ],

    'array_contact_key' => [
        'attribute','name', 'yomi_name', 'phone1', 'phone1_type', 'phone2', 'phone2_type', 'phone3', 'phone3_type', 'phone4', 'phone4_type', 'phone5', 'phone5_type','mail_address1', 'mail_address1_type', 'mail_address2', 'mail_address2_type', 'mail_address3', 'mail_address3_type', 'mail_address4', 'mail_address4_type', 'mail_address5', 'mail_address5_type', 'company_name', 'group1', 'group2', 'group3', 'group4', 'group5', 'birthday', 'memo', 
        'setting_share', 'tag'
    ],
];
