@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Estimates') }} @endsection

@section('sub_title') {{ __('List') }} @endsection

@section('content_title_add') @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @include('admin.templates.action_notifi')
        
        <div class="box">
            <div class="box-header"></div>
            
            <div class="box-body">
                <table id="estimates-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>                            
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Title') }}</th>                            
                            <th>{{ __('Note') }}</th>
                            <th style="width: 100px;">{{ __('Condition') }}</th>
                            <th style="width: 100px;">{{ __('Type') }}</th>
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
                            <th style="width: 100px;">{{ __('Type') }}</th>
                            <th style="width: 130px;">{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="box-footer"></div>
        </div>
    </div>
    
</div>

@if(Auth::user()->hasAnyPermission(['add estimates', 'edit estimates']))
    @include('admin.templates.modal_actions')
@endif

@if(Auth::user()->hasPermissionTo('delete estimates'))
    <div style="display: none">
        <form id="jq_estimates-delete-form" method="POST" action="" data-url="{{ url('/admin/estimates/') }}" accept-charset="UTF-8">
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
          
            @if(Auth::user()->hasPermissionTo('add estimates'))
                $("#estimates-table").one("preInit.dt", function () {
                    $button = $('<button class="btn btn-success margin-l-10" role="button" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Add new estimate') }}" data-url="{{ route('estimates.create') }}"> {{ __('Add new estimate') }}</button>');
                    $("#estimates-table_filter label").append($button);
                    $button.button();
                });                                    
            @endif

            var estimates = $('#estimates-table').DataTable({
                    'ajax': "{{ url('admin/estimates_dt_ajax') }}",
                    'paging'      : true,
                    'lengthChange': true,
                    'searching'   : true,
                    'ordering'    : true,
                    'info'        : true,
                    'autoWidth'   : false,
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
                        {   data: 'view'},                        
                        {   data: 'actions',
                            orderable: false,
                            render: function ( data, type, row ) {
                                var actions = '';

                                actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                                actions += '<a href="{{ url('admin/estimates') }}/'+row.id+'" class="btn btn-success"><i class="fa fa-eye"></i></a>';
                                actions += '</div>';

                                @if(Auth::user()->hasPermissionTo('edit estimates'))
                                    actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                                    actions += '<button class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal-actions" data-title="{{ __('Edit estimate') }} '+row.name+'" data-url="{{ url('admin/estimates') }}/'+row.id+'/edit" ><i class="fa fa-pencil"></i></button>';
                                    actions += '</div>';
                                @endif

                                @if(Auth::user()->hasPermissionTo('delete estimates'))
                                    actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                                    actions += '<a href="" class="btn btn-danger jq_estimates-delete" data-jq_estimates="'+row.id+'"><i class="fa fa-trash"></i></a>';
                                    actions += '</div>';
                                @endif

                                return actions;
                            }
                        }
                    ]
                }); 
            
            @if(Auth::user()->hasAnyPermission(['add estimates', 'edit estimates']))
                $("#modal-actions").on('hidden.bs.modal', function (e) {
                    var response = $("#modal-actions").attr('data-response');

                    if(!response) return false;

                    response = JSON.parse(response);
                    
                    if(response.estimate){
                        if(response.type == 'store'){
                            estimates.row.add(response.estimate).draw();
                        }else{
                            estimates.rows().every( function () {                        
                                if(this.data().id == response.estimate.id){
                                    this.data(response.estimate).invalidate();
                                }                                
                            });
                        }
                        
                        AddJsNotifi('success', '{{ __('Success') }}!', response.success);
                    }
                });
            @endif
                
            @if(Auth::user()->hasPermissionTo('delete estimates'))
                
                $(document).on('click','.jq_estimates-delete',function (e){
                    e.preventDefault(); 
                    $('#jq_estimates-delete-form').attr('action',$('#jq_estimates-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_estimates'));
                    confirmDelete($(this));                    
                });       
                
                function confirmDelete(eElement){
                    var dialog = bootbox.dialog({
                        title: "{{__('Are you sure you want to delete a estimate?')}}",
                        message: "<p>{{__('All estimate info will be deleted!')}}</p>",
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
                                    var form = $('#jq_estimates-delete-form');
                                                                        
                                    $.post(form.attr('action'),  form.serialize())
                                        .done(function(data) {  
                                            estimates.row(eElement.parents('tr')).remove().draw(false);
                                            AddJsNotifi('success', '{{ __('Success') }}!', data.success);
                                        })
                                        .fail(function(error) {                                       
                                            AddJsNotifi('danger', '{{ __('Error') }}!', '{{ __('Error delete estimate') }}');
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