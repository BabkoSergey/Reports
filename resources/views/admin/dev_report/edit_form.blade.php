{!! Form::model($report, ['method' => 'PATCH','route' => ['dev_reports.update', $report->id], 'class' => 'form-horizontal'] ) !!}        
    <div class="box-body">
        <div class="form-group">
            <label for="time_h" class="col-sm-2 control-label">{{ __('Hours') }}</label>

            <div class="col-sm-4">
                {!! Form::number('time_h', date('H', strtotime($report->time)), array('placeholder' => __('Hours'), 'class' => 'form-control', 'min'=>0, 'id'=>'dev_reports-edit-time_h', 'step'=>'1')) !!}                                      
            </div>

            <label for="time_m" class="col-sm-2 control-label">{{ __('Minutes') }}</label>

            <div class="col-sm-4">
                {!! Form::number('time_m', date('i', strtotime($report->time)), array('placeholder' => __('Minutes'), 'class' => 'form-control', 'min'=>0, 'max'=>59, 'id'=>'dev_reports-edit-time_m', 'step'=>'5')) !!}                                      
            </div>
        </div>

        <div class="form-group">
            <label for="is_done" class="col-sm-2 control-label">{{ __('Is Done') }}*</label>

            <div class="col-sm-10">
                {!! Form::textarea('is_done', $report->is_done, ['placeholder' => __('Is Done'), 'class' => 'form-control', 'id'=>'dev_reports-edit-is_done', 'required', 'rows'=> '4']) !!}                            
            </div>
        </div> 

        <div class="form-group">
            <label for="note" class="col-sm-2 control-label">{{ __('Note') }}</label>

            <div class="col-sm-10">
                {!! Form::textarea('note', $report->note, ['placeholder' => __('Note'), 'class' => 'form-control cke-textarea', 'id'=>'dev_reports-edit-note', 'rows'=> '3']) !!}                            
            </div>
        </div> 

    </div> 
{!! Form::close() !!}
