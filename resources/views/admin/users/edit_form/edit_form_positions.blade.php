{!! Form::model($position, ['method' => 'PATCH','route' => ['positions.update', $position->id], 'class' => 'form-horizontal']) !!}
    <div class="box-body">
        
        <div class="form-group">
            <label for="roles" class="col-sm-2 control-label">{{ __('Position') }}</label>

            <div class="col-sm-10">
                {!! Form::select('position', $params['positions'], $position->position, array('class' => 'form-control select2', 'single', 'required')) !!}                            
            </div>
        </div> 
        
        <div class="form-group">

            <label for="birthday" class="col-sm-2 control-label">{{ __('From') }}</label>

            <div class="col-sm-10">
                {!! Form::text('from', date('Y-m-d', strtotime($position->from)), array('class' => 'form-control jq_position-from', 'required')) !!}                            
            </div>
        </div>
        
    </div>
{!! Form::close() !!}