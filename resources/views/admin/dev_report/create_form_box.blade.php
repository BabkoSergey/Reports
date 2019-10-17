{!! Form::open(['method'=>'POST', 'route' => 'dev_reports.store', 'class' => 'form-horizontal', 'id' => 'jq_form-dev_reports']) !!}   
        {!! Form::hidden('date', $date) !!}

        <div class="col-lg-5">
            <div class="form-group">
                <label for="types" class="col-sm-2 control-label">{{ __('Type') }}*</label>

                <div class="col-sm-10">                            
                    {!! Form::select('types', $type, array_key_first($type), array('class' => 'form-control', 'id' => 'dev_reports-types', 'single', 'required')) !!}
                </div>     
            </div>

            <div class="form-group">
                <label for="resources" class="col-sm-2 control-label jq_task_resources-label">{{$type[array_key_first($type)]}}</label>

                <div class="col-sm-10">                    
                    <div class="input-group input-group-sm">                        
                        {!! Form::select('resources', $resources, null, array('placeholder' => __('...'), 'class' => 'form-control', 'id' => 'dev_reports-resources', 'single')) !!}                    
                        @if(Auth::user()->hasPermissionTo('append resources'))
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn-flat jq_report_resources-add"  data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Add new') }} {{$type[array_key_first($type)]}}" data-clean_title="{{ __('Add new') }}" data-url="{{ route('resource.create') }}?resource={{array_key_first($type)}}" data-clean_url="{{ route('resource.create') }}">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </span>  
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tasks" class="col-sm-2 control-label jq_task_resourse-label">{{ __('Task') }}</label>

                <div class="col-sm-10">
                    <div class="input-group input-group-sm">                        
                        {!! Form::select('tasks', $tasks, null, array('placeholder' => __('...'), 'class' => 'form-control', 'id' => 'dev_reports-tasks', 'required', 'single')) !!}    
                        @if(Auth::user()->hasPermissionTo('append tasks'))
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn-flat jq_report_tasks-add" disabled   data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Add new task') }}" data-url="" data-clean_url="{{ route('tasks.create') }}">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </span> 
                        @endif
                    </div>                            
                </div>
            </div>
            
            <div class="form-group">
                <label for="time_h" class="col-sm-2 control-label">{{ __('Hours') }}</label>

                <div class="col-sm-4">
                    {!! Form::number('time_h', null, array('placeholder' => __('Hours'), 'class' => 'form-control', 'min'=>0, 'id'=>'dev_reports-time_h', 'step'=>'1')) !!}                                      
                </div>
            
                <label for="time_m" class="col-sm-2 control-label">{{ __('Minutes') }}</label>

                <div class="col-sm-4">
                    {!! Form::number('time_m', null, array('placeholder' => __('Minutes'), 'class' => 'form-control', 'min'=>0, 'max'=>59, 'id'=>'dev_reports-time_m', 'step'=>'5')) !!}                                      
                </div>
            </div>
            
        </div>
        
        <div class="col-lg-7">
            <div class="form-group">
                <label for="is_done" class="col-sm-2 control-label">{{ __('Is Done') }}*</label>

                <div class="col-sm-10">
                    {!! Form::textarea('is_done', null, ['placeholder' => __('Is Done'), 'class' => 'form-control', 'id'=>'dev_reports-is_done', 'required', 'rows'=> '4']) !!}                            
                </div>
            </div> 

            <div class="form-group">
                <label for="note" class="col-sm-2 control-label">{{ __('Note') }}</label>

                <div class="col-sm-10">
                    {!! Form::textarea('note', null, ['placeholder' => __('Note'), 'class' => 'form-control cke-textarea', 'id'=>'dev_reports-note', 'rows'=> '3']) !!}                            
                </div>
            </div> 
        </div>
{!! Form::close() !!}

@push('styles')    

@endpush

@push('scripts')            
    <script>        
        $(function () { 
                var resourcesParams = {_token: $("input[name=_token]").val()};
                
                $(document).on('change, keyup', '#dev_reports-time_m, #dev_reports-edit-time_h', function(){                    
                   if(parseInt($(this).val()) > 59) $(this).val(59);
                });
                
                $(document).on('change', '#dev_reports-types', function(){                    
                    clearSelect('dev_reports-resources,dev_reports-tasks');
                    updateSelect();
                    ClearCheckReqireFields();
                    
                    $('.jq_task_resources-label').text($(this).find('option:selected').text());
                    
                });
                
                $(document).on('change', '#dev_reports-resources', function(){                    
                    ClearCheckReqireFields();
                    clearSelect('dev_reports-tasks');
                    updateSelect();
                });
                
                
                function clearSelect(ids){
                    $.each(ids.split(','), function(key, id){
                        $('#'+id).html('<option selected="selected" value="">...</option>');
                    });      
                }
                
                function renderSelect(id, data){console.log(id);
                    $.each(data, function(key, val){
                        $('#'+id).append('<option value="'+key+'">'+val+'</option>');
                    });                    
                }
                
                function updateSelect(){    
                    getResourcesParamsVar();
                    
                    $('.jq_report_resources-add').attr('data-url', $('.jq_report_resources-add').attr('data-clean_url') + '?resource=' + resourcesParams.types);
                    var TypeName = '';
                    $('#dev_reports-types option').each(function(){
                        if($(this).prop('selected')) TypeName = $(this).text();
                    });
                    $('.jq_report_resources-add').attr('data-title', $('.jq_report_resources-add').attr('data-clean_title') + ' ' + TypeName);
                    
                    $('.jq_report_tasks-add').attr('data-url', $('.jq_report_tasks-add').attr('data-clean_url') + '?type=' + resourcesParams.types + '&resourse=' + resourcesParams.resources);
                                        
                    $.post("{{route('dev_reports.get_resources_list')}}", resourcesParams)
                        .done(function(data) {
                            $('#dev_reports-resources').prop('disabled', data.disabled);
                            $('.jq_report_resources-add').prop('disabled', data.disabled);
                            $('.jq_report_tasks-add').prop('disabled', (data.update == 'tasks' ? false : true));
                            
                            renderSelect('dev_reports-'+data.update, data[data.update]);                            
                        })
                        .fail(function(error) {          
                            AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error get data') }}');  
                        });
                }
            
                function getResourcesParamsVar(){
                    resourcesParams.types = $('#dev_reports-types').val();
                    resourcesParams.resources = $('#dev_reports-resources').val();
                }
        });
    </script>
@endpush