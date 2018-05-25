@extends('layouts.app')

@section('main')
    <div class="container" id="app-contact">
        <div class="row" style="margin-top: 20px">
            <div class="col-md-4">
                <h3>Contact</h3>
            </div>
        </div>
        <div class="btn-toolbar" style="margin-top: 20px">
            <form ref="contact-search" action="{{route('contact.register.search', ['idCompany' => $idCompany, 'idGroup' => $idGroup])}}" method="get">
                <input type="text" class="form-control" placeholder="個人名、会社名を入力してください" value="{{$_GET['value'] ?? ''}}" name="value">
            </form>
            <button type="button" name="search" @click="searchContact()" class="btn btn-primary">@lang('contact.search')</button>
            <button type="button" v-if="selectAllContact == false && dataContact.length > 0" name="select_all" @click="checkedSelectAllContact()" class="btn btn-primary">@lang('contact.select_all')</button>
            <button type="button" v-else-if="dataContact.length > 0" name="select_all" @click="checkedSelectAllContact()" class="btn btn-primary">@lang('contact.unselect_all')</button>
            <button name="delete" class="btn btn-danger" @click="showModalConfirmDeleteContact()" data-target="#myModal">@lang('contact.delete')</button>
        </div>
        <div class="well" style="margin-top: 20px">
            <form ref="contact-delete" action="{{route('contact.register.delete', ['idCompany' => $idCompany, 'idGroup' => $idGroup])}}" method="post">
                {{csrf_field()}}
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col" width="5%">@lang('contact.header_table.edit')</th>
                        <th scope="col" width="5%">@lang('contact.header_table.check')</th>
                        <th scope="col" width="12%">@lang('contact.header_table.name')</th>
                        <th scope="col" width="12%">@lang('contact.header_table.phone')</th>
                        <th scope="col" width="7%">@lang('contact.header_table.memo')</th>
                        <th scope="col" width="10%">@lang('contact.header_table.company')</th>
                        <th scope="col" width="7%">@lang('contact.header_table.mail')</th>
                        <th scope="col" width="7%">@lang('contact.header_table.group')</th>
                        <th scope="col" width="7%">@lang('contact.header_table.birthday')</th>
                        <th scope="col" width="12%">@lang('contact.header_table.setting_share')</th>
                        <th scope="col" width="7%">@lang('contact.header_table.tag')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($contacts) && !$contacts->isEmpty())
                        @foreach($contacts as $key => $item)
                            <input type="hidden" name="contact[{{$item->id}}]">
                            <input type="hidden" name="currentPage" value="{{$contacts->currentPage() ?? null}}">
                            <tr>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="/edit">@lang('contact.header_table.edit')</a>
                                </td>
                                <td>
                                    <img src="{{asset('ava.png')}}" alt="" height="40" width="40">
                                    <input style="margin-left: 12px" type="checkbox" name="idContactChecked[{{$item->id}}]" v-model="contactSelected" value="{{$item->id}}">
                                </td>
                                <td>
                                    {{$item->name ?? ""}}<br>
                                    {{$item->yomi_name ?? ""}}
                                </td>
                                <td>
                                    @if($item->contact_phone)
                                        @foreach($item->contact_phone as $itemPhone)
                                            {{$itemPhone->phone ?? ""}}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{$item->memo ?? ""}}</td>
                                <td>{{$item->companies->name ?? ""}}</td>
                                <td>
                                    @if($item->contact_mailaddress)
                                        @foreach($item->contact_mailaddress as $itemMail)
                                            {{$itemMail->mailaddress}}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{$item->company_groups->name ?? ""}}</td>
                                <td>{{$item->birthday ?? ""}}</td>
                                <td>{{$item->settingShare ?? ""}}</td>
                                <td>
                                    @if($item->tags)
                                        @foreach($item->tags as $itemTag)
                                            {{$itemTag->name ?? ""}}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
                @include('pagination.default', ['paginator' => $contacts])
            @endif
            @include('layouts.modal_confirm_delete')
        </div>
    </div>
    <script>
        var page = 'contact'
        var dataContact = []
        @if(isset($contacts) && !$contacts->isEmpty())
            @foreach($contacts as $key => $item)
                dataContact.push({!! json_encode($item) !!});
            @endforeach
        @endif
    </script>
@endsection
@section('js')
    <script src="{{ asset('js/contact.js') }}"></script>
@endsection
