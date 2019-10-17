<div class="col-sm-12 info-row js-info-courses-row">
        
    <div class="col-sm-12 form-group">

        <label for="inst" class="col-sm-2 control-label">{{ __('Educational institution') }}</label>

        <div class="col-sm-9">
            {!! Form::text('', $courses->inst ?? null, array('placeholder' => __('Educational institution'), 'class' => 'form-control jq_row_input', 'data-name' => 'inst')) !!}
        </div>
        
        <div class="col-sm-1 padding-r-0">
            <button type="button" class="btn btn-danger pull-right jq_info-row-btn-delete">
                <i class="fa fa-trash"></i>
            </button>
        </div>        
    </div>
    
    <div class="col-sm-12 form-group">

        <label for="name" class="col-sm-2 control-label">{{ __('Course name') }}</label>

        <div class="col-sm-9">
            {!! Form::text('', $courses->name ?? null, array('placeholder' => __('Course name'), 'class' => 'form-control jq_row_input', 'data-name' => 'name')) !!}
        </div>
        
        <div class="col-sm-1 padding-r-0">
            <button type="button" class="btn btn-danger pull-right jq_info-row-btn-delete">
                <i class="fa fa-trash"></i>
            </button>
        </div>        
    </div>
    
    <div class="col-sm-12 form-group">

        <label for="birthday" class="col-sm-2 control-label">{{ __('Year of ending') }}</label>

        <div class="col-sm-9 padding-r-0">            
            <label for="y" class="col-sm-1 control-label">{{ __('YY') }}</label>
            <div class="col-sm-3">            
                {!! Form::text('', $courses->y ?? null, array('placeholder' => __('Year'),'class' => 'form-control jq_row_input jq_education_y', 'data-name' => 'y')) !!}                            
            </div>
            
            <label for="m" class="col-sm-1 control-label">{{ __('MM') }}</label>
            <div class="col-sm-3">            
                {!! Form::text('', $courses->m ?? null, array('placeholder' => __('Month'),'class' => 'form-control jq_row_input jq_education_m', 'data-name' => 'm')) !!}                            
            </div>
            
            <label for="d" class="col-sm-1 control-label">{{ __('DD') }}</label>
            <div class="col-sm-3">            
                {!! Form::text('', $courses->d ?? null, array('placeholder' => __('Day'),'class' => 'form-control jq_row_input jq_education_d', 'data-name' => 'd')) !!}                            
            </div>
        </div>
    </div>
    
    

</div>