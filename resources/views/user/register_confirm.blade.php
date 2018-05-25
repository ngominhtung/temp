@extends('layouts.app')

@section('main')
<div class="container-fluid" id="app">
    <div class="row" style="margin-top: 20px">
        <div class="col-md-4">
            <h3>@lang('register.title_page_confirm')</h3>
        </div>
        <div class="col-md-1" style="margin-left: -100px">
            <button name="add_user" class="btn btn-primary">@lang('register.add_user')</button>
        </div>
        <div class="col-md-1" style="margin-left: 40px">
            <a href="/user/regist" class="btn btn-primary">@lang('register.add_CSV')</a>
        </div>
    </div>
    <div class="btn-toolbar" style="margin-top: 20px">
        <form ref="user-search" action="{{route('user.register.search')}}" method="get">
            <input type="text" class="form-control" placeholder="個人名、会社名を入力してください" value="{{$_GET['value'] ?? ''}}" name="value">
        </form>
        <button type="button" name="search" @click="search()" class="btn btn-primary">@lang('register.search')</button>
        <button type="button" v-if="selectAll == false" name="select_all" @click="checkedSelectAll()" class="btn btn-primary">@lang('register.select_all')</button>
        <button type="button" v-else name="select_all" @click="checkedSelectAll()" class="btn btn-primary">@lang('register.unselect_all')</button>
        <button name="delete" class="btn btn-danger" data-target="#myModal" @click="showModalConfirmDelete">@lang('register.delete')</button>
    </div>
    <div class="well" style="margin-top: 20px">
        <form ref="user-delete" action="{{route('user.register.delete')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="currentPage" value="{{$datas->currentPage()}}">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" width="5%">@lang('register.choice')</th>
                        <th scope="col" width="5%">@lang('register.avatar')</th>
                        <th scope="col" width="10%">@lang('register.name')</th>
                        <th scope="col" width="8%">@lang('register.phone')</th>
                        <th scope="col" width="12%">@lang('register.email')</th>
                        <th scope="col" >@lang('register.note')</th>
                        <th scope="col" width="6%">@lang('register.company')</th>
                        <th scope="col" width="7%">@lang('register.group')</th>
                        <th scope="col" width="6%">@lang('register.birthday')</th>
                        <th scope="col" width="8%">@lang('register.card')</th>
                        <th scope="col" width="6%">@lang('register.id')</th>
                        <th scope="col" width="6%">@lang('register.properties')</th>
                        <th scope="col" width="6%">@lang('register.organization')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $key => $item)
                    <tr>
                        <td>
                            <input type="checkbox" name="id[{{$item['id']}}]" v-model="selected" value="{{$item['id']}}">
                        </td>
                        <td>
                            <img src="{{asset('ava.png')}}" alt="" height="40" width="40">
                        </td>
                        <td>
                            {{$item['name']}}<br>
                            {{$item['yomi_name']}}
                        </td>
                        <td>
                            @foreach($item['phone'] as $itemPhone)
                            @if($itemPhone['number'] != null)
                            <i class="{{$itemPhone['icon']}}">{{$itemPhone['number']}}</i>
                            <br>
                            @endif
                            @endforeach
                        </td>
                        <td><i class="fas fa-chart-bar"><p>{{$item['mail_address']}}</p></i></td>
                        <td>{{$item['memo']}}</td>
                        <td>{{$item['company_name']}}</td>
                        <td>
                            @foreach($item['group'] as $itemGroup)
                            {{$itemGroup}}
                            <br>
                            @endforeach
                        </td>
                        <td>{{$item['birthday']}}</td>
                        <td></td>
                        <td>{{$item['id']}}</td>
                        <td></td>
                        <td>{{$item['role_name']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @include('feature.default', ['paginator' => $datas])
        <form action="{{route('user.register.confirmed')}}" method="POST">
            {{csrf_field()}}
            <p style="text-align: center;">@lang('register.confirm_register')<button name="register" type="submit" class="btn btn-primary">@lang('register.register')</button></p>
        </form>
        @include('layouts.modal_confirm_delete')
    </div>
</div>
<style>
button{
    margin-left: 10px;
}
</style>
<script>
    var dataUser = []
    @foreach($datas as $key => $item)
    dataUser.push({!! json_encode($item) !!});
    @endforeach
</script>
@endsection
@section('js')
    <script src="{{ asset('js/register_confirm.js') }}"></script>
@endsection

