<div class="box-body">
    <div class="row">              
        <button type="button" class="btn btn-success margin-b-10 pull-right jq_skills-cat-btn-add">+ {{ __('Add new') }}</i></button>
    </div>        
    
    <div class="row jq_experience-box">     
        @forelse($user->getExperiences->sortByDesc('from')->keyBy('id') as $experienceKey => $experience)
            @include('admin.users.show_box.experiences_row', ['experience' => $experience, 'editable' => true])
        @empty

        @endforelse        
    </div>
</div>

<div class="box-footer">
    <a class="btn btn-default" role="button" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@push('scripts')   
    <script src="{{ asset('/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    
    <script>
        $(function () {  
            $( document ).ready(function() { 
                
            });
        });
        
    </script>
@endpush