<tr class="jq_report-row" id="report-{{ $report->id ?? '' }}" data-report="{{ $report->id ?? '' }}">
    <td class="jq_report-type" style="width: 100px">{{ __( ucfirst($report->getTask->type ??  '')) }}</td>
    <td class="jq_report-resource">{{ isset($report) ? $report->getTask->getResourceName()->name ?? $report->getResource()->name ?? '' : '' }}</td>
    <td class="jq_report-task">{{ $report->getTask->name ??  '' }}</td>    
    <td class="jq_report-is_done">{!! $report->is_done ??  '' !!}</td>
    <td class="jq_report-note">{!! $report->note ??  '' !!}</td>
    <td class="jq_report-time">{{ substr($report->time ??  '', 0, -3) }}</td>
    @if(!isset($actions) || (isset($actions) && $actions) )
        <td class="jq_report-actions">
            @if(Auth::user()->hasPermissionTo('delete dev_report'))
                    <button type="button" class="btn btn-danger btn-sm jq_report-delete" data-jq_report="{{ $report->id ?? '' }}">
                        <i class="fa fa-trash"></i>
                    </button>                    
                @endif

                @if(Auth::user()->hasPermissionTo('edit dev_report'))
                    <button type="button" class="btn btn-primary btn-sm jq_report-edit-btn" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Edit report') }}" data-url="{{ url('admin/dev_reports') }}/{{ $report->id ?? '' }}/edit">
                        <i class="fa fa-pencil"></i>
                    </button>
                @endif
        </td>
    @endif
</tr>