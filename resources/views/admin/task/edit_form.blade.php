{!! Form::model($task, ['method' => 'PATCH','route' => ['tasks.update', $task->id], 'class' => 'form-horizontal'] ) !!}        
    <div class="box-body">                
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">{{__('Title')}}*</label>

            <div class="col-sm-10">                            
                {!! Form::text('name', $task->name, ['placeholder' => __('Title'),'class' => 'form-control', 'required']) !!}
            </div>     
        </div>

        <div class="form-group">
            <label for="todo" class="col-sm-2 control-label">{{__('ToDo')}}</label>

            <div class="col-sm-10">
                {!! Form::textarea('todo', $task->todo, ['placeholder' => __('ToDo'), 'class' => 'form-control', 'rows'=> '3']) !!}                            
            </div>
        </div>
        
        <div class="form-group">
            <label for="note" class="col-sm-2 control-label">{{ __('Note') }}</label>

            <div class="col-sm-10">
                {!! Form::textarea('note', $task->note, ['placeholder' => __('Note'), 'class' => 'form-control', 'rows'=> '3']) !!}                            
            </div>
        </div> 
        
    </div> 
{!! Form::close() !!}
