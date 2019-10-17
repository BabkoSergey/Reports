<div class="box box-default row-box collapsed-box jq_task-box" id="task-{{ $task->id ?? '' }}" data-task="{{ $task->id ?? '' }}">
    <div class="box-header with-border">
        <h3 class="box-title task-title text-primary jq_task-title-text">{{ $task->name ?? '' }}</h3>                
        <div class="box-tools pull-right">            
            @if(Auth::user()->hasPermissionTo('delete tasks'))
                <button type="button" class="btn btn-danger btn-sm jq_task-delete" data-jq_task="{{ $task->id ?? '' }}">
                    <i class="fa fa-trash"></i>
                </button>                    
            @endif
            
            @if(Auth::user()->hasPermissionTo('edit tasks'))
                <button type="button" class="btn btn-primary btn-sm jq_task-edit-btn" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Edit task') }}" data-url="{{ url('admin/tasks') }}/{{ $task->id ?? '' }}/edit">
                    <i class="fa fa-pencil"></i>
                </button>
            @endif                    
            
            <button type="button" class="btn btn-box-tool btn-collapsed-box-collapse">
                <i class="fa fa-plus"></i>
            </button>
        </div>              
    </div>

    <div class="box-footer">
        <div class="row form-horizontal">
            <div class="form-group">
                <label for="type" class="col-md-1 control-label">{{__('ToDo')}}:</label>
                <div class="col-sm-11">                            
                    <p class="control-content jq_task-footer-todo">{!! $task->todo ?? '' !!}</p>
                </div>     
            </div>
            
            <div class="form-group">
                <label for="type" class="col-md-1 control-label">{{__('Note')}}:</label>
                <div class="col-sm-11">                            
                    <p class="control-content jq_task-footer-note">{!! $task->note ?? '' !!}</p>
                </div>     
            </div>
        </div>        
    </div>
</div>