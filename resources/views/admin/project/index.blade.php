@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Projects') }} @endsection

@section('sub_title') {{ __('List') }} @endsection

@section('content_title_add') @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @include('admin.templates.action_notifi')
        
        <div class="box">
            <div class="box-header"></div>
            
            <div class="box-body">
                <table id="projects-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>                            
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Title') }}</th>                            
                            <th>{{ __('Note') }}</th>
                            <th style="width: 100px;">{{ __('Condition') }}</th>   
                            <th style="width: 130px;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Note') }}</th>
                            <th style="width: 100px;">{{ __('Condition') }}</th>   
                            <th style="width: 130px;">{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="box-footer"></div>
        </div>
    </div>
    
</div>

@if(Auth::user()->hasAnyPermission(['add projects', 'edit projects']))
    @include('admin.templates.modal_actions')
@endif

@if(Auth::user()->hasPermissionTo('delete projects'))
    <div style="display: none">
        <form id="jq_projects-delete-form" method="POST" action="" data-url="{{ url('/admin/projects/') }}" accept-charset="UTF-8">
            @csrf
            <input name="_method" type="hidden" value="DELETE">    
            <input class="btn btn-danger" type="submit" value="Delete">
        </form>
    </div>
@endif

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@push('scripts')    
    <script src="{{ asset('/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>    
    
    <script>
      $(function () {  
          
            @if(Auth::user()->hasPermissionTo('add projects'))
                $("#projects-table").one("preInit.dt", function () {
                    $button = $('<button class="btn btn-success margin-l-10" role="button" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Add new project') }}" data-url="{{ route('projects.create') }}"> {{ __('Add new project') }}</button>');
                    $("#projects-table_filter label").append($button);
                    $button.button();
                });                                    
            @endif

            var projects = $('#projects-table').DataTable({
                    'ajax': "{{ url('admin/projects_dt_ajax') }}",
                    'paging'      : true,
                    'lengthChange': true,
                    'searching'   : true,
                    'ordering'    : true,
                    'info'        : true,
                    'autoWidth'   : true,
                    'language': {
                        'url': '{{ asset('/bower_components/datatables.net/'.App::getLocale().'.json') }}'
                    },
                    'columnDefs': [
                        {
                            "targets": [ 0 ],
                            "visible": false,
                            "searchable": false
                        },                
                    ],
                    'order': [[2, 'desc']],
                    'columns': [
                        {   data: 'id' },
                        {   data: 'name'},                        
                        {   data: 'note'}, 
                        {   data: 'status',
                            render: function ( data, type, row ) {
                                return data ? '<span class="text-green">{{ __('Active') }}</span>' : '<span class="text-red">{{ __('Inactive') }}</span>';
                            }
                        },                        
                        {   data: 'actions',
                            orderable: false,
                            render: function ( data, type, row ) {
                                var actions = '';

                                actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                                actions += '<a href="{{ url('admin/projects') }}/'+row.id+'" class="btn btn-success"><i class="fa fa-eye"></i></a>';
                                actions += '</div>';

                                @if(Auth::user()->hasPermissionTo('edit projects'))
                                    actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                                    actions += '<button class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Edit project') }} '+row.name+'" data-url="{{ url('admin/projects') }}/'+row.id+'/edit" ><i class="fa fa-pencil"></i></button>';
                                    actions += '</div>';
                                @endif

                                @if(Auth::user()->hasPermissionTo('delete projects'))
                                    actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                                    actions += '<a href="" class="btn btn-danger jq_projects-delete" data-jq_projects="'+row.id+'"><i class="fa fa-trash"></i></a>';
                                    actions += '</div>';
                                @endif

                                return actions;
                            }
                        }
                    ]
                }); 
            
            @if(Auth::user()->hasAnyPermission(['add projects', 'edit projects']))
                $("#modal-actions").on('hidden.bs.modal', function (e) {
                    var response = $("#modal-actions").attr('data-response');

                    if(!response) return false;

                    response = JSON.parse(response);
                    
                    if(response.project){
                        if(response.type == 'store'){
                            projects.row.add(response.project).draw();
                        }else{
                            projects.rows().every( function () {                        
                                if(this.data().id == response.project.id){
                                    this.data(response.project).invalidate();
                                }                                
                            });
                        }
                        
                        AddJsNotifi('success', '{{ __('Success') }}!', response.success);
                    }
                });
            @endif
                
            @if(Auth::user()->hasPermissionTo('delete projects'))
                
                $(document).on('click','.jq_projects-delete',function (e){
                    e.preventDefault(); 
                    $('#jq_projects-delete-form').attr('action',$('#jq_projects-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_projects'));
                    confirmDelete($(this));                    
                });       
                
                function confirmDelete(eElement){
                    var dialog = bootbox.dialog({
                        title: "{{__('Are you sure you want to delete a project?')}}",
                        message: "<p>{{__('All supported project info will be deleted!')}}</p>",
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
                                    var form = $('#jq_projects-delete-form');
                                                                        
                                    $.post(form.attr('action'),  form.serialize())
                                        .done(function(data) {  
                                            projects.row(eElement.parents('tr')).remove().draw(false);
                                            AddJsNotifi('success', '{{ __('Success') }}!', data.success);
                                        })
                                        .fail(function(error) {                                       
                                            AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error delete project') }}');
                                        });
                                }
                            }
                        }
                    });
                }
            
            @endif
      });
    </script>
@endpush