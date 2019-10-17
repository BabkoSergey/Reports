@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Report') }} {{ $user->getShortFullName() }} @endsection

@section('sub_title') {{ __('for') }} {{ $date }}@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include('admin.templates.action_notifi')
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary dev-action-box collapsed-box">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <h3 class="text-primary pull-left" style="margin: 0 10px 0 0;line-height: 34px;">{{ __('Date') }}: {{ $date }}</h3>
                        <div class="btn-group pull-right">
                            <a href="{{route('dev_reports.index')}}?date={{ date('Y-m-d', strtotime('-1 day', strtotime($date) )) }}" class="btn btn-info">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <button id="date_select" type="button" class="btn btn-primary">
                                <input type="text" class="form-control" value="{{ $date }}" style="display: none">
                                <i class="fa fa-calendar"></i>
                            </button>
                            <a href="{{route('dev_reports.index')}}?date={{ date('Y-m-d', strtotime('+1 day', strtotime($date) )) }}" class="btn btn-info">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="box-tools pull-right margin-t-5">
                        <span class="text-success" style="display: inline-block; line-height: 34px; margin-right: 20px;">
                            <b>{{ __('Time')}}:</b> <span class="jq_dev-all-time" style="display: inline-block;min-width: 50px;font-weight: bold;"></span>
                        </span>
                        
                        @if(Auth::user()->hasPermissionTo('add dev_report'))
                            <button type="button" class="btn btn-success btn-collapsed-box-collapse pull-right jq_report-show-form-btn">
                                + {{ __('Add new') }}
                            </button>
                            <button type="button" class="btn btn-default pull-right jq_dev-cancel-add jq_report-cancel-form-btn hidden">{{__('Cancel')}}</button>                            
                        @endif                    
                    </div>              
                </div>

                @if(Auth::user()->hasAnyPermission(['add dev_report']))
                    <div class="box-body">                        
                        @include('admin.dev_report.create_form_box')                
                    </div>  

                    <div class="box-footer">        
                        <button type="button" class="btn btn-default pull-left jq_dev-cancel-add">{{__('Cancel')}}</button>                
                        <button type="submit" class="btn btn-success pull-right jq_dev-submit-add">{{__('Save')}}</button>
                    </div>
                @endif
            </div>

            <table id="js-template-dev_report" style="display: none;">        
                @include('admin.dev_report.report_row')
            </table>      
            <table class="table table-striped bg-gray">
                <thead class="thead-dark bg-light-blue">
                    <tr>
                        <th colspan="2">{{ __('Type') }}</th>
                        <th>{{ __('Task') }}</th>                        
                        <th>{{ __('Is Done') }}</th>
                        <th>{{ __('Note') }}</th>
                        <th style="width: 80px">{{ __('Time') }}</th>
                        <th style="width: 80px">{{ __('Actions') }}</th>
                    </tr>
                </thead>    
                <tbody class="jq_dev_report-body">                
                    @foreach($reports ?? [] as $report)
                        @include('admin.dev_report.report_row', $report)                        
                    @endforeach                    
                </tbody>
            </table>
        </div>    
    </div>

    @if(Auth::user()->hasAnyPermission(['append resources', 'append tasks', 'edit dev_report']))
        @include('admin.templates.modal_actions')
    @endif
    
    @if(Auth::user()->hasPermissionTo('delete dev_report'))
        <div style="display: none">
            <form id="jq_report-delete-form" method="POST" action="" data-url="{{ url('/admin/dev_reports/') }}" accept-charset="UTF-8">
                @csrf
                <input name="_method" type="hidden" value="DELETE">    
                <input class="btn btn-danger" type="submit" value="Delete">
            </form>
        </div>
    @endif

@endsection

@push('styles')    
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@push('scripts')      
    <script src="{{ asset('/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    
    <script>        
        $(function () { 
            $( document ).ready(function() {
                
                var formateDate = 'yyyy-mm-dd';
        
                $('#date_select').datepicker({
                        autoclose:  true,
                        format:     formateDate,
                        startDate:  moment($('#date_select input').val(), formateDate).formateDate
                })
                .on('changeDate', function(e) {
                    window.location.search = 'date='+$('#date_select input').val();
                });
                
                updateAllTimeReports();
                
                function updateAllTimeReports(){
                    var allTimeReports = 0; 
                    
                    $('.jq_report-time').each(function(){
                        var thisTime = $(this).text().split(':');
                        if(thisTime.length > 1)
                            allTimeReports += parseInt(thisTime[0])*60 + parseInt(thisTime[1]);                        
                    });
                    
                    $('.jq_dev-all-time').text( Math.floor(allTimeReports / 60) + ':' + ('0'+(allTimeReports % 60)).slice(-2) );
                }
                
                @if(Auth::user()->hasAnyPermission(['add dev_report']))
                    var devForm = 'jq_form-dev_reports';
                    $(document).on('click', '.jq_report-show-form-btn', function(){                    
                        $('.jq_report-show-form-btn,.jq_report-cancel-form-btn').toggleClass('hidden');
                    });
                                        
                    $(document).on('click', '.jq_dev-cancel-add', function(){                                       
                        $(this).closest('.box').find('.btn-collapsed-box-collapse').click();                        
                        clearForm(devForm);
                    });
                    
                    $(document).on('click', '.jq_dev-submit-add', function(){                    
                        var form = $('#'+devForm);                        
                        if(!checkReqireFields(form)) return false;
                        
                        $.post(form.attr('action'),  form.serialize())
                            .done(function(data) {                                                                  
                                if(data.type == 'store'){
                                    var reportBox = $('#js-template-dev_report tr').first().clone();

                                    reportBox.attr('id', 'report-' + data.report.id).attr('data-report', data.report.id);
                                    
                                    @if(Auth::user()->hasPermissionTo('edit dev_report'))
                                        reportBox.find('.jq_report-edit-btn').attr('data-url', reportBox.find('.jq_report-edit-btn').attr('data-url').substring(0, reportBox.find('.jq_report-edit-btn').attr('data-url').length - 5)+ data.report.id + '/edit');                            
                                    @endif
                                    
                                    @if(Auth::user()->hasPermissionTo('delete dev_report'))
                                        reportBox.find('.jq_report-delete').attr('data-jq_report', data.report.id);
                                    @endif
                                    
                                    reportBox.appendTo('.jq_dev_report-body');                              
                                }                        
                                updateReportBox(data.report);
                                
                                AddJsNotifi('success', '{{ __('Success') }}!', data.success);
                                clearForm(devForm);
                            })
                            .fail(function(error) {                                       
                                if(typeof error.responseJSON.error == 'object' && Object.values(error.responseJSON.error).length >= 1){
                                    $.each(error.responseJSON.error, function(key, val){
                                        AddJsNotifi('danger', '{{ __('Error') }}!', val); 
                                    });
                                }else{
                                    AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error saving form') }}'); 
                                }
                                $('.js-modal-submite').prop('disabled', false);
                            });                        
                    });
                    
                    function updateReportBox(report){                                 
                        var reportBox = $('#report-'+report.id);

                        reportBox.find('.jq_report-type').text(report.type);
                        reportBox.find('.jq_report-resource').text(report.resource);
                        reportBox.find('.jq_report-task').text(report.task);
                        reportBox.find('.jq_report-is_done').text(report.is_done);
                        reportBox.find('.jq_report-note').text(report.note);
                        reportBox.find('.jq_report-time').text(report.time);
                        
                        updateAllTimeReports();
                    }
                                        
                    function clearForm(id){
                        $('#'+id+' textarea').val('').text('');
                        $('#'+id+' input[type=number]').val(0);
                    }
                    
                @endif
                
                @if(Auth::user()->hasPermissionTo('delete dev_report'))
                
                    $(document).on('click','.jq_report-delete',function (e){
                        e.preventDefault(); 
                        $('#jq_report-delete-form').attr('action',$('#jq_report-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_report'));
                        confirmDeleteReport($(this));                    
                    });       

                    function confirmDeleteReport(eElement){
                        var dialog = bootbox.dialog({
                            title: "{{__('Are you sure you want to delete report?')}}",
                            message: " ",
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
                                        var form = $('#jq_report-delete-form');

                                        $.post(form.attr('action'),  form.serialize())
                                            .done(function(data) {                                              
                                                eElement.closest('.jq_report-row').remove();
                                                AddJsNotifi('success', '{{ __('Success') }}!', data.success);
                                                updateAllTimeReports();
                                            })
                                            .fail(function(error) {                                       
                                                AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error delete report') }}');
                                            });
                                    }
                                }
                            }
                        });
                    }

                @endif
                
                @if(Auth::user()->hasAnyPermission(['append resources', 'append tasks', 'edit dev_report']))
                    $("#modal-actions").on('hidden.bs.modal', function (e) {
                        var response = $("#modal-actions").attr('data-response');
                        
                        if(!response) return false;

                        response = JSON.parse(response);

                        if(response.resource){
                            var resourceObj = response[response.resource];
                            var selectId = response.resource == 'task' ? 'tasks' : 'resources';
                            
                            $('#dev_reports-'+selectId+' option').each(function(){
                                $(this).prop('selected', false);
                            });                    
                            
                            $('#dev_reports-'+selectId).append('<option value="'+resourceObj.id+'" selected>'+resourceObj.name+'</option>');
                            $('#dev_reports-'+selectId).trigger('change');
                                            
                            AddJsNotifi('success', '{{ __('Success') }}!', response.success);
                        }
                        
                        if(response.report && response.type == 'update'){
                            updateReportBox(response.report);
                            console.log(response.report);
                            AddJsNotifi('success', '{{ __('Success') }}!', response.success);
                        }
                    });
                @endif
                
            });
        });
    </script>
@endpush