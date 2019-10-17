<div class="row skill-row jq_skill_row">
    {!! Form::hidden('id[]', $skill->id ?? null, array('class' => 'jq_skill_id')) !!}                            
    {!! Form::hidden('cat[]', $cat) !!}                            
    
    <div class="col-md-4 form-group">
        <label for="name" class="control-label">{{ __('Title') }}</label>
        <div class="box-tools pull-right"><button type="button" class="btn btn-danger btn-sm jq_skill_row-delete-btn"><i class="fa fa-trash"></i></button></div>
        <div class="">
            {!! Form::select('name[]', $settings['skills'][$cat] ?? [], $skill->name ?? null, array('placeholder' => __('Title').'...', 'class' => 'form-control jq_skill_name jq_row_input'.(isset($skill) && !empty($skill) ? '' : ' select2-input-template'), 'data-name' => 'skill', 'single', 'required')) !!}
        </div>
    </div>

    <div class="col-md-4 form-group">
        <label for="level" class="control-label">{{ __('Level') }}</label>
        <div class="">
            {!! Form::select('level[]', $settings['skillLevels'] ?? [], $skill->level ?? null, array('placeholder' => __('Level').'...', 'class' => 'form-control jq_skill_level jq_row_input', 'data-name' => 'level', 'single', 'required')) !!}            
        </div>
    </div>

    <div class="col-md-2 form-group">
        <label for="exp" class="control-label">{{ __('Experience') }}</label>
        <div class="">
            {!! Form::number('exp[]', $skill->exp ?? 0, array('placeholder' => __('Years'), 'class' => 'form-control jq_skill_exp jq_row_input', 'data-name' => 'exp', 'min'=>0, 'max'=>50, 'step'=>'0.5', 'required')) !!}                                      
        </div>
    </div>

    <div class="col-md-2 form-group">
        <label for="used" class="control-label">{{ __('Last used') }}</label>
        <div class="">
            {!! Form::text('used[]', $skill->used ?? null, array('placeholder' => __('Year'),'class' => 'form-control jq_row_input jq_skill_used', 'data-name' => 'used', 'required')) !!}                            
        </div>
    </div>
    
    <div class="col-sm-12">
        @foreach($settings['skillNotes'] as $skillLevel => $skillNote)
            <span class="skill-note jq_skill-skill-note jq_skill-skill-note-{{ $skillLevel }} {{ isset($skill) && $skill->level && $skill->level == $skillLevel ? '' : 'hidden'}}">{{ __($skillNote) }}</span>
        @endforeach
    </div>
</div>