<div class="col-sm-12 info-row js-info-languages-row">
    <div class="col-sm-12 form-group">

        <label for="name" class="col-sm-2 control-label">{{ __('Language') }}</label>

        <div class="col-sm-4">
            {!! Form::select('', $userInfo->enums['langs'], $languages->ln ?? null, array('placeholder' => __('Language').'...', 'class' => 'form-control '.(isset($languages) ? 'jq_languages_ln' : 'jq_languages_ln-none').' jq_row_input', 'data-name' => 'ln', 'single')) !!}            
        </div>
        
        <div class="col-sm-4">
            {!! Form::select('', $userInfo->enums['langLevels'], $languages->level ?? null, array('placeholder' => __('Level').'...', 'class' => 'form-control jq_row_input jq_languages_level', 'data-name' => 'level', 'single')) !!}                            
        </div>
        
        <div class="col-sm-2 padding-r-0">
            <button type="button" class="btn btn-danger pull-right jq_info-row-btn-delete">
                <i class="fa fa-trash"></i>
            </button>
        </div>   
        
        <div class="col-sm-10 col-sm-offset-2 margin-t-5">
            @foreach($userInfo->enums['langNotes'] as $langLevel => $langNote)
                <span class="jq_info-languages-note jq_info-languages-note-{{ $langLevel }} {{ isset($languages) && $languages->level && $languages->level == $langLevel ? '' : 'hidden'}}">{{ __($langNote) }}</span>
            @endforeach
        </div>
        
    </div>

</div>