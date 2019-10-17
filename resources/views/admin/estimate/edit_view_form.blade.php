{!! Form::model($estimate, ['method' => 'PATCH','route' => ['estimates.update.view-status', $estimate->id], 'class' => 'cust-form-inline-flex pull-right'] ) !!} 
    <div class="input-group input-group-sm">
        {!! Form::select('view', $views , $estimate->view , array('class' => 'form-control','single', 'required')) !!}
        <span class="input-group-btn">
            <button type="submit" class="btn btn-warning btn-flat">
                <i class="fa fa-exchange"></i>
            </button>
        </span>
    </div>        
{!! Form::close() !!}
