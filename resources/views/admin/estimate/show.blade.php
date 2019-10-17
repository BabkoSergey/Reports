@extends('admin.layouts.app')

@section('htmlheader_title') {{$estimate->name}} @endsection

@section('sub_title') @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        @include('admin.templates.action_notifi')
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Info') }}</h3>
                
                <div class="box-tools pull-right">
                    @if(Auth::user()->hasPermissionTo('edit estimates'))
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Edit estimate') }} {{$estimate->name}}" data-url="{{ url('admin/estimates') }}/{{$estimate->id}}/edit">
                            <i class="fa fa-pencil"></i>
                        </button>
                    @endif
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>                    
                </div>              
            </div>
            
            <div class="box-body">                
                <h4 class="box-title js-project-name">{{$estimate->name}}</h4>
                <h4 class="js-project-status">{!! $estimate->status ? '<span class="text-green">'.__('Active').'</span>' : '<span class="text-red">'.__('Inactive').'</span>' !!}</h4>
                <p class="js-project-note">{!! $estimate->note !!}</p>                              
            </div>            
        </div>
    </div>    
</div>

<h2 class="page-header">
    <span>{{ __('Estimate tasks') }} <small>({{ __('additional')}})</small></span>
    
    @if(Auth::user()->hasPermissionTo('add tasks'))
        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Add new task') }}" data-url="{{ route('tasks.create') }}?resourse={{$estimate->id ?? ''}}&type=estimate">
            + {{ __('Add new task') }}
        </button>
    @endif
        
</h2>

<div class="row">
    <div class="col-md-12">
        @include('admin.task.show_box', $resource = $estimate)
    </div>    
</div>

@if(Auth::user()->hasPermissionTo('edit estimates') || (Auth::user()->hasPermissionTo('fill estimates') && $estimate->view == 'estimate-dev') )
<h2 class="page-header">
    <span>{{ __('Timing') }}</span>
    
    @if(Auth::user()->hasPermissionTo('edit estimates'))
        @include('admin.estimate.edit_view_form')
    @endif        
    @if(Auth::user()->hasPermissionTo('edit estimates') && $estimate->view != 'estimate-dev' && $estimate->timing)
        <a href="{{route('estimates.pdf', ['id'=>$estimate->id])}}" target="_blank" class="btn btn-primary pull-right margin-r-5" style="height: 30px; padding: 4px 7px;">
            <i class="fa fa-file-pdf-o"></i>
        </a>
        <a href="{{route('estimates.xls', ['id'=>$estimate->id])}}" target="_blank" class="btn btn-primary pull-right margin-r-5" style="height: 30px; padding: 4px 7px;">
            <i class="fa fa-file-excel-o"></i>
        </a>
    @endif        
    
    @include('admin.estimate.timing_totals')
    
</h2>

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            @if((Auth::user()->hasPermissionTo('edit estimates') && $estimate->view != 'estimate-customer') || (Auth::user()->hasPermissionTo('fill estimates') && $estimate->view == 'estimate-dev') )                
                <div class="box-header with-border">
                    <button type="button" class="btn btn-info pull-left jq_timing-add-block">+ {{ __('Add block') }}</button>
                </div>
            @endif
            
            <div class="box-body jq_timing-content">  
                @if(Auth::user()->hasPermissionTo('edit estimates') && $estimate->view == 'estimate-customer' && $estimate->timing)
                <table class="table table-striped bg-gray"> 
                    <thead class="thead-dark bg-light-blue">
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('Task') }}</th>                        
                            <th class="text-center">{{ __('Optimistic') }}</th>
                            <th class="text-center">{{ __('Pessimistic') }}</th>
                            <th>{{ __('Comments') }}</th>                
                        </tr>
                    </thead>   
                    <tbody>       
                        @foreach(json_decode($estimate->timing)->blocks as $timingShowBlock)                        
                            @if($timingShowBlock->type == 'table')                                
                                @include('admin.estimate.timing_block_table')
                            @else
                                @include('admin.estimate.timing_block_note')
                            @endif
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>            
            
            @if((Auth::user()->hasPermissionTo('edit estimates') && $estimate->view != 'estimate-customer') || (Auth::user()->hasPermissionTo('fill estimates') && $estimate->view == 'estimate-dev') )                
                <div class="box-footer with-border">
                    <button type="button" class="btn btn-info pull-left jq_timing-add-block">+ {{ __('Add block') }}</button>
                </div>
            @endif
            
        </div>
    </div>    
</div>

@if((Auth::user()->hasPermissionTo('edit estimates') && $estimate->view != 'estimate-customer') || (Auth::user()->hasPermissionTo('fill estimates') && $estimate->view == 'estimate-dev') )                
    <div id="js-template-timing-block" style="display: none;">        
        @include('admin.estimate.timing_block_edit')
    </div>
@endif

@endif


@if(Auth::user()->hasAnyPermission(['edit estimates', 'add tasks']))
    @include('admin.templates.modal_actions')
@endif

@endsection

@push('styles')    
    
@endpush

@push('scripts') 
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    
    <script>
        var timing = JSON.parse(@json($estimate->timing));
        var token = $("input[name=_token]").val();
        var estStatus = '{{$estimate->view}}';
                
        if(!timing || typeof timing !== 'object'){
            timing = {'order' : [], 'blocks' : {}};
        }
        
        var blockTextContentTimer, blockTitleTimer, blockTableContentTimer;
        
        $(function () {  
        
            @if(Auth::user()->hasPermissionTo('edit estimates') || (Auth::user()->hasPermissionTo('fill estimates') && $estimate->view == 'estimate-dev') )                
                
                updateTimig();
            
                Echo.private('estimates.{{$estimate->id}}')
                    .listen('editEstimateDevTiming', (e) => {                       
                        if(estStatus !== e.status){
                            window.location.reload(true);
                        }
                        if(e.timing){                              
                            timing = JSON.parse(e.timing);
                            updateTimig();
                        }                        
                    });
                
                $(document).on('click', '.jq_timing-add-block', function(){
                     choiceBlockType();
                });
            
                $(document).on('click', '.jq_timing_block-delete-btn', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');                    
                    
                    removeDataBlock(blockId);
                    updateTimig();
                });
                        
                $(document).on('click', '.jq_timing_block-add-row-btn', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');                    
                    appendDataRow(blockId);
                    updateBlock(blockId);
                });
                
                $(document).on('click', '.jq_timing_block-delete-row-btn', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');    
                    var rowId = $(this).closest('.jq_timing-block-table-row').attr('data-timing_row');    
                    
                    removeDataRow(blockId, rowId);
                    updateBlock(blockId);                    
                });
                
                $(document).on('change', '.jq_timing-block-text-content', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');    
                    
                    updateDataTextContent(blockId, $(this).val());
                });
                
                $(document).on('change', '.jq_timing-block-title', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');    
                    
                    updateDataBlockTitle(blockId, $(this).val());
                });
                
                $(document).on('keyup', '.jq_timing-block-text-content', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');    
                    var newBlockContent = $(this).val();
                    
                    clearTimeout(blockTextContentTimer);
                    blockTextContentTimer = setTimeout(function() { updateDataTextContent(blockId, newBlockContent); }, 200);                    
                });
                
                $(document).on('keyup', '.jq_timing-block-title', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');    
                    var newBlockTitle = $(this).val();
                    
                    clearTimeout(blockTitleTimer);
                    blockTitleTimer = setTimeout(function() { updateDataBlockTitle(blockId, newBlockTitle); }, 200);                    
                });
                
                $(document).on('change', '.jq_timing-block-table-row input, .jq_timing-block-table-row textarea', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');
                    var rowId = $(this).closest('.jq_timing-block-table-row').attr('data-timing_row');
                    var rowContent = {};
                    $('#jq_timing-block-table-row-'+rowId+' input,#jq_timing-block-table-row-'+rowId+' textarea').each(function(){
                        rowContent[$(this).attr('name')] = $(this).val();
                    });
                                                                     
                    updateDataTableRowContent(blockId, rowId, rowContent);
                    updateTableBlocktotal(blockId);
                });
                
                $(document).on('keyup', '.jq_timing-block-table-row input, .jq_timing-block-table-row textarea', function(){                    
                    var blockId = $(this).closest('.jq_timing-block').attr('data-timing_block');
                    var rowId = $(this).closest('.jq_timing-block-table-row').attr('data-timing_row');
                    var rowContent = {};
                    $('#jq_timing-block-table-row-'+rowId+' input,#jq_timing-block-table-row-'+rowId+' textarea').each(function(){
                        rowContent[$(this).attr('name')] = $(this).val();
                    });
                                
                    clearTimeout(blockTableContentTimer);
                    blockTableContentTimer = setTimeout(function() { 
                        updateDataTableRowContent(blockId, rowId, rowContent);
                        updateTableBlocktotal(blockId);
                    }, 200);                    
                });
                                
                function choiceBlockType(){
                    var choiceDialog = bootbox.dialog({
                        title: "{{__('Add block')}}",
                        message: "<p>{{__('Select the type of block to add!')}}</p>",
                        buttons: {
                            cancel: {
                                label: "{{__('Cancel')}}",
                                className: 'btn-default pull-left',
                                callback: function(){
                                }
                            },                    
                            text: {
                                label: "{{__('Text block')}}",
                                className: 'btn-primary pull-right',
                                callback: function(){      
                                    addDataBlock('text');
                                }
                            },
                            table: {
                                label: "{{__('Table block')}}",
                                className: 'btn-primary pull-right',
                                callback: function(){      
                                    addDataBlock('table');
                                }
                            }
                        }
                    });
                }
                
                function addDataBlock(type){                                        
                    var blockKeys = Object.keys(timing.blocks);                    
                    var blockKey = blockKeys[blockKeys.length - 1] ? parseInt(blockKeys[blockKeys.length - 1]) + 1 : 0;                      
                    var content = type == 'text' ? null : {'order' : [0], 'rows' : {0: {'num' : null, 'task' : null, 'opt' : null, 'pes' : null, 'note' : null}} };
                    
                    timing.blocks[blockKey] = {'type' : type, 'content' : content, 'title' : null};                    
                    timing.order.push(blockKey);
                    
                    insertBlock(blockKey);
                    
                    sendUpdate();
                }
                
                function appendDataRow(blockKey){                    
                    var rowKeys = Object.keys(timing.blocks[blockKey].content.rows);                    
                    var rowKey = rowKeys[rowKeys.length - 1] ? parseInt(rowKeys[rowKeys.length - 1]) + 1 : 0;  

                    timing.blocks[blockKey].content.order.push(rowKey);
                    timing.blocks[blockKey].content.rows[rowKey] = {'num' : null, 'task' : null, 'opt' : null, 'pes' : null, 'note' : null};
                    
                    sendUpdate();
                }
                
                function removeDataRow(blockKey, rowKey){                                                                        
                    delete timing.blocks[blockKey].content.rows[rowKey];
                    timing.blocks[blockKey].content.order.splice(timing.blocks[blockKey].content.order.indexOf(parseInt(rowKey)), 1);                    
                    
                    sendUpdate();
                }
                
                function removeDataBlock(blockKey){                    
                    delete timing.blocks[blockKey];
                    timing.order.splice(timing.order.indexOf(parseInt(blockKey)), 1);                                                   
                    
                    sendUpdate();
                }
                
                function updateDataBlockTitle(blockKey, blockTitle){
                    timing.blocks[blockKey].title = blockTitle;
                    
                    sendUpdate();
                }
                
                function updateDataTextContent(blockKey, blockContent){
                    timing.blocks[blockKey].content = blockContent;
                    
                    sendUpdate();
                }
                
                function updateDataTableRowContent(blockKey, rowKey, rowContent){
                    timing.blocks[blockKey].content.rows[rowKey] = rowContent;
                    
                    sendUpdate();
                }
                
                function updateTimig(){           
                    
                    $('.jq_timing-content').find('.jq_timing-block').each(function(){                                                         
                        if(timing.order.indexOf(parseInt($(this).attr('data-timing_block'))) < 0 )
                            $(this).remove();
                    });
                    
                    if($('#js-template-timing-block').length > 0){
                        $.each(timing.order, function(key, id){                        
                            if(!$('.jq_timing-content').find('#jq_timing_block-'+id).length){                                
                                insertBlock(id);
                            }else{
                                updateBlock(id);
                            }
                        });
                    }
                }
                    
                function sendUpdate(){
                    $.post('{{route('estimates.update.timing',['id'=>$estimate->id])}}', {timing: JSON.stringify(timing),_token: $("input[name=_token]").val()});
                }
                    
                function insertBlock(blockKey){
                    var block = timing.blocks[blockKey];
                    
                    var tmpBlock = $('#js-template-timing-block .box').first().clone();                    
                    
                    if(block.type == 'text'){
                        tmpBlock.find('.jq_timing-block-table').remove();
                    }else{
                        tmpBlock.find('.jq_timing-block-text').remove();
                        tmpBlock.find('.jq_timing-block-table-row').remove();
                    }         
                    
                    tmpBlock.attr('id', tmpBlock.attr('id') + blockKey).attr('data-timing_block', blockKey);                    
                    tmpBlock.appendTo('.jq_timing-content');
                    
                    $('#jq_timing_block-' + blockKey + ' .cke-textarea').each(function(){
                        $(this).attr('id', $(this).attr('id') + blockKey);
                        var keyCKEDITOR = $(this).attr('id'); 
                        CKEDITOR.inline(keyCKEDITOR).on('change', function() { 
                            var newTextData = this.getData();
                            clearTimeout(blockTextContentTimer);
                            
                            blockTextContentTimer = setTimeout(function() { updateDataTextContent(blockKey, newTextData);}, 200);                                                
                        });
                    });
                    
                    updateBlock(blockKey);
                }
         
                function updateBlock(blockKey){
                    var block = timing.blocks[blockKey];
                    
                    var htmlBlock = $('#jq_timing_block-'+blockKey);
                    
                    htmlBlock.find('.jq_timing-block-title').val(block.title);                    
                    
                    if(block.type == 'text'){
                        CKEDITOR.instances[htmlBlock.find('.jq_timing-block-text-content').attr('id')].setData(block.content);
                    }else{
                        htmlBlock.find('.jq_timing-block-table-row').each(function(){
                            if(block.content.order.indexOf(parseInt($(this).attr('data-timing_row'))) < 0 )
                                $(this).remove();
                        });                        
                        $.each(block.content.order, function(key, id){
                            if(!htmlBlock.find('#jq_timing-block-table-row-'+id).length){                                
                                var tmpRow = $('#js-template-timing-block .jq_timing-block-table-row').first().clone();
                                tmpRow.attr('id', tmpRow.attr('id') + id).attr('data-timing_row', id);
                                htmlBlock.find('tbody').append(tmpRow);
                            }
                            updateBlockRow(blockKey, id, block.content.rows[id]);
                        });
                        updateTableBlocktotal(blockKey);
                    }
                }    
                
                function updateTableBlocktotal(blockKey){
                    var htmlBlock = $('#jq_timing_block-' + blockKey);
                    var totalOpt = 0; totalPes = 0;
                    
                    $.each(timing.blocks[blockKey].content.rows, function (key, row){
                        totalOpt += row.opt ? parseFloat(row.opt) : 0;
                        totalPes += row.pes ? parseFloat(row.pes) : 0;
                        
                        if( parseFloat(row.opt) > parseFloat(row.pes) ){
                            htmlBlock.find('#jq_timing-block-table-row-'+key+' .jq_timing-block-table-pes').addClass('text-red field-error');
                        }else{
                           htmlBlock.find('#jq_timing-block-table-row-'+key+' .jq_timing-block-table-pes').removeClass('text-red field-error');
                        }
                        
                    });
                    
                    htmlBlock.find('.jq_timing-block-table-opt-sum').text(totalOpt);
                    htmlBlock.find('.jq_timing-block-table-pes-sum').text(totalPes);
                    
                    if(totalOpt > totalPes){
                        htmlBlock.find('.jq_timing-block-table-pes-sum').addClass('text-red');
                    }else{
                        htmlBlock.find('.jq_timing-block-table-pes-sum').removeClass('text-red');
                    }
                    
                    updateTotalsAll();
                }
                
                function updateTotalsAll(){
                    var hourInWeek = parseInt($('.jq_timing-totals-block').attr('data-hourInWeek'));
                    var totalOpt = 0; totalPes = 0;
                    
                    $('.jq_timing-block-table-opt-sum').each(function(){                        
                        totalOpt += $(this).text() ? parseFloat($(this).text()) : 0;
                    });                    
                    $('.jq_timing-block-table-pes-sum').each(function(){                        
                        totalPes += $(this).text() ? parseFloat($(this).text()) : 0;
                    });
                    
                    $('.jq_timing-totals-opt-sum').text(totalOpt);
                    $('.jq_timing-totals-pes-sum').text(totalPes);
                    
                    $('.jq_timing-totals-opt-sum-week').text(Math.ceil(totalOpt/hourInWeek));
                    $('.jq_timing-totals-pes-sum-week').text(Math.ceil(totalPes/hourInWeek));
                    
                }
                                
                function updateBlockRow(blockKey, id, row){
                    var htmlRow = $('#jq_timing_block-' + blockKey + ' #jq_timing-block-table-row-'+id);
                    
                    htmlRow.find('.jq_timing-block-table-num').val(row.num);
                    htmlRow.find('.jq_timing-block-table-task').val(row.task);
                    htmlRow.find('.jq_timing-block-table-opt').val(row.opt);
                    htmlRow.find('.jq_timing-block-table-pes').val(row.pes);
                    htmlRow.find('.jq_timing-block-table-note').val(row.note);                    
                }
                
            @endif
            
            @if(Auth::user()->hasAnyPermission(['edit estimates']))
                $("#modal-actions").on('hidden.bs.modal', function (e) {
                    var response = $("#modal-actions").attr('data-response');
                    var active = '{{ __('Active') }}', inactive = '{{ __('Inactive') }}';

                    if(!response) return false;
                    
                    response = JSON.parse(response);
                    
                    if(response.estimate){
                        AddJsNotifi('success', '{{ __('Success') }}!', response.success);
                        
                        $('.js-project-name, .content-header h1, head title').text(response.estimate.name);
                        $('.js-project-status').html(response.estimate.status ? '<span class="text-green">'+active+'</span>' : '<span class="text-red">'+inactive+'</span>');
                        $('.js-project-note').text(response.estimate.note);                    
                    }
                });
            @endif
            
        });        
    </script>
@endpush