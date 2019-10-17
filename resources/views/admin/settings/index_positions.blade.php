@extends('admin.layouts.app')

@section('htmlheader_title') {{__('Positions List')}} @endsection

@section('content')

    <div class="row">
        <div class="col-md-12 jq_start_main">
            
            @include('admin.templates.action_notifi')        
            
            <div id="js-setting-row-template" style="display: none;">
                @include('admin.settings.row_key_val')
            </div>

            <div class="nav-tabs-custom js-main-tabs">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#positions" data-toggle="tab">{{__('Positions List')}}</a></li>                                    
<!--                    <li><a href="#mail" data-toggle="tab">{{__('Mail')}}</a></li>                                -->
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane active" id="positions">                         
                        {!! Form::model($settings['positions'], ['method' => 'PATCH','route' => ['settings.update', 'positions'], 'class' => 'form-horizontal', 'id'=>'positions_form']) !!}
                            <div class="box-header">
                                <a class="btn btn-success margin-l-10 jq_settings-btn-add"> {{ __('Add New') }}</a>                                
                            </div>
                        
                            <div class="box-body jq_setting-box-positions">                                 
                                @foreach($settings['positions'] as $code => $val)   
                                    @include('admin.settings.row_key_val', ['codePrefix' => 'positions'])
                                @endforeach    
                            </div><!-- /.box-body -->            
                            
                            <div class="box-footer">
                                <a class="btn btn-default" role="button" href="{{ route('settings.positions.index') }}">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-info pull-right ">{{ __('Save') }}</button>
                            </div><!-- /.box-footer -->

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
            var hashes = ['positions'];

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

            $(document).on('change, keyup','.jq_row_input-code',function (){                
                $(this).val(codefy($(this).val()));      
            });

            $(document).on('click','.jq_settings-btn-add',function (e){
                e.preventDefault(); 
                     
                insertRow($(this).closest('.tab-pane').attr('id'));
            });    
                
            $(document).on('click','.jq_setting-row-btn-delete',function (e){
                e.preventDefault(); 
                
                confirmDelete($(this));                                    
            });
            
            function confirmDelete(eElement){
                    var dialog = bootbox.dialog({
                        title: "{{__('Are you sure you want to delete this setting parametr?')}}",
                        message: "<p>{{__('All related information will be deleted!')}}</p>",
                        buttons: {
                            cancel: {
                                label: "{{__('Cancel')}}",
                                className: 'btn-default pull-left',
                                callback: function(){
                                }
                            },                    
                            delere: {
                                label: "{{__('Delete')}}",
                                className: 'btn-danger pull-right',
                                callback: function(){      
                                    $.post('{{route('settings.destroy', ['code'=> ''])}}'+'/'+eElement.closest('.tab-pane').attr('id')+'-'+eElement.closest('.js-setting-row').find('.jq_row_input-code').val(), {_method: 'DELETE', _token: $("input[name=_token]").val() })
                                        .done(function(data) {  
                                            eElement.closest('.js-setting-row').remove();                                            
                                            AddJsNotifi('success', '{{ __('Success') }}!', data.success);
                                        })
                                        .fail(function(error) {                                       
                                            AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error delete setting parametr') }}');
                                        });
                                }
                            }
                        }
                    });
                }
                
            function insertRow(type){
                var tmpBlock = $('#js-setting-row-template .js-setting-row').first().clone();  
                
                tmpBlock.find('.jq_row_input-prefix').text(type + '-');
                tmpBlock.appendTo('#'+type+' .jq_setting-box-'+type);
            }
            
            $(document).on('submit','#positions_form',function (e){
                    e.preventDefault();                                         
                    $('.alert').remove();
                    
                    var type = $(this).closest('.tab-pane').attr('id');
                    if(!($('.jq_setting-box-'+type+' .jq_row_input').length > 0)) return false;
                                        
                    ClearCheckReqireFields();
                    var form = $(this).closest('form');
                    
                    $('.jq_setting-box-'+type+' .jq_row_input').each(function(){
                        $(this).attr('name', $(this).attr('data-name')+'[]').prop('required', true);
                    });
                    
                    if(!checkReqireFields(form)) return false;
                                        
                    $.post(form.attr('action'), form.serialize())
                        .done(function(data) { 
                            window.scrollTo( 0, 0 );
                            AddJsNotifi('success', '{{ __('Success') }}!', '{{ __('Updated successfully') }}!');                        
                        })
                        .fail(function(error) { 
                            window.scrollTo( 0, 0 );
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