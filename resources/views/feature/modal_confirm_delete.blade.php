<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" ref="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">@lang('register.confirm_delete')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('register.message_confirm')
            </div>
            <div class="modal-footer">
                <button name="close_modal" type="button" class="btn btn-default" data-dismiss="modal">@lang('register.close')</button>
                <button v-if="page == 'user-register'" name="confirm_delete" @click="deleteUserRegister()" type="button" class="btn btn-primary">@lang('register.modal_delete')</button>
                <button v-if="page == 'contact'" name="confirm_delete" @click="deleteContact()" type="button" class="btn btn-primary">@lang('register.modal_delete')</button>
                <button v-if="page == 'company'" name="confirm_delete" @click="deleteCompany()" type="button" class="btn btn-primary">@lang('register.modal_delete')</button>
                <button v-if="page == 'group'" name="confirm_delete" @click="deleteGroup()" type="button" class="btn btn-primary">@lang('register.modal_delete')</button>
            </div>
        </div>
    </div>
</div>
