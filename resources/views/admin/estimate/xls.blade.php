@php $hourInWeek = 35; $opt = 0; $pes = 0; @endphp
<table border="1">
    <thead>                
        <tr>            
            <th colspan="5"  style="color: #336699; font-size: 14pt; height: 22pt; text-align: center; font-weight: bold;">{{ $estimate->name }}</th>
        </tr>        
        <tr>            
            <th colspan="5"></th>
        </tr>        
    </thead>
    <tbody>
        <tr>
            <th style="color: #336699; width: 8pt; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ __('#') }}</th>
            <th style="color: #336699; width: 50pt; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ __('Task') }}</th>                        
            <th style="color: #336699; width: 12pt; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ __('Optimistic') }}</th>
            <th style="color: #336699; width: 12pt; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ __('Pessimistic') }}</th>
            <th style="color: #336699; width: 20pt; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ __('Comments') }}</th>                
        </tr>
        @foreach(json_decode($estimate->timing)->blocks as $timingShowBlock)                        
            <tr>
                <th colspan="5" style="height: 15pt; color: #337ab7; padding: 5pt 10pt; font-weight: bold; border: 2pt solid #0056b3; background: #eeeeee;">{{$timingShowBlock->title}}</th>    
            </tr>
            @if($timingShowBlock->type == 'table')                                
                @foreach($timingShowBlock->content->rows as $timingShowBlockRow)                    
                    @php
                        $opt += floatval($timingShowBlockRow->opt);
                        $pes += floatval($timingShowBlockRow->pes);
                    @endphp
                    <tr>
                        <td style="text-align: center; border: 2pt solid #0056b3;">{{ $timingShowBlockRow->num }}</td>
                        <td style="border: 2pt solid #0056b3;">{{ $timingShowBlockRow->task }}</td>
                        <td style="border: 2pt solid #0056b3; text-align: center;">{{ $timingShowBlockRow->opt }}</td>
                        <td style="border: 2pt solid #0056b3; text-align: center;">{{ $timingShowBlockRow->pes }}</td>
                        <td style="border: 2pt solid #0056b3;">{!! $timingShowBlockRow->note !!}</td>
                    </tr>
                @endforeach
                
            @else
                <tr>
                    <td colspan="5" style="word-wrap: break-word;">{!! $timingShowBlock->content !!}</td>
                </tr>
            @endif           
        @endforeach
    </tbody>
    <tfoot>
        <tr>                    
            <th colspan="2" style="background: #BEE9EA; color: #336699;text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ __('Total') }} ({{ __('hours') }}):</th>
            <th style="background: #BEE9EA; color: #336699;text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ $opt }}</th>
            <th style="background: #BEE9EA; color: #336699;text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ $pes }}</th>
            <th style="background: #BEE9EA; color: #336699;text-align: center; font-weight: bold; border: 2pt solid #0056b3;"></th>
        </tr>          
        <tr>                    
            <th colspan="2" style="background: #BEE9EA; color: #336699; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ __('Timeline') }} ({{ __('weeks') }}):</th>
            <th style="background: #BEE9EA; color: #336699; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ ceil($opt/$hourInWeek) }}</th>
            <th style="background: #BEE9EA; color: #336699; text-align: center; font-weight: bold; border: 2pt solid #0056b3;">{{ ceil($pes/$hourInWeek) }}</th>
            <th style="background: #BEE9EA; color: #336699; text-align: center; font-weight: bold; border: 2pt solid #0056b3;"></th>
        </tr> 
    </tfoot>
</table>