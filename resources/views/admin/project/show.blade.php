@extends('admin.layouts.app')

@section('htmlheader_title') {{$project->name}} @endsection

@section('sub_title') @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        @include('admin.templates.action_notifi')
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Info') }}</h3>
                
                <div class="box-tools pull-right">
                    @if(Auth::user()->hasPermissionTo('edit projects'))
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Edit project') }} {{$project->name}}" data-url="{{ url('admin/projects') }}/{{$project->id}}/edit">
                            <i class="fa fa-pencil"></i>
                        </button>
                    @endif
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>                    
                </div>              
            </div>
            
            <div class="box-body">                
                <h4 class="box-title js-project-name">{{$project->name}}</h4>
                <h4 class="js-project-status">{!! $project->status ? '<span class="text-green">'.__('Active').'</span>' : '<span class="text-red">'.__('Inactive').'</span>' !!}</h4>
                <p class="js-project-note">{!! $project->note !!}</p>                              
            </div>            
        </div>
    </div>    
</div>

<h2 class="page-header">
    {{ __('Project tasks') }}
    
    @if(Auth::user()->hasPermissionTo('add tasks'))
        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Add new task') }}" data-url="{{ route('tasks.create') }}?resourse={{$project->id ?? ''}}&type=project">
            + {{ __('Add new task') }}
        </button>
    @endif
        
</h2>

<div class="row">
    <div class="col-md-12">
        @include('admin.task.show_box', $resource = $project)
    </div>    
</div>

@if(Auth::user()->hasAnyPermission(['edit projects', 'add tasks']))
    @include('admin.templates.modal_actions')
@endif

@endsection

@push('styles')    
    
@endpush

@push('scripts') 
        
    <script>
        $(function () {  
            
            @if(Auth::user()->hasAnyPermission(['edit projects']))
                $("#modal-actions").on('hidden.bs.modal', function (e) {
                    var response = $("#modal-actions").attr('data-response');
                    var active = '{{ __('Active') }}', inactive = '{{ __('Inactive') }}';

                    if(!response) return false;
                    
                    response = JSON.parse(response);
                    
                    if(response.project){
                        AddJsNotifi('success', '{{ __('Success') }}!', response.success);
                        
                        $('.js-project-name, .content-header h1, head title').text(response.project.name);
                        $('.js-project-status').html(response.project.status ? '<span class="text-green">'+active+'</span>' : '<span class="text-red">'+inactive+'</span>');
                        $('.js-project-note').text(response.project.note);                    
                    }
                });
            @endif
            
        });        
    </script>
@endpush