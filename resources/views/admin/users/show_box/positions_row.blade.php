<tr class="js_positions-row js_positions-row-{{ $position->id ?? '' }}">                        
    <td class="js_positions-row-name">{{ isset($position) ? $position->getPositionName() : '' }}</td>                
    <td class="text-center js_positions-row-from">{{ isset($position) && $position->from ? date('Y-m-d', strtotime($position->from)) : '' }}</td>
    <td class="text-center js_positions-row-to">{{ isset($position) && $position->to ? date('Y-m-d', strtotime($position->to)) : '' }}</td>
    @if(Auth::user()->hasPermissionTo('office positions edit') && isset($editable) && $editable)                    
        <td class="text-center js_positions-row-actions">                        
            @if(!isset($position) || (isset($position) && strtotime($position->to) == strtotime(date('Y-m-d', time())) && !$user->pos_status) )
                <button class="btn btn-success btn-sm pull-right margin-r-5 jq_position-lay_on-btn" value="{{ $position->id ?? '' }}">
                    <i class="fa fa-refresh"></i>
                </button>
            @endif 
            @if(!isset($position) || (isset($position) && strtotime($position->from) >= strtotime(date('Y-m-d', time()))) )                                                
                <button class="btn btn-danger btn-sm pull-right margin-r-5 jq_positions-delete-btn" value="{{ $position->id ?? '' }}">
                    <i class="fa fa-trash"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm pull-right margin-r-5 jq_positions-edit-btn"  data-toggle="modal" data-target="#modal-position" data-position="{{ $position->id ?? '' }}">                
                    <i class="fa fa-pencil"></i>
                </button>
            @endif                                   
        </td>
    @endif            
</tr>
