{!! Form::model($project, ['method' => 'PATCH','route' => ['projects.update', $project->id], 'class' => 'form-horizontal'] ) !!}        
    <div class="box-body">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">{{__('Title')}}*</label>

            <div class="col-sm-10">                            
                {!! Form::text('name', $project->name, ['placeholder' => __('Title'),'class' => 'form-control', 'required']) !!}
            </div>     
        </div>

        <div class="form-group">
            <label for="status" class="col-sm-2 control-label">{{__('Condition')}}*</label>

            <div class="col-sm-10">
                {!! Form::select('status', array(0=>__('Inactive'), 1=>__('Active')), $project->status, array('class' => 'form-control','single', 'required')) !!}
            </div>
        </div>
        
        <div class="form-group">
            <label for="note" class="col-sm-2 control-label">{{ __('Note') }}</label>

            <div class="col-sm-10">
                {!! Form::textarea('note', $project->note, ['placeholder' => __('Note'), 'class' => 'form-control', 'rows'=> '3']) !!}                            
            </div>
        </div>
        
    </div> 
{!! Form::close() !!}
