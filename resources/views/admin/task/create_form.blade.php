{!! Form::open(['method'=>'POST', 'route' => 'tasks.store', 'class' => 'form-horizontal']) !!}                
    <div class="box-body">
        @if(!is_array($type))
            {!! Form::hidden('type', $type) !!}            
            {!! Form::hidden('resourse', $resourse) !!}
        @else
            <div class="form-group">
                <label for="type" class="col-sm-2 control-label">{{__('Type')}}*</label>

                <div class="col-sm-10">                            
                    {!! Form::select('type', $type, array_key_first($type), array('class' => 'form-control','single', 'required')) !!}
                </div>     
            </div>
        
            <div class="form-group">
                <label for="resourse" class="col-sm-2 control-label">{{__('resourse')}}*</label>

                <div class="col-sm-10">
                    {!! Form::text('resourse', null, ['placeholder' => __('resourse'),'class' => 'form-control', 'disabled']) !!}
                </div>
            </div>
        @endif
        
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">{{__('Title')}}*</label>

            <div class="col-sm-10">                            
                {!! Form::text('name', null, ['placeholder' => __('Title'),'class' => 'form-control', 'required']) !!}
            </div>     
        </div>

        <div class="form-group">
            <label for="todo" class="col-sm-2 control-label">{{__('ToDo')}}</label>

            <div class="col-sm-10">
                {!! Form::textarea('todo', null, ['placeholder' => __('ToDo'), 'class' => 'form-control', 'rows'=> '3']) !!}                            
            </div>
        </div>
        
        <div class="form-group">
            <label for="note" class="col-sm-2 control-label">{{ __('Note') }}</label>

            <div class="col-sm-10">
                {!! Form::textarea('note', null, ['placeholder' => __('Note'), 'class' => 'form-control', 'rows'=> '3']) !!}                            
            </div>
        </div> 
        
    </div>                
{!! Form::close() !!}