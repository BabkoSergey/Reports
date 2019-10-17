<table class="table table-striped bg-gray"> 
    <thead class="thead-dark bg-light-blue">
        <tr>
            <th>{{ __('Positions') }}</th>
            <th class="text-center">{{ __('From') }}</th>                        
            <th class="text-center">{{ __('To') }}</th>            
            @if(Auth::user()->hasPermissionTo('office positions edit') && isset($editable) && $editable)                    
                <th style="width: 100px">{{ __('Actions') }}</th>
            @endif            
        </tr>
    </thead>                   
    <tbody class="js-positions-box-body"> 
        @forelse($user->getPositions->sortByDesc('from')->keyBy('id') as $positionKey => $position)
            @include('admin.users.show_box.positions_row', ['position' => $position, 'editable' => $editable ?? null])                    
        @empty

        @endforelse
    </tbody>            
</table>

<table id="js-template-row-positions" style="display: none;">
    <tbody>
        @include('admin.users.show_box.positions_row', ['position' => null, 'editable' => $editable ?? null])                    
    </tbody>
</table>

@if(Auth::user()->hasPermissionTo('office positions edit') && isset($editable) && $editable)        
    <div style="display: none">
        <form id="jq_positions-delete-form" method="POST" action="" data-url="{{ url('/admin/positions/') }}" accept-charset="UTF-8">
            @csrf
            <input name="_method" type="hidden" value="DELETE">    
            <input class="btn btn-danger" type="submit" value="Delete">
        </form>
    </div>
@endif

@push('styles')

@endpush

@push('scripts')   
     
@endpush