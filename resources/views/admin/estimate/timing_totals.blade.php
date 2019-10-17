@php 
    $hourInWeek = 35; $opt = 0; $pes = 0;    
    if($estimate->timing){
        $timingAll = json_decode($estimate->timing);
        foreach($timingAll->blocks as $timingAllBlock){        
            if($timingAllBlock->type == 'table'){
                foreach($timingAllBlock->content->rows as $timingAllBlockRow){
                    $opt += floatval($timingAllBlockRow->opt);
                    $pes += floatval($timingAllBlockRow->pes);
                }
            }
        }
    }
@endphp

<div class="row">
    <div class="col-md-12 margin-t-5">
        <table class="table table-striped bg-gray jq_timing-totals-block" data-hourInWeek="{{ $hourInWeek }}" style="font-size: 0.6em">
            <thead class="thead-dark bg-gray-active">
                <tr>
                    <th></th>                    
                    <th class="text-center">{{ __('Optimistic') }}</th>
                    <th class="text-center">{{ __('Pessimistic') }}</th>
                </tr>
            </thead>    
            <tbody>         
                <tr class="">                    
                    <td>{{ __('Total') }} ({{ __('hours') }}):</td>
                    <td class="jq_timing-totals-opt-sum text-center">{{ $opt }}</td>
                    <td class="jq_timing-totals-pes-sum text-center">{{ $pes }}</td>
                </tr>          
                <tr class="">                    
                    <td>{{ __('Timeline') }} ({{ __('weeks') }}):</td>
                    <td class="jq_timing-totals-opt-sum-week text-center">{{ ceil($opt/$hourInWeek) }}</td>
                    <td class="jq_timing-totals-pes-sum-week text-center">{{ ceil($pes/$hourInWeek) }}</td>
                </tr>          
            </tbody>
        </table>
    </div>
</div>
