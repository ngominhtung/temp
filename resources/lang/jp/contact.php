<?php

return [
    //list contact: view('admin.contact.contact')
    'header_table' => [
        'edit' => '編集',
        'check' => '選択',
        'name' => '名前',
        'phone' => '電話番号',
        'memo' => 'メモ',
        'company' => '会社名',
        'mail' => 'メールアドレス',
        'group' => 'グループ',
        'birthday' => '誕生日',
        'setting_share' => '共有設定',
        'tag' => 'タグ',
    ],
    'unselect_all' => '全解除',
    'delete' => '削除',
    'search' => '検索',
    'select_all' => '全選択',

    //Controller/Admin/ContactController
    'error_import' => [
        'format_file' => 'File is error format',
        'empty_file' => 'File is empty',
        'group_error' => 'File has a line error atribute group',
        'name_error' => 'File has a line error atribute name',
        'birthday_error' => 'File has a line error atribute birthday',
        'phone_error' => 'File has a line error atribute phone 1',
        'mail_error' => 'File has a line error atribute mail address 1',
        'share_setting_error' => 'File has a line error atribute share setting',
        'message_save_error' => 'Save file error !!!',
    ],

    'error_download' => [
        'download_fail' => 'Download file template fail!',
    ],
];
