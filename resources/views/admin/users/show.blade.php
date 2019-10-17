@extends('admin.layouts.app')

@section('htmlheader_title') {{$user->last_name ? $user->last_name .' '. $user->first_name : $user->name}} @endsection

@section('sub_title') {{ __('Info') }} @endsection

@section('content')

<div class="row">

    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{$user->logo ? $user->logo : asset('/img/default-user.png')}}" alt="">

                <h3 class="profile-username text-center">{{ $user->first_name||$user->last_name ? $user->first_name.' '.$user->last_name : $user->name }}</h3>

                <p class="text-muted text-center">{{ implode(', ', $user->setroles) }}</p>

                <ul class="list-group list-group-unbordered">                    
                    <li class="list-group-item">
                        <b>{{ __('Position') }}</b> 
                        <a class="pull-right">
                            <span class="text-{{ $user->pos_status ? 'green' : 'red' }}"><i class="fa fa-{{ $user->pos_status ? 'check' : 'ban' }}"></i></span>                            
                            <span>{{ $user->getPositionName() }}</span>
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>{{ __('Login') }}</b> <a class="pull-right">{{ $user->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{ __('Name') }}</b> <a class="pull-right">{{ $user->first_name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{ __('Surname') }}</b> <a class="pull-right">{{ $user->last_name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{ __('Patronymic') }}</b> <a class="pull-right">{{ $user->patron_name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{ __('E-mail') }}</b> <a class="pull-right">{{ $user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{ __('Phone') }}</b> <a class="pull-right">{{ $user->phone }}</a>
                    </li>                    
                    <br>
                    <p>{!! $user->description !!}</p>                    
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="nav-tabs-custom js-main-tabs">
            <ul class="nav nav-tabs">                             
                <li class=""><a href="#info" data-toggle="tab">{{ __('Additional info') }}</a></li>     
                <li class=""><a href="#skills" data-toggle="tab">{{ __('Skills') }}</a></li>     
                @if(Auth::user()->hasPermissionTo('office positions list'))
                    <li class=""><a href="#positions" data-toggle="tab">{{ __('Positions') }}</a></li>     
                @endif   
                @if(Auth::user()->hasPermissionTo('show permission'))
                    <li class=""><a href="#permissions" data-toggle="tab">{{ __('Permissions') }}</a></li>     
                @endif   
                
                <a class="btn btn-default pull-right" role="button" href="{{ route('users.index') }}">{{ __('Back to list') }}</a>
            </ul>
            
            <div class="tab-content" style="min-height: 375px;">
                <div class="tab-pane" id="info">
                    @include('admin.users.show_box.info')
                </div>
                
                <div class="tab-pane" id="skills">
                    @include('admin.users.show_box.skills')
                </div>                
                
                @if(Auth::user()->hasPermissionTo('office positions list'))
                    <div class="tab-pane" id="positions">
                        @include('admin.users.show_box.positions', ['editable' => false])                    
                    </div>                
                @endif
                
                @if(Auth::user()->hasPermissionTo('show permission'))
                    <div class="tab-pane" id="permissions">
                        <p>
                            @foreach($user->getAllPermissions() as $permission)
                                <span class="label label-primary">{{ $permission->name }}</span>
                            @endforeach
                        </p>
                    </div>
                @endif                                                 
            </div>
        </div>
    </div>
    
</div>

@endsection

@push('styles')    

@endpush

@push('scripts')    
    <script>
        $(function () {  
            
            var hashes = ['info', 'skills', 'positions', 'permissions'];

            if(typeof window.location.hash != "undefined"){
                var hash = window.location.hash.replace(/#/gi, ''); 
                if(hashes.indexOf(hash) != -1){
                    $('.js-main-tabs li, .tab-pane').removeClass('active');
                    $('#'+hash).addClass('active');
                    $('.js-main-tabs li').each(function(){                    
                        if($(this).find('a').attr('href') === '#'+hash) $(this).addClass('active');
                    });
                }else{
                    $('.js-main-tabs ul.nav-tabs li').first().addClass('active');
                    $('#'+$('.js-main-tabs ul.nav-tabs li').first().find('a').attr('href').replace(/#/gi, '')).addClass('active');
                }
            }else{
                $('.js-main-tabs ul.nav-tabs li').first().addClass('active');
                $('#'+$('.js-main-tabs ul.nav-tabs li').first().find('a').attr('href').replace(/#/gi, '')).addClass('active');
            }
            
            $(document).on('click','.js-main-tabs li', function(e){
                window.location.hash = $(this).find('a').attr('href');
            });
        
        });
        
    </script>
@endpush