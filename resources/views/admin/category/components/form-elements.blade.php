<div class="form-group row align-items-center" :class="{'has-danger': errors.has('category_name'), 'has-success': fields.category_name && fields.category_name.valid }">
    <label for="category_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.category.columns.category_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.category_name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('category_name'), 'form-control-success': fields.category_name && fields.category_name.valid}" id="category_name" name="category_name" placeholder="{{ trans('admin.category.columns.category_name') }}">
        <div v-if="errors.has('category_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('category_name') }}</div>
    </div>
</div>


