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
<tr class="">                    
    <th colspan="5"></th>
</tr>
<tr class="">                    
    <th colspan="2">{{ __('Total') }} ({{ __('hours') }}):</th>
    <th class="jq_timing-totals-opt-sum text-center">{{ $opt }}</th>
    <th class="jq_timing-totals-pes-sum text-center">{{ $pes }}</th>
    <th></th>
</tr>          
<tr class="">                    
    <th colspan="2">{{ __('Timeline') }} ({{ __('weeks') }}):</th>
    <th class="jq_timing-totals-opt-sum-week text-center">{{ ceil($opt/$hourInWeek) }}</th>
    <th class="jq_timing-totals-pes-sum-week text-center">{{ ceil($pes/$hourInWeek) }}</th>
    <th></th>
</tr>          
