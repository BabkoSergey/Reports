{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id], 'class' => 'form-horizontal', 'id'=>'password_form']) !!}
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

    </div>

    <div class="box-footer">
        <a class="btn btn-default" role="button" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
    </div>

{!! Form::close() !!}

@push('styles')

@endpush

@push('scripts')     
    <script>
        $(function () {  
            
            $(document).on('submit','#password_form',function (e){
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