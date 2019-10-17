@extends('admin.layouts.app')

@section('htmlheader_title') {{$user->last_name ? $user->last_name .' '. $user->first_name : $user->name}} @endsection

@section('sub_title') {{ __('Editing') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12 jq_start_main">
        
        @include('admin.templates.action_notifi')
        
        <div class="nav-tabs-custom js-main-tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#main_info" data-toggle="tab">{{ __('Main info') }}</a></li>
                <li><a href="#info" data-toggle="tab">{{ __('Additional info') }}</a></li>
                <li><a href="#skills" data-toggle="tab">{{ __('Skills') }}</a></li>
                <li><a href="#positions" data-toggle="tab">{{ __('Positions') }}</a></li>
                <li><a href="#experience" data-toggle="tab">{{ __('Experience') }}</a></li>
                <li><a href="#password" data-toggle="tab">{{ __('Password') }}</a></li>
                
                <a class="btn btn-default pull-right" role="button" href="{{ route('users.index') }}">{{ __('Back to list') }}</a>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="main_info">
                    @include('admin.users.edit_form.edit_form_main')
                </div>
                
                <div class="tab-pane" id="info">
                    @include('admin.users.edit_form.edit_form_info')
                </div>                
                
                <div class="tab-pane" id="skills">
                    @include('admin.users.edit_form.edit_form_skills')
                </div>                
                
                <div class="tab-pane" id="positions">
                    @include('admin.users.edit_form.edit_form_positions-box')                    
                </div>                
                
                <div class="tab-pane" id="experience">
                    @include('admin.users.edit_form.edit_form_experience')                    
                </div>                
                                                
                <div class="tab-pane" id="password">
                    @include('admin.users.edit_form.edit_form_password')
                </div>                
            </div>            
        </div>
    </div>
</div>

@endsection

@push('styles')

@endpush

@push('scripts') 
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
        
    <script>
        $(function () {               
            var hashes = ['main_info', 'password', 'info', 'skills', 'positions', 'experience'];

            if(typeof window.location.hash != "undefined"){
                var hash = window.location.hash.replace(/#/gi, ''); 
                if(hashes.indexOf(hash) != -1){
                    $('.js-main-tabs li, .tab-pane').removeClass('active');
                    $('#'+hash).addClass('active');
                    $('.js-main-tabs li').each(function(){                    
                        if($(this).find('a').attr('href') === '#'+hash) $(this).addClass('active');
                    });
                }            
            }

            $(document).on('click','.js-main-tabs li a', function(e){                
                e.preventDefault();
//                window.location.hash = $(this).attr('href');                
            });
        
        });
        
    </script>
@endpush