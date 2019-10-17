{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id], 'class' => 'form-horizontal', 'id'=>'main_form']) !!}
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
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

                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">{{ __('Condition') }}*</label>

                    <div class="col-sm-8">
                        {!! Form::select('status', array(0=>__('Blocked'), 1=>__('Active')), $user->status, array('class' => 'form-control','single', 'required')) !!}                            
                    </div>
                </div>

            </div>

            <div class="col-sm-8">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">{{ __('Login') }}*</label>

                    <div class="col-sm-10">
                        {!! Form::text('name', $user->name, array('placeholder' => __('Login'),'class' => 'form-control', 'required')) !!}                            
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
                        {!! Form::select('roles[]', $roles, $user->roles->pluck('name', 'name'), array('class' => 'form-control roles-select2', 'multiple')) !!}                            
                    </div>
                </div> 

                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">{{ __('Note') }}</label>
                    <div class="col-sm-10">
                        {!! Form::textarea('description', $user->description, array('placeholder' => __('Note'), 'class' => 'form-control', 'rows'=> '3', 'id' => 'cke-description' )) !!}                            
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

@include('admin.templates.img_upload')

@push('styles')

@endpush

@push('scripts')     
    <script>
        $(function () {  
            CKEDITOR.replace( 'cke-description' );        
            
            $('.roles-select2').select2();            
            
            $(document).on('submit','#main_form',function (e){
                e.preventDefault(); 
                
                $('.alert').remove();
                
                $.post($(this).attr('action'), $(this).serialize())
                    .done(function(data) { 
                        AddJsNotifi('success', '{{ __('Success') }}!', '{{ __('Updated successfully') }}!');                        
                    })
                    .fail(function(error) { 
                        if(typeof error.responseJSON.error == 'object' && Object.values(error.responseJSON.error).length >= 1){
                            $.each(error.responseJSON.error, function(key, val){
                                AddJsNotifi('danger', '{{ __('Error') }}!', val); 
                            });
                        }else{
                            AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error saving form') }}'); 
                        }
                    });
            });
            
        });
        
    </script>
@endpush