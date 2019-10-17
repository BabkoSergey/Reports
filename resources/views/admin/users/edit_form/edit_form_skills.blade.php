@foreach($settings['skills_cat'] as $skillsCatKey => $skillsCat)
    <div id="js-skills-row-template-{{ $skillsCatKey }}" style="display: none;">
        @include('admin.users.edit_form.skill_row', ['skill' => null, 'cat' => $skillsCatKey])
    </div>
@endforeach

{!! Form::model($user, ['method' => 'PATCH','route' => ['user_skill.update', $user->id], 'class' => '', 'id'=>'skills_form']) !!}
    <div class="box-body">
        <div class="row">
            @foreach($settings['skills_cat'] as $skillsCatKey => $skillsCat)
                <div class="col-sm-12">                    
                    <div class="box box-default box-solid jq_skills-cat-box" data-skills_cat="{{ $skillsCatKey }}">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ __($skillsCat) }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm jq_skills-cat-btn-add"><i class="fa fa-plus-square-o color-wite"></i></button>
                                <button type="button" class="btn btn-box-tool jq_skills-cat-btn-expand" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body js-skills-box-body-{{ $skillsCatKey }}" style="">
                            @foreach($user->skills[$skillsCatKey] ?? [] as $skill)
                                @include('admin.users.edit_form.skill_row', ['skill' => $skill, 'cat' => $skillsCatKey])
                            @endforeach
                        </div>
                    </div>           
                </div>
            @endforeach
        </div>
    </div>

    <div class="box-footer">
        <a class="btn btn-default" role="button" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
    </div>

{!! Form::close() !!}

@push('styles')
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@push('scripts')   
    <script src="{{ asset('/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    
    <script>
        $(function () {  
            $( document ).ready(function() { 
                
                $(document).on('change','.jq_skill_level',function (e){
                    e.preventDefault(); 
                     
                    $(this).closest('.skill-row').find('.jq_skill-skill-note').addClass('hidden');
                    $(this).closest('.skill-row').find('.jq_skill-skill-note-'+$(this).val()).removeClass('hidden');
                });
                
                var formateDatepickerY = {                                            
                                            autoclose:  true,
                                            format:     'yyyy',
                                            startDate:  moment().formateDate,
                                            startView: 'years',
                                            minViewMode: "years",
                                        };
                var select2ForSkillName = {
                                            minimumInputLength: 0,
                                            multiple: false,
                                            tags: true
                                        };
        
                $('.jq_skill_used').datepicker(formateDatepickerY);
                
                refreshSelect2Skill();
                
                $(document).on('click', '.js-main-tabs li a', function(){
                    if($(this).attr('href') == '#skills'){
                        refreshSelect2Skill();
                    }                    
                });
                                
                function refreshSelect2Skill(){
                    $('.jq_skill_name').each(function(){
                        if(!$(this).hasClass('select2-input-template'))
                            $(this).select2(select2ForSkillName);
                    });
                }
                
                $(document).on('click','.jq_skills-cat-btn-add',function (e){
                    e.preventDefault(); 
                    
                    if($(this).closest('.jq_skills-cat-box').hasClass('collapsed-box')){
                        $(this).closest('.jq_skills-cat-box').find('.jq_skills-cat-btn-expand').trigger('click');
                    }                    
                    
                    insertRow($(this).closest('.jq_skills-cat-box').attr('data-skills_cat'));
                });    
                                
                $(document).on('click','.jq_skill_row-delete-btn',function (e){
                    e.preventDefault(); 
                     
                    $(this).closest('.skill-row').remove();
                });    
                
                function insertRow(type){
                    var tmpBlock = $('#js-skills-row-template-'+type+' .skill-row').first().clone();                    
                    
                    tmpBlock.appendTo('.js-skills-box-body-'+type);
                    
                    var pickerInputY = $('.js-skills-box-body-'+type+' .skill-row').last().find('.jq_skill_used');
                    if(pickerInputY.length > 0){
                        pickerInputY.datepicker(formateDatepickerY);
                    }  
                    
                    var levelInput = $('.js-skills-box-body-'+type+' .skill-row').last().find('.jq_skill_name');                    
                    if(levelInput.length > 0){
                        levelInput.removeClass('select2-input-template').select2(select2ForSkillName);                                                
                    }                         

                    $('html, body').animate({scrollTop: $('.js-skills-box-body-'+type+' .skill-row').last().offset().top-100}, 500);
                
                }
                
                $(document).on('submit','#skills_form',function (e){
                    e.preventDefault();                     
                    $('.alert').remove();
                                        
                    $.post($(this).attr('action'), $(this).serialize())
                        .done(function(data) { 
                            $.each(data.skills, function(cat, skills){
                                $.each($('.js-skills-box-body-'+cat+' .jq_skill_id'), function(row, inputID){
                                    $(inputID).val(skills[row]['id']);                                    
                                });
                            });
                            
                            window.scrollTo( 0, 0 );
                            AddJsNotifi('success', '{{ __('Success') }}!', '{{ __('Updated successfully') }}!');                        
                        })
                        .fail(function(error) { 
                            window.scrollTo( 0, 0 );
                            if(typeof error.responseJSON.error == 'object' && Object.values(error.responseJSON.error).length >= 1){
                                $.each(error.responseJSON.error, function(key, val){
                                    AddJsNotifi('danger', '{{ __('Error') }}!', val); 
                                });
                            }else{
                                AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error saving form') }}'); 
                            }
                        });
                });
            });
        });
        
    </script>
@endpush