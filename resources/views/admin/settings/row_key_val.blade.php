<div class="col-sm-12 js-setting-row">
    <div class="col-sm-12 form-group">

        <label for="code" class="col-sm-1 control-label">{{ __('Code') }}</label>

        <div class="col-sm-4">
            <label for="code" class="col-sm-4 control-label font-weight-normal text-grey-darkest jq_row_input-prefix">{{ isset($codePrefix) && $codePrefix ? $codePrefix.'-' : null }}</label>
            <div class="col-sm-8">
                {!! Form::text('', $code ?? null, array('placeholder' => __('Code'),'class' => 'form-control jq_row_input-code jq_row_input', 'data-name' => 'code', (isset($code) ? 'readonly' : '') )) !!}
            </div>
        </div>
        
        <label for="code" class="col-sm-2 control-label">{{ __('Value') }}</label>
        <div class="col-sm-4">
            {!! Form::text('', $val ?? null, array('placeholder' => __('Value'),'class' => 'form-control jq_row_input', 'data-name' => 'val')) !!}
        </div>

        <div class="col-sm-1 padding-r-0">
            <button type="button" class="btn btn-danger pull-right jq_setting-row-btn-delete">
                <i class="fa fa-trash"></i>
            </button>
        </div>        
    </div>
</div>