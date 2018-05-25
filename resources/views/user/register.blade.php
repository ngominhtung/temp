@extends('layouts.app')

@section('main')
<div id='register_page'>
    <div class='register_body'>
        <div>@lang('register.title_page')</div>
        <div class='register_row_2'>
            <div>
                <a href='/user/download' class='download_btn'>@lang('register.download_phonebook')</a>
            </div>
            <div>
                <form id="import_form" action="/user/import" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type='file' name='import_file' hidden/>
                </form>
                <a href='javascript:void(0)' class='import_btn'>@lang('register.import_phonebook')</a>
            </div>
        </div>
        <div class="register_row_3">
            <div>@lang('register.download_data')</div>
            <div>@lang('register.upload_data')</div>
        </div>
        <div class="register_row_4">
            <div class="error-csv">@lang('register.error_format_csv')</div>
            @if (Session::has('errors'))
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection