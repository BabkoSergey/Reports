<table class="table table-striped bg-gray jq_timing-block-table">
    <thead class="thead-dark bg-light-blue">
        <tr>
            <th>{{ __('#') }}</th>
            <th>{{ __('Task') }}</th>                        
            <th>{{ __('Optimistic') }}</th>
            <th>{{ __('Pessimistic') }}</th>
            <th>{{ __('Comments') }}</th>                
            <th></th>                
        </tr>
    </thead>    
    <tbody>         
        <tr class="jq_timing-block-table-row" id="jq_timing-block-table-row-" data-timing_row="">
            <td style="width: 50px; padding-left: 10px!important;"><input name="num" type="text" val="" class="jq_timing-block-table-num"></td>
            <td style="width: 40%;"><input name="task" type="text" val="" class="jq_timing-block-table-task"></td>
            <td style="width: 30px;"><input name="opt" type="number" min="0" val="" class="jq_timing-block-table-opt"></td>
            <td style="width: 30px;"><input name="pes" type="number" min="0" val="" class="jq_timing-block-table-pes"></td>
            <td><textarea name="note" val="" class="jq_timing-block-table-note" rows="1"></textarea></td>
            <td style="padding-right:10px!important; width: 30px;"><button type="button" class="btn btn-danger btn-sm pull-right jq_timing_block-delete-row-btn margin-t-3"><i class="fa fa-trash"></i></button></td>
        </tr>          
    </tbody>
    <tfoot class="thead-dark bg-gray-active">
        <tr>
            <th colspan="2">{{ __('Total') }}:</th>            
            <th class="jq_timing-block-table-opt-sum text-center"></th>
            <th class="jq_timing-block-table-pes-sum text-center"></th>
            <th colspan="2"><button type="button" class="btn btn-info btn-sm pull-right jq_timing_block-add-row-btn"><i class="fa fa-plus"></i></button></th>                
        </tr>
    </tfoot>     
</table>