@php $userSkillsA = $user->getSkills->groupBy('cat'); @endphp 

@foreach($settings['skills_cat'] as $skillsCatKey => $skillsCat)
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __($skillsCat) }}</h3>
        </div>    
        <div class="box-body">
            <table class="table table-striped bg-gray"> 
                <thead class="thead-dark bg-light-blue">
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Level') }}</th>                        
                        <th class="text-center">{{ __('Experience') }}</th>
                        <th class="text-center">{{ __('Last used') }}</th>
                    </tr>
                </thead>                   
                <tbody> 
                    @forelse($userSkillsA[$skillsCatKey] ?? [] as $skill)
                        <tr>                        
                            <td>{{ $skill->name ?? '' }}</td>
                            <td>{{ __(Config::get('skillLevels.levels')[$skill->level ?? null]) }}</td>
                            <td class="text-center">{{ $skill->exp ?? '' }}</td>
                            <td class="text-center">{{ $skill->used ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                    @endforelse
                </tbody>            
            </table>
        </div>
    </div>
@endforeach

@push('styles')

@endpush

@push('scripts')   
    
@endpush