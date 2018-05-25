<?php

return [
    //list contact: view('admin.contact.contact')
    'header_table' => [
        'edit' => 'Edit',
        'check' => 'Check',
        'name' => 'Name',
        'phone' => 'Phone',
        'memo' => 'Memo',
        'company' => 'Company',
        'mail' => 'Mail Address',
        'group' => 'Group',
        'birthday' => 'Birthday',
        'setting_share' => 'Setting Share',
        'tag' => 'Tag',
    ],
    'unselect_all' => 'Unselect All',
    'delete' => 'Delete',
    'search' => 'Search',
    'select_all' => 'Select All',

    //Controller/Admin/ContactController
    'error_import' => [
        'format_file' => 'File is error format',
        'empty_file' => 'File is empty',
        'group_error' => 'File has a line error atribute group',
        'name_error' => 'File has a line error atribute name',
        'birthday_error' => 'File has a line error atribute birthday',
        'phone_error' => 'File has a line error atribute phone 1',
        'mail_error' => 'File has a line error atribute mail address 1',
        'message_save_error' => 'Save file error !!!',
        'share_setting_error' => 'File has a line error atribute share setting',
    ],

    'error_download' => [
        'download_fail' => 'Download file template fail!',
    ],
];
