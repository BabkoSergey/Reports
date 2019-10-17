<div class="jq_tasks-body" data-resource="{{ $resource->id ?? '' }}">          
    @foreach($resource->getTasks->sortByDesc('created_at') as $task)
        @include('admin.task.show_single', $task)
    @endforeach    
</div>  

@if(Auth::user()->hasAnyPermission(['add tasks']))
    @php unset($task); @endphp
    <div id="js-template-task" style="display: none;">        
        @include('admin.task.show_single')
    </div>
@endif

@if(Auth::user()->hasPermissionTo('delete tasks'))
    <div style="display: none">
        <form id="jq_task-delete-form" method="POST" action="" data-url="{{ url('/admin/tasks/') }}" accept-charset="UTF-8">
            @csrf
            <input name="_method" type="hidden" value="DELETE">    
            <input class="btn btn-danger" type="submit" value="Delete">
        </form>
    </div>
@endif

@push('styles')    

@endpush

@push('scripts') 
    
    <script>
        $(function () {
            $(document).on('click', '.jq_task-title-text', function(e){
               e.preventDefault();
               
               $(this).closest('.box').find('.btn-collapsed-box-collapse').trigger('click');
            });
            
            @if(Auth::user()->hasAnyPermission(['edit tasks', 'add tasks']))
                $("#modal-actions").on('hidden.bs.modal', function (e) {
                    var response = $("#modal-actions").attr('data-response');

                    if(!response) return false;
                    
                    response = JSON.parse(response);
                    
                    if(response.task){
                        if(response.type == 'store'){
                            var taskBox = $('#js-template-task .box').first().clone();
                        
                            taskBox.attr('id', 'task-' + response.task.id).attr('data-task', response.task.id);
                            taskBox.find('.jq_task-edit-btn').attr('data-url', taskBox.find('.jq_task-edit-btn').attr('data-url').substring(0, taskBox.find('.jq_task-edit-btn').attr('data-url').length - 5)+ response.task.id + '/edit');                            
                            taskBox.find('.jq_task-delete').attr('data-jq_task', response.task.id);
                            
                            taskBox.prependTo('.jq_tasks-body');                              
                        }                        
                        updateTaskBox(response);
                        
                        AddJsNotifi('success', '{{ __('Success') }}!', response.success);
                    }
                });
                
            @endif
            
            @if(Auth::user()->hasPermissionTo('delete tasks'))
                
                $(document).on('click','.jq_task-delete',function (e){
                    e.preventDefault(); 
                    $('#jq_task-delete-form').attr('action',$('#jq_task-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_task'));
                    confirmDeleteFloor($(this));                    
                });       
                
                function confirmDeleteFloor(eElement){
                    var dialog = bootbox.dialog({
                        title: "{{__('Are you sure you want to delete task?')}}",
                        message: "<p>{{__('All supported task info will be deleted!')}}</p>",
                        buttons: {
                            cancel: {
                                label: "{{__('Cancel')}}",
                                className: 'btn-default pull-left',
                                callback: function(){
                                }
                            },                    
                            delere: {
                                label: "{{__('Delete')}}",
                                className: 'btn-danger pull-right',
                                callback: function(){      
                                    var form = $('#jq_task-delete-form');
                                    
                                    $.post(form.attr('action'),  form.serialize())
                                        .done(function(data) {                                              
                                            eElement.closest('.jq_task-box').remove();
                                            AddJsNotifi('success', '{{ __('Success') }}!', data.success);
                                        })
                                        .fail(function(error) {                                       
                                            AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error delete task') }}');
                                        });
                                }
                            }
                        }
                    });
                }
            
            @endif
           
            function updateTaskBox(response){
                var taskBoxTemplate = $('#js-template-task');
                var taskBox = $('#task-'+response.task.id);
                
                taskBox.find('.jq_task-title-text').text(response.task.name);
                taskBox.find('.jq_task-edit-btn').attr('data-title', taskBoxTemplate.find('.jq_task-edit-btn').attr('data-title') + ' ' + response.task.name);
                taskBox.find('.jq_task-footer-note').text(response.task.note);
                taskBox.find('.jq_task-footer-todo').text(response.task.todo);
            }
                    
        });        
    </script>
@endpush