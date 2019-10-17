{!! Form::model($userInfo, ['method' => 'PATCH','route' => ['user_info.update', $userInfo->id], 'class' => 'form-horizontal', 'id'=>'info_form']) !!}
    <div class="box-body">
        <div class="row">
            <div class="form-group">
                <label for="birthday" class="col-sm-2 control-label">{{ __('Birthday') }}</label>

                <div class="col-sm-10">
                    {!! Form::text('birthday', $userInfo->birthday, array('placeholder' => __('Birthday'),'class' => 'form-control', 'id' => 'birthday')) !!}                            
                </div>
            </div>
            
            <div class="form-group">
                <label for="address" class="col-sm-2 control-label">{{ __('Address') }}</label>

                <div class="col-sm-10">
                    {!! Form::text('address', $userInfo->address, array('placeholder' => __('Address'),'class' => 'form-control')) !!}                            
                </div>
            </div>

            <div class="form-group">
                <label for="gender" class="col-sm-2 control-label">{{ __('Gender') }}</label>

                <div class="col-sm-10">
                    {!! Form::select('gender', $userInfo->enums['gender'], $userInfo->gender, array('placeholder' => '...', 'class' => 'form-control', 'single')) !!}                            
                </div>
            </div>
            
            <div class="form-group">
                <label for="marital" class="col-sm-2 control-label">{{ __('Marital') }}</label>

                <div class="col-sm-10">
                    {!! Form::select('marital', $userInfo->enums['marital'], $userInfo->marital, array('placeholder' => '...', 'class' => 'form-control', 'single')) !!}                            
                </div>
            </div>
            
            <div class="form-group">
                <div id="js-template-row-children" style="display: none;">@include('admin.users.edit_form.info_row_children')</div>
                
                <label for="children" class="col-sm-2 control-label">{{ __('Сhildren') }}</label>

                <div class="col-sm-10 js-info-box-json js-info-box-children">                    
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success pull-right jq_info-btn-add" data-row_type="children">
                                + {{ __('Add new') }}
                            </button>
                            {!! Form::hidden('children', $userInfo->children, array('id'=>'children')) !!}
                        </div>
                    </div>
                    
                    @foreach(json_decode($userInfo->children) ?? [] as $children)                        
                        @include('admin.users.edit_form.info_row_children')
                    @endforeach
                    
                </div>                
            </div>
            
            <div class="form-group">
                <div id="js-template-row-languages" style="display: none;">@include('admin.users.edit_form.info_row_languages')</div>
                
                <label for="languages" class="col-sm-2 control-label">{{ __('Languages') }}</label>

                <div class="col-sm-10 js-info-box-json js-info-box-languages">                    
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success pull-right jq_info-btn-add" data-row_type="languages">
                                + {{ __('Add new') }}
                            </button>
                            {!! Form::hidden('languages', $userInfo->languages, array('id'=>'languages')) !!}
                        </div>
                    </div>
                    
                    @foreach(json_decode($userInfo->languages) ?? [] as $languages)                        
                        @include('admin.users.edit_form.info_row_languages')
                    @endforeach
                    
                </div>                
            </div>
            
            <div class="form-group">
                <div id="js-template-row-education" style="display: none;">@include('admin.users.edit_form.info_row_education')</div>
                
                <label for="education" class="col-sm-2 control-label">{{ __('Education') }}</label>

                <div class="col-sm-10 js-info-box-json js-info-box-education">                    
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success pull-right jq_info-btn-add" data-row_type="education">
                                + {{ __('Add new') }}
                            </button>
                            {!! Form::hidden('education', $userInfo->education, array('id'=>'education')) !!}
                        </div>
                    </div>
                    
                    @foreach(json_decode($userInfo->education) ?? [] as $education)                        
                        @include('admin.users.edit_form.info_row_education')
                    @endforeach
                    
                </div>
            </div>
            
            <div class="form-group">
                <div id="js-template-row-courses" style="display: none;">@include('admin.users.edit_form.info_row_courses')</div>
                
                <label for="courses" class="col-sm-2 control-label">{{ __('Courses') }}</label>

                <div class="col-sm-10 js-info-box-json js-info-box-courses">                    
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success pull-right jq_info-btn-add" data-row_type="courses">
                                + {{ __('Add new') }}
                            </button>
                            {!! Form::hidden('courses', $userInfo->courses, array('id'=>'courses')) !!}
                        </div>
                    </div>
                    
                    @foreach(json_decode($userInfo->courses) ?? [] as $courses)                        
                        @include('admin.users.edit_form.info_row_courses')
                    @endforeach
                    
                </div>                
            </div>            
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
                var formateDatepicker = {
                                            autoclose:  true,
                                            format:     'yyyy-mm-dd',
                                            startDate:  moment().formateDate,
                                            startView: 'years',
                                        };
                var formateDatepickerY = {                                            
                                            autoclose:  true,
                                            format:     'yyyy',
                                            startDate:  moment().formateDate,
                                            startView: 'years',
                                            minViewMode: "years",
                                        };
                var formateDatepickerM = {                                            
                                            autoclose:  true,
                                            showButtonPanel: false,
                                            format:     'mm',
                                            startDate:  moment().formateDate,
                                            startView: 'months',
                                            minViewMode: "months",
                                        };
                var formateDatepickerD = {                                            
                                            autoclose:  true,
                                            format:     'dd',
                                            startDate:  moment().formateDate,
                                            startView: 'days',
                                            minViewMode: "days",
                                        };
        
                $('#birthday, .jq_children_birthday').datepicker(formateDatepicker);
                $('.jq_education_y').datepicker(formateDatepickerY);
                $('.jq_education_m').datepicker(formateDatepickerM);
                $('.jq_education_d').datepicker(formateDatepickerD);
                
                refreshSelect2();
                
                $(document).on('click', '.js-main-tabs li a', function(){
                    if($(this).attr('href') == '#info'){
                        refreshSelect2();
                    }                    
                });
                
                function refreshSelect2(){
                    $('.jq_languages_ln').select2({
                            minimumInputLength: 0,
                            multiple: false
                        });
                }
                        
                $(document).on('change','.jq_languages_level',function (e){
                    e.preventDefault(); 
                     
                    $(this).closest('.info-row').find('.jq_info-languages-note').addClass('hidden');
                    $(this).closest('.info-row').find('.jq_info-languages-note-'+$(this).val()).removeClass('hidden');
                });    
                                
                $(document).on('click','.jq_info-btn-add',function (e){
                    e.preventDefault(); 
                     
                    insertRow($(this).attr('data-row_type'));
                });    
                
                $(document).on('click','.jq_info-row-btn-delete',function (e){
                    e.preventDefault(); 
                     
                    $(this).closest('.info-row').remove();
                });    
                
                function insertRow(type){
                    var tmpBlock = $('#js-template-row-'+type+' .info-row').first().clone();                    
                    
                    tmpBlock.appendTo('.js-info-box-'+type);
                    
                    var pickerInput = $('.js-info-box-'+type+' .info-row').last().find('.jq_children_birthday');
                    if(pickerInput.length > 0){
                        pickerInput.datepicker(formateDatepicker);
                    }     
                    
                    var pickerInputY = $('.js-info-box-'+type+' .info-row').last().find('.jq_education_y');
                    if(pickerInputY.length > 0){
                        pickerInputY.datepicker(formateDatepickerY);
                    }  
                    
                    var pickerInputM = $('.js-info-box-'+type+' .info-row').last().find('.jq_education_m');
                    if(pickerInputM.length > 0){
                        pickerInputM.datepicker(formateDatepickerM);
                    }  
                    
                    var pickerInputD = $('.js-info-box-'+type+' .info-row').last().find('.jq_education_d');
                    if(pickerInputD.length > 0){
                        pickerInputD.datepicker(formateDatepickerD);
                    }  
                    
                    var lengInput = $('.js-info-box-'+type+' .info-row').last().find('.jq_languages_ln-none');
                    if(lengInput.length > 0){
                        lengInput.toggleClass('jq_languages_ln-none jq_languages_ln');
                        refreshSelect2();
                    }                         
                }
                
                $(document).on('submit','#info_form',function (e){
                    e.preventDefault();                     
                    $('.alert').remove();
                    
                    $('.js-info-box-json').each(function(){
                        var data = [];
                        var type = $(this).find('.jq_info-btn-add').attr('data-row_type');
                        
                        $(this).find('.js-info-'+type+'-row').each(function(){
                            var row = {};
                                $(this).find('.jq_row_input').each(function(){
                                    row[$(this).attr('data-name')] = $(this).val();
                                });
                            data.push(row);
                        });
                        
                        $(this).find('#'+type).val(data.length > 0 ? JSON.stringify({ ...data }) : '');                        
                    });
                    
                    $.post($(this).attr('action'), $(this).serialize())
                        .done(function(data) { 
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