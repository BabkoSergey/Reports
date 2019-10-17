<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Birthday') }}</h3>
    </div>    
    <div class="box-body">        
        {{$user->getInfo->birthday ?? ''}}
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Address') }}</h3>
    </div>    
    <div class="box-body">
        {{$user->getInfo->address ?? ''}}
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Gender') }}</h3>
    </div>    
    <div class="box-body">
        {{ __(ucfirst($user->getInfo->gender ?? '')) }}
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Marital') }}</h3>
    </div>    
    <div class="box-body">
        {{ __(ucfirst($user->getInfo->marital ?? '')) }}
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Сhildren') }}</h3>
    </div>    
    <div class="box-body">
        @foreach($user->getInfo && $user->getInfo->children ? json_decode($user->getInfo->children) : [] as $children)                        
            <div class="row margin-b-10">
                <div class="col-md-12"><b>{{ $children->name ?? '' }}</b></div>
                <div class="col-md-6"><b>{{ __('Gender') }}:</b> {{ __(ucfirst($children->gender ?? '')) }}</div>
                <div class="col-md-6"><b>{{ __('Birthday') }}:</b> {{ $children->birthday ?? '' }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Languages') }}</h3>
    </div>    
    <div class="box-body">
        @foreach($user->getInfo && $user->getInfo->languages ? json_decode($user->getInfo->languages) : [] as $languages)                        
            <div class="row margin-b-10">            
                <div class="col-md-6"><b>{{ __('Language') }}:</b> {{ $languages->ln ? Languages::lookup([$languages->ln], App::getLocale())[$languages->ln] : '' }}</div>
                <div class="col-md-6"><b>{{ __('Level') }}:</b> {{ $languages->level ?? '' }} {{ __(Config::get('lengLevels.levels')[$languages->level ?? null]) }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Education') }}</h3>
    </div>    
    <div class="box-body">
        @foreach($user->getInfo && $user->getInfo->education ? json_decode($user->getInfo->education) : [] as $education)                        
            <div class="row margin-b-10">   
                <div class="col-md-12"><b>{{ __('Educational institution') }}:</b> {{ $education->inst ?? '' }}</div>
                <div class="col-md-12"><b>{{ __('Name of specialty') }}:</b> {{ $education->spec ?? '' }}</div>
                <div class="col-md-8"><b>{{ __('Qualification') }}:</b> {{ $education->qual ?? '' }}</div>
                <div class="col-md-4"><b>{{ __('Year of ending') }}:</b> {{ $education->y ?? '' }}  {{ $education->m ?? '' }}  {{ $education->d ?? '' }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Courses') }}</h3>
    </div>    
    <div class="box-body">
        @foreach($user->getInfo && $user->getInfo->courses ? json_decode($user->getInfo->courses) : [] as $courses)                        
            <div class="row margin-b-10">   
                <div class="col-md-12"><b>{{ __('Educational institution') }}:</b> {{ $courses->inst ?? '' }}</div>
                <div class="col-md-8"><b>{{ __('Course name') }}:</b> {{ $courses->name ?? '' }}</div>
                <div class="col-md-4"><b>{{ __('Year of ending') }}:</b> {{ $courses->y ?? '' }}  {{ $courses->m ?? '' }}  {{ $courses->d ?? '' }}</div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')

@endpush

@push('scripts')   
    
@endpush