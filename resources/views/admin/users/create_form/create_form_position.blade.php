{!! Form::open(array('method'=>'POST', 'route' => 'positions.store', 'class' => 'form-horizontal')) !!}
    <div class="box-body">
        {!! Form::hidden('user_id', $params['user_id']) !!}

        <div class="form-group">
            <label for="roles" class="col-sm-2 control-label">{{ __('Position') }}</label>

            <div class="col-sm-10">
                {!! Form::select('position', $params['positions'], null, array('class' => 'form-control select2', 'single', 'required')) !!}                            
            </div>
        </div> 
        
        <div class="form-group">

            <label for="birthday" class="col-sm-2 control-label">{{ __('From') }}</label>

            <div class="col-sm-10">
                {!! Form::text('from', date('Y-m-d', time()), array('class' => 'form-control jq_position-from', 'required')) !!}                            
            </div>
        </div>
        
    </div>
{!! Form::close() !!}