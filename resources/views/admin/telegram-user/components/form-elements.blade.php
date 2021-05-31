<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': fields.user_id && fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.telegram-user.columns.user_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.user_id" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('user_id'), 'form-control-success': fields.user_id && fields.user_id.valid}" id="user_id" name="user_id" placeholder="{{ trans('admin.telegram-user.columns.user_id') }}">
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('dialog_path'), 'has-success': fields.dialog_path && fields.dialog_path.valid }">
    <label for="dialog_path" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.telegram-user.columns.dialog_path') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.dialog_path" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('dialog_path'), 'form-control-success': fields.dialog_path && fields.dialog_path.valid}" id="dialog_path" name="dialog_path" placeholder="{{ trans('admin.telegram-user.columns.dialog_path') }}">
        <div v-if="errors.has('dialog_path')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('dialog_path') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('dialog_params'), 'has-success': fields.dialog_params && fields.dialog_params.valid }">
    <label for="dialog_params" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.telegram-user.columns.dialog_params') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.dialog_params" v-validate="'required'" id="dialog_params" name="dialog_params"></textarea>
        </div>
        <div v-if="errors.has('dialog_params')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('dialog_params') }}</div>
    </div>
</div>


