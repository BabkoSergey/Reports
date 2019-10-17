@extends('admin.layouts.app')

@section('htmlheader_title') {{$user->last_name ? $user->last_name .' '. $user->first_name : $user->name}} @endsection

@section('sub_title') {{ __('Profile') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12 jq_start_main">
        
        @include('admin.templates.action_notifi')
        
        <!-- Custom Tabs (Pulled to the right) -->
        <div class="nav-tabs-custom js-main-tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#main_info" data-toggle="tab">{{ __('Main info') }}</a></li>
                <li><a href="#info" data-toggle="tab">{{ __('Additional info') }}</a></li>                                
                <li><a href="#skills" data-toggle="tab">{{ __('Skills') }}</a></li>                                
                <li><a href="#password" data-toggle="tab">{{ __('Password') }}</a></li>                                                
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="main_info">
                    <!-- form start -->            
                    {!! Form::model($user, ['method' => 'PATCH','route' => ['profile.info.update'], 'class' => 'form-horizontal', 'id'=>'main_form']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5 class="box-title">
                                        <strong>{{ __('Position')}}: </strong>
                                        <span class="text-green js-positions-status-check {{ $user->pos_status ? '' : 'is-hidden' }}"><i class="fa fa-check"></i></span>
                                        <span class="text-red js-positions-status-ban {{ $user->pos_status ? 'is-hidden' : '' }}"><i class="fa fa-ban"></i></span>
                                        <span class="js-positions-current">{{ $user->getPositionName() }}</span>
                                    </h5>
                                    
                                    <div class="form-group">                                                                                
                                        <label for="logo" class="col-sm-12">
                                            {{__('Avatar')}}
                                            <span class="input-group-btn pull-right">
                                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-img-set" data-path_type="avatars">
                                                    <i class="fa fa-upload"></i>
                                                </button>
                                            </span>                                
                                        </label>

                                        <div class="col-sm-12 js-related_target text-center" id="js-related_target-logo">                                            
                                            <div class="js-img-logo">
                                                <img class="profile-user-img img-responsive ava-block-img" src="{{$user->avatar ?? asset('/img/default-user.png')}}">
                                            </div>
                                            {!! Form::hidden('logo', null, array('class' => 'js-img-set-val')) !!}                                                                                            
                                        </div>
                                    </div>
                                                                        
                                </div>
                                
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">{{ __('Login') }}*</label>

                                        <div class="col-sm-10">
                                            {!! Form::text('', $user->name, array('placeholder' => __('Login'),'class' => 'form-control', 'disabled')) !!}                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-sm-2 control-label">{{ __('E-mail') }}*</label>

                                        <div class="col-sm-10">
                                            {!! Form::email('email', null, array('placeholder' => __('E-mail'),'class' => 'form-control', 'required')) !!}                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">{{ __('Name') }}</label>

                                        <div class="col-sm-10">
                                            {!! Form::text('first_name', $user->first_name, array('placeholder' => __('Name'),'class' => 'form-control')) !!}                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">{{ __('Surname') }}</label>

                                        <div class="col-sm-10">
                                            {!! Form::text('last_name', $user->last_name, array('placeholder' => __('Surname'),'class' => 'form-control')) !!}                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="patron_name" class="col-sm-2 control-label">{{ __('Patronymic') }}</label>

                                        <div class="col-sm-10">
                                            {!! Form::text('patron_name', $user->patron_name, array('placeholder' => __('Patronymic'),'class' => 'form-control')) !!}                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone" class="col-sm-2 control-label">{{ __('Phone') }}</label>

                                        <div class="col-sm-10">
                                            {!! Form::text('phone', $user->phone, array('placeholder' => 'XXXXXXXXXXX','class' => 'form-control')) !!}                            
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="roles" class="col-sm-2 control-label">{{ __('Roles') }}*</label>

                                        <div class="col-sm-10">
                                            {!! Form::select('roles[]', $roles, $user->roles->pluck('name', 'name'), array('class' => 'form-control select2info', 'multiple', 'disabled')) !!}                            
                                        </div>
                                    </div> 
                                                                        
                                </div>
                                
                            </div>
                            
                        </div><!-- /.box-body -->            

                        <div class="box-footer">
                            <a class="btn btn-default" role="button" href="{{ url('') }}">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                        </div><!-- /.box-footer -->

                    {!! Form::close() !!}
                </div>
                
                <div class="tab-pane" id="info">
                    @include('admin.users.edit_form.edit_form_info')
                </div>                
                
                <div class="tab-pane" id="skills">
                    @include('admin.users.edit_form.edit_form_skills')
                </div>                
                
                <div class="tab-pane" id="password">
                    <!-- form start -->            
                    {!! Form::model($user, ['method' => 'PATCH','route' => ['profile.password.update'], 'class' => 'form-horizontal', 'id'=>'password_form']) !!}
                        <div class="box-body">

                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">{{ __('New password') }}*</label>

                                <div class="col-sm-10">
                                    {!! Form::password('password', array('placeholder' => '','class' => 'form-control', 'required')) !!}                                       
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirm-password" class="col-sm-2 control-label">{{ __('Password confirmation') }}*</label>

                                <div class="col-sm-10">
                                    {!! Form::password('confirm-password', array('placeholder' => '','class' => 'form-control', 'required')) !!}                                       
                                </div>
                            </div>

                        </div><!-- /.box-body -->            

                        <div class="box-footer">
                            <a class="btn btn-default" role="button" href="{{ url('') }}">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                        </div><!-- /.box-footer -->

                    {!! Form::close() !!}
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
                          
    </div>
</div>

@include('admin.templates.img_upload')

@endsection

@push('styles')

@endpush

@push('scripts') 
        
<script>
    $(function () {  
        $( document ).ready(function() {    
            $('.select2info').select2();
            
            $(document).on('submit','#main_form, #password_form',function (e){
                e.preventDefault(); 
                
                $('.alert').remove();
                
                $.post($(this).attr('action'), $(this).serialize())
                    .done(function(data) { 
                        $('.jq_start_main').prepend('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> {{ __('Success') }}!</h4> {{ __('Updated successfully') }}!</div>');
                    })
                    .fail(function(error) { 
                        $.each( error.responseJSON.errors, function( type, obj ) {                            
                            $.each( obj, function( key, error ) {
                                $('.jq_start_main').prepend('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> {{ __('Error') }}!</h4>'+error+'</div>');
                            }); 
                        }); 
                    });
            });
             
            var hashes = ['main_info', 'info', 'skills', 'password'];

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
    });    
</script>
@endpush