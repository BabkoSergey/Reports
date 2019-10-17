@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('By developers') }} @endsection

@section('sub_title') {{ __('daily') }} @endsection

@section('content_title_add') 
    <div class="pull-right">
        <h3 class="margin text-primary pull-left">{{ __('Date') }}: {{ $date }}</h3>
        <div class="btn-group pull-right margin-t-5">
            <a href="{{route('reports.dev.index')}}?date={{ date('Y-m-d', strtotime('-1 day', strtotime($date) )) }}" class="btn btn-info">
                <i class="fa fa-angle-left"></i>
            </a>
            <button id="date_select" type="button" class="btn btn-primary">
                <input type="text" class="form-control" value="{{ $date }}" style="display: none">
                <i class="fa fa-calendar"></i>
            </button>
            <a href="{{route('reports.dev.index')}}?date={{ date('Y-m-d', strtotime('+1 day', strtotime($date) )) }}" class="btn btn-info">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        @include('admin.templates.action_notifi')
    </div>
</div>

<div class="row">
    
    <div class="col-md-3">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ __('Developers') }}</h3>                
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            
            <div class="box-body">
                <table class="table table-striped bg-gray">
                    <thead class="thead-dark bg-light-blue">
                        <tr>
                            <th>{{ __('Full name') }}</th>
                            <th>{{ __('Condition') }}</th>
                            <th>{{ __('Time') }}</th>                            
                        </tr>
                    </thead>    
                    <tbody class="jq_dev_report-body">                
                        @foreach($developers ?? [] as $developer)
                            <tr>
                                <th>
                                    <a class="js-report-show" href="#dev_{{ $developer->id }}" data-developer="{{ $developer->id }}">
                                        {{ $developer->getShortFullName() }}
                                    </a>
                                </th>
                                <th><span class="text-{{ __($developer->status ? 'green' : 'red') }}">{{ __($developer->status ? 'Active' : 'Blocked') }}</span></th>
                                <th>{{ $developer->time }}</th>                            
                            </tr>
                        @endforeach                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="box js-dev_reports">
            @include('admin.reports.developers.reports')
        </div>
    </div>
    
</div>
<!-- /.row -->


@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    
    <script>
      $(function () {          
        
        var formateDate = 'yyyy-mm-dd';
        
        $('#date_select').datepicker({
                autoclose:  true,
                format:     formateDate,
                startDate:  moment($('#date_select input').val(), formateDate).formateDate
        })
        .on('changeDate', function(e) {
            window.location.search = 'date='+$('#date_select input').val();
        });
                
        $(document).on('click', '.js-report-show', function(e){
            e.preventDefault();
            
            $('.loader').addClass('on');
            $.get("{{url('/admin/reports/dev_report')}}/"+$(this).attr('data-developer'))
                .done(function(data) {        
                    $('.js-dev_reports').html(data);
                    $('.loader').removeClass('on');
                })
                .fail(function(error) { 
                    $('.js-dev_reports').html('');
                    $('.loader').removeClass('on');                            
                });
            
        });
                  
      });
    </script>
@endpush