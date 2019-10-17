<div class="col-sm-12 info-row js-info-children-row">
    <div class="col-sm-12 form-group">

        <label for="name" class="col-sm-2 control-label">{{ __('Name') }}</label>

        <div class="col-sm-9">
            {!! Form::text('', $children->name ?? null, array('placeholder' => __('Name'),'class' => 'form-control jq_row_input', 'data-name' => 'name')) !!}                            
        </div>

        <div class="col-sm-1 padding-r-0">
            <button type="button" class="btn btn-danger pull-right jq_info-row-btn-delete">
                <i class="fa fa-trash"></i>
            </button>
        </div>        
    </div>

    <div class="col-sm-6 form-group">

        <label for="gender" class="col-sm-4 control-label">{{ __('Gender') }}</label>

        <div class="col-sm-6">
            {!! Form::select('', $userInfo->enums['gender'], $children->gender ?? null, array('placeholder' => '...', 'class' => 'form-control jq_row_input', 'data-name' => 'gender', 'single')) !!}                            
        </div>
    </div>

    <div class="col-sm-6 form-group">

        <label for="birthday" class="col-sm-4 control-label">{{ __('Birthday') }}</label>

        <div class="col-sm-6">
            {!! Form::text('', $children->birthday ?? null, array('placeholder' => __('Birthday'),'class' => 'form-control jq_row_input jq_children_birthday', 'data-name' => 'birthday')) !!}                            
        </div>
    </div>
</div>