<div class="box-header">
    <h3 class="box-title">{{ __('Daily report') }} <span class="box-title text-primary">{{ !empty($data['developer']) ? $data['developer']->getShortFullName() : '' }}</span></h3>                
    <div class="box-tools pull-right">        
        <span class="text-success margin-r-10" style="display: inline-block">
            <b>{{ __('Time')}}:</b> {{ !empty($data['developer']) ? $data['developer']->time : '' }}
        </span>
        <button type="button" class="btn btn-box-tool" data-widget="collapse">
            <i class="fa fa-minus"></i>
        </button>        
    </div>
</div>

<div class="box-body js-dev_reports">
    <table class="table table-striped bg-gray">        
        <thead class="thead-dark bg-light-blue">
            <tr>
                <th colspan="2">{{ __('Type') }}</th>
                <th>{{ __('Task') }}</th>                        
                <th>{{ __('Is Done') }}</th>
                <th>{{ __('Note') }}</th>
                <th style="width: 80px">{{ __('Time') }}</th>                
            </tr>
        </thead>    
        <tbody class="jq_dev_report-body">     
            @php $actions = false; @endphp
            @foreach($data['reports'] ?? [] as $report)
                @include('admin.dev_report.report_row', $report)                        
            @endforeach                    
        </tbody>
    </table>
</div>
