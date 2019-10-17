<tr style="border-top: 1em solid #fff;">
    <th colspan="5" class="bg-gray-active">{{$timingShowBlock->title}}</th>    
</tr>

@foreach($timingShowBlock->content->rows as $timingShowBlockRow)                    
    <tr>
        <td>{{ $timingShowBlockRow->num }}</td>
        <td>{{ $timingShowBlockRow->task }}</td>
        <td class="text-center">{{ $timingShowBlockRow->opt }}</td>
        <td class="text-center">{{ $timingShowBlockRow->pes }}</td>
        <td class="text-note">{!! $timingShowBlockRow->note !!}</td>
    </tr>
@endforeach                
