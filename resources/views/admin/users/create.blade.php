@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Users') }} @endsection

@section('sub_title') {{ __('Add user') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
       
        @include('admin.templates.action_notifi')

        <!-- Horizontal Form -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Add new user') }}</h3>
                <a class="btn btn-default pull-right" role="button" href="{{ route('users.index') }}">{{ __('Back to list') }}</a>
            </div><!-- /.box-header -->
            <!-- form start -->            
                        
            {!! Form::open(array('method'=>'POST', 'route' => 'users.store', 'class' => 'form-horizontal')) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="logo" class="col-sm-12">
                                    {{__('Avatar')}}
                                    <span class="input-group-btn pull-right">
                                        <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-img-set" data-path_type="avatars"><i class="fa fa-upload"></i></button>
                                    </span>                                
                                </label>

                                <div class="col-sm-12 js-related_target text-center" id="js-related_target-logo">                                            
                                    <div class="js-img-logo">
                                        <img class="profile-user-img img-responsive ava-block-img" src="{{$user->logo ?? asset('/img/default-user.png')}}">
                                    </div>
                                    {!! Form::hidden('logo', null, array('class' => 'js-img-set-val')) !!}                                                                                            
                                </div>
                            </div>                            
                            
                            <div class="form-group">
                                <label for="status" class="col-sm-2 control-label">{{ __('Condition') }}*</label>

                                <div class="col-sm-8">
                                    {!! Form::select('status', array(0=>__('Blocked'), 1=>__('Active')), 1, array('class' => 'form-control','single', 'required')) !!}                            
                                </div>
                            </div>
                            
                        </div>
                                
                        <div class="col-sm-8">
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">{{ __('Login') }}*</label>

                                <div class="col-sm-10">
                                    {!! Form::text('name', null, array('placeholder' => __('Login'),'class' => 'form-control', 'required')) !!}                            
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">{{ __('E-mail') }}*</label>

                                <div class="col-sm-10">
                                    {!! Form::email('email', null, array('placeholder' => __('E-mail'),'class' => 'form-control', 'required')) !!}                            
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">{{ __('Password') }}*</label>

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

                            <div class="form-group">
                                <label for="first_name" class="col-sm-2 control-label">{{ __('Name') }}</label>

                                <div class="col-sm-10">
                                    {!! Form::text('first_name', null, array('placeholder' => __('Name'),'class' => 'form-control')) !!}                            
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="col-sm-2 control-label">{{ __('Surname') }}</label>

                                <div class="col-sm-10">
                                    {!! Form::text('last_name', null, array('placeholder' => __('Surname'),'class' => 'form-control')) !!}                            
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="patron_name" class="col-sm-2 control-label">{{ __('Patronymic') }}</label>

                                <div class="col-sm-10">
                                    {!! Form::text('patron_name', null, array('placeholder' => __('Patronymic'),'class' => 'form-control')) !!}                            
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="col-sm-2 control-label">{{ __('Phone') }}</label>

                                <div class="col-sm-10">
                                    {!! Form::text('phone', null, array('placeholder' => 'XXXXXXXXXXX','class' => 'form-control')) !!}                            
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="roles" class="col-sm-2 control-label">{{ __('Roles') }}</label>

                                <div class="col-sm-10">
                                    {!! Form::select('roles[]', $roles,[], array('class' => 'form-control select2', 'multiple')) !!}                            
                                </div>
                            </div>                    

                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">{{ __('Note') }}</label>

                                <div class="col-sm-10">
                                    {!! Form::textarea('description', null, array('placeholder' => __('Note'), 'class' => 'form-control', 'rows'=> '3', 'id' => 'cke-description' )) !!}                            
                                </div>
                            </div> 
                            
                        </div>
                    
                    </div>
                                        
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                </div><!-- /.box-footer -->
                                
            {!! Form::close() !!}
            
        </div><!-- /.box -->
       
    </div>
</div>

@include('admin.templates.img_upload')

@endsection

@push('styles')
    
@endpush

@push('scripts')    
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
//        CKEDITOR.replace('cke-description');
    </script>
    
    <script>
      $(function () {  
          $('.select2').select2();
          
      });
    </script>
            
@endpush