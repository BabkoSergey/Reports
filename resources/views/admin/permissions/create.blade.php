@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Permissions') }} @endsection

@section('sub_title') {{ __('Add new') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @include('admin.templates.action_notifi')        

        <!-- Horizontal Form -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Add new permission') }}</h3>
            </div><!-- /.box-header -->
            <!-- form start -->            
                        
            {!! Form::open(array('method'=>'POST', 'route' => 'permissions.store', 'class' => 'form-horizontal')) !!}
                <div class="box-body">
                    
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{ __('Permission name') }}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::text('name', null, array('placeholder' => __('Permission name'),'class' => 'form-control')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="guard_name" class="col-sm-2 control-label">{{ __('Provider type') }}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('guard_name', array('web'=>'web', 'api'=>'api'), 'web', array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                    
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('permissions.index') }}">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                </div><!-- /.box-footer -->
                                
            {!! Form::close() !!}
            
        </div><!-- /.box -->
       
    </div>
</div>


@endsection

@push('styles')

@endpush

@push('scripts')    
 
@endpush