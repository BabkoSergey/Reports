@extends('admin.layouts.app')

@section('htmlheader_title') {{__('Main Settings')}} @endsection

@section('content')

    <div class="row">
        <div class="col-md-12 jq_start_main">
            
            @include('admin.templates.action_notifi')        

            <div class="nav-tabs-custom js-main-tabs">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#general" data-toggle="tab">{{__('General')}}</a></li>                                    
                    <li><a href="#mail" data-toggle="tab">{{__('Mail')}}</a></li>                                
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane active" id="general">                    
                        {!! Form::model($settings['app'], ['method' => 'PATCH','route' => ['settings.update', 'app'], 'class' => 'form-horizontal', 'id'=>'general_form']) !!}
                            <div class="box-body">                                
                                <div class="form-group">
                                    <label for="app_name" class="col-sm-2 control-label">{{__('Site name')}}</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('app_name', $settings['app']['app_name'] ?? null, array('placeholder' => __('Site name'),'class' => 'form-control')) !!}                                                                                    
                                    </div>
                                </div>    

                                <div class="form-group">
                                    <label for="app_url" class="col-sm-2 control-label">{{__('Site URL')}}</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('app_url', $settings['app']['app_url'] ?? null, array('placeholder' => __('Site URL'),'class' => 'form-control')) !!}                                                                                    
                                    </div>
                                </div>   
                            </div>

                            <div class="box-footer">
                                <a class="btn btn-default" role="button" href="{{ route('settings.index') }}">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                            </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="tab-pane" id="mail">                   
                        {!! Form::model($settings['mail'], ['method' => 'PATCH','route' => ['settings.update', 'mail'], 'class' => 'form-horizontal', 'id'=>'mail_form']) !!}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="mail_driver" class="col-sm-2 control-label">{{__('Mail protocol')}}</label>

                                    <div class="col-sm-10">                                        
                                        {!! Form::text('mail_driver', $settings['mail']['mail_driver'] ?? null, array('placeholder' => __('Mail protocol'),'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="mail_host" class="col-sm-2 control-label">{{__('Mail host')}}</label>

                                    <div class="col-sm-10">                                        
                                        {!! Form::text('mail_host', $settings['mail']['mail_host'] ?? null, array('placeholder' => __('Mail host'),'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="mail_port" class="col-sm-2 control-label">{{__('Mail port')}}</label>

                                    <div class="col-sm-10">                                        
                                        {!! Form::text('mail_port', $settings['mail']['mail_port'] ?? null, array('placeholder' => __('Mail port'),'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="mail_username" class="col-sm-2 control-label">{{__('Mail login')}}</label>

                                    <div class="col-sm-10">                                        
                                        {!! Form::text('mail_username', $settings['mail']['mail_username'] ?? null, array('placeholder' => __('Mail login'),'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="mail_password" class="col-sm-2 control-label">{{__('Mail password')}}</label>

                                    <div class="col-sm-10">                                        
                                        {!! Form::password('mail_password', array('placeholder' => __('Mail password'),'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="mail_encryption" class="col-sm-2 control-label">{{__('Mail encryption')}}</label>

                                    <div class="col-sm-10">                                        
                                        {!! Form::text('mail_encryption', $settings['mail']['mail_encryption'] ?? null, array('placeholder' => __('Mail encryption'),'class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <a class="btn btn-default" role="button" href="{{ route('settings.index') }}">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    
                </div>                
            </div>
            
        </div>
    </div>

@endsection

@push('styles')

@endpush

@push('scripts')    
    <script>
        $(document).ready(function () {
            var hashes = ['general', 'mail'];

            if (typeof window.location.hash != "undefined") {
                var hash = window.location.hash.replace(/#/gi, '');
                if (hashes.indexOf(hash) != -1) {
                    $('.js-main-tabs li, .tab-pane').removeClass('active');
                    $('#' + hash).addClass('active');
                    $('.js-main-tabs li').each(function () {
                        if ($(this).find('a').attr('href') === '#' + hash)
                            $(this).addClass('active');
                    });
                }
            }

            $(document).on('click', '.js-main-tabs li', function (e) {
                window.location.hash = $(this).find('a').attr('href');
            });

        });

    </script>
@endpush