@extends('layouts.app')

@section('main')
    <div class="container" id="app-contact">
        <div class="row" style="margin-top: 20px">
            <div class="col-md-4">
                <h3>@lang('group.title_page_confirm')</h3>
            </div>
        </div>
        <div class="btn-toolbar" style="margin-top: 20px">
            <form ref="group-search" action="{{route('contact.register.group.search', ['idCompany' => $idCompany])}}" method="get">
                <input type="text" class="form-control" placeholder="個人名、会社名を入力してください" value="{{$_GET['value'] ?? ''}}" name="value">
            </form>
            <button type="button" @click="searchGroup" class="btn btn-primary">@lang('group.search')</button>
            <button type="button" v-if="selectAllGroup == false && dataGroup.length > 0" name="select_all" @click="checkedSelectAllGroup()" class="btn btn-primary">@lang('company.select_all')</button>
            <button type="button" v-else-if="dataGroup.length > 0" name="select_all" @click="checkedSelectAllGroup()" class="btn btn-primary">@lang('company.unselect_all')</button>
            <button name="delete" class="btn btn-danger" @click="showModalConfirmDeleteGroup()" data-target="#myModal">@lang('company.delete')</button>
        </div>
        <div>
            <form ref="group-delete" action="{{route('contact.register.group.delete', ['idCompany' => $idCompany])}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="currentPage" value="{{$datas->currentPage() ?? null}}">
                <table>
                    <thead>
                    <tr>
                        <th>@lang('company.header_table.check')</th>
                        <th>@lang('company.header_table.company')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($datas as $key => $item)
                        <tr>
                            <td><input type="checkbox" style="display: inline-block; width: 50px;" name="idGroup[{{$item['id']}}]" v-model="groupSelected" value="{{$item['id']}}"></td>
                            <td><a href="{{route('contact.register.list', ['idCompany' => $idCompany, 'idGroup' => $item['id']])}}">{{$item['name']}}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        @include('layouts.modal_confirm_delete')
        @include('pagination.default', ['paginator' => $datas])
    </div>
    <script>
        var page = 'group';
        var dataGroup = [];
        @foreach($datas as $key => $item)
            dataGroup.push({!! json_encode($item) !!});
        @endforeach
    </script>
@endsection
@section('js')
    <script src="{{ asset('js/contact.js') }}"></script>
@endsection

