@extends('layouts.app')
@section('main')
    <div class="container" id="app-contact">
        <div class="row" style="margin-top: 20px">
            <div class="col-md-4">
                <h3>@lang('company.title_page_confirm')</h3>
            </div>
        </div>
        <div class="btn-toolbar" style="margin-top: 20px">
            <form ref="company-search" action="{{route('contact.register.company.search')}}" method="get">
                <input ref="valueSearch" type="text" class="form-control" placeholder="個人名、会社名を入力してください" value="{{$_GET['value'] ?? ''}}" name="value">
            </form>
            <button type="button" name="search" @click="searchCompany()" class="btn btn-primary">@lang('company.search')</button>
            <button type="button" v-if="selectAllCompany == false  && dataCompany.length > 0" name="select_all" @click="checkedSelectAllCompany()" class="btn btn-primary">@lang('company.select_all')</button>
            <button type="button" v-else-if="dataCompany.length > 0" name="select_all" @click="checkedSelectAllCompany()" class="btn btn-primary">@lang('company.unselect_all')</button>
            <button name="delete" class="btn btn-danger" @click="showModalConfirmDeleteCompany()" data-target="#myModal">@lang('company.delete')</button>
        </div>
        <div>
            <form ref="company-delete" action="{{route('contact.register.company.delete')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="currentPage" value="{{$data->currentPage() ?? null}}">
            <table>
                <thead>
                    <tr>
                        <th>@lang('company.header_table.check')</th>
                        <th>@lang('company.header_table.company')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $item)
                        <tr>
                            <td><input type="checkbox" style="display: inline-block; width: 50px;" name="idCompany[{{$item['id']}}]" v-model="companySelected" value="{{$item['id']}}"></td>
                            <td><a style="color: #175388" href="{{route('contact.register.group', $item['id'])}}"><p>{{$item['name']}}</p></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </form>
        </div>
        @include('pagination.default', ['paginator' => $data])
        @include('layouts.modal_confirm_delete')
    </div>

    <script>
        var page = 'company'
        var dataCompany = []
        @foreach($data as $key => $item)
            dataCompany.push({!! json_encode($item) !!});
        @endforeach
    </script>
@endsection
@section('js')
    <script src="{{ asset('js/contact.js') }}"></script>
@endsection

