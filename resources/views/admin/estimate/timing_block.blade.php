<div class="box box-default jq_timing-block" id="jq_timing_block-" data-timing_block="">
    <div class="box-header with-border">
        <h3 class="box-title text-primary jq_timing-block-title-text">
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Title')]) !!}
        </h3>                
        <div class="box-tools pull-right">                        
            <button type="button" class="btn btn-danger btn-sm jq_timing_block-delete-btn" data-jq_timing_block="{{ $timingKey ?? '' }}">
                <i class="fa fa-trash"></i>
            </button>
        </div>              
    </div>

    <div class="box-body jq_timing-block-body">        
        @include('admin.estimate.timing_block_table_edit')
        @include('admin.estimate.timing_block_note_edit')
    </div>                
</div>