@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Roles') }} @endsection

@section('sub_title') {{ __('Add new') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @include('admin.templates.action_notifi')

        <!-- Horizontal Form -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Add new role') }}</h3>
            </div><!-- /.box-header -->
            <!-- form start -->            
                        
            {!! Form::open(array('method'=>'POST', 'route' => 'roles.store', 'class' => 'form-horizontal')) !!}
                <div class="box-body">
                    
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{ __('Role') }}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::text('name', null, array('placeholder' => __('Role'),'class' => 'form-control')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="name_" class="col-sm-2 control-label">{{ __('Permissions') }}</label>
                        
                        <div class="col-sm-10">
                            <div class="row padding-t-10">
                                @foreach($permissions as $value)
                                    <div class="col-md-3 col-sm-6">
                                        {{ Form::checkbox('permissions[]', $value->id, false, array('class' => 'name')) }}
                                        {{ $value->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('roles.index') }}">{{ __('Cancel') }}</a>
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