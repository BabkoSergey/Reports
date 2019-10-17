{!! Form::model($estimate, ['method' => 'PATCH','route' => ['estimates.update', $estimate->id], 'class' => 'form-horizontal'] ) !!}        
    <div class="box-body">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">{{__('Title')}}*</label>

            <div class="col-sm-10">                            
                {!! Form::text('name', $estimate->name, ['placeholder' => __('Title'),'class' => 'form-control', 'required']) !!}
            </div>     
        </div>

        <div class="form-group">
            <label for="status" class="col-sm-2 control-label">{{__('Condition')}}*</label>

            <div class="col-sm-10">
                {!! Form::select('status', array(0=>__('Inactive'), 1=>__('Active')), $estimate->status, array('class' => 'form-control','single', 'required')) !!}
            </div>
        </div>
        
        <div class="form-group">
            <label for="view" class="col-sm-2 control-label">{{__('Type')}}*</label>

            <div class="col-sm-10">
                {!! Form::select('view', $views , $estimate->view , array('class' => 'form-control','single', 'required')) !!}
            </div>
        </div>
        
        <div class="form-group">
            <label for="note" class="col-sm-2 control-label">{{ __('Note') }}</label>

            <div class="col-sm-10">
                {!! Form::textarea('note', $estimate->note, ['placeholder' => __('Note'), 'class' => 'form-control cke-textarea', 'id' =>'estimates-note', 'rows'=> '3']) !!}                            
            </div>
        </div>
        
    </div> 
{!! Form::close() !!}
