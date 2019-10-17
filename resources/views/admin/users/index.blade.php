@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Users') }} @endsection

@section('sub_title') {{ __('List') }} @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @include('admin.templates.action_notifi')
        
        <div class="box">
            <div class="box-header">
                
            </div>
            
            <div class="box-body">
                <table id="users-table" class="table table-bordered table-striped tabel-mass-actions">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="users-select-all"></th>
                            <th>{{__('Avatar')}}</th>
                            <th>{{ __('Login') }}</th>
                            <th>{{ __('Full name') }}</th>
                            <th>{{ __('E-mail') }}</th>                             
                            <th>{{ __('Phone') }}</th>                             
                            <th>{{ __('Roles') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Condition') }}</th>
                            <th style="width: 175px;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>{{__('Avatar')}}</th>
                            <th>{{ __('Login') }}</th>
                            <th>{{ __('Full name') }}</th>
                            <th>{{ __('E-mail') }}</th>                             
                            <th>{{ __('Phone') }}</th>                             
                            <th>{{ __('Roles') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Condition') }}</th>
                            <th style="width: 175px;">{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>            
            
        </div>
        
    </div>
    
</div>

@if(Auth::user()->hasPermissionTo('delete users'))
    <div style="display: none">
        <form id="jq_user-delete-form" method="POST" action="" data-url="{{ url('/admin/users/') }}" accept-charset="UTF-8">
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
          
        @if(Auth::user()->hasPermissionTo('add users'))
            $("#users-table").one("preInit.dt", function () {

                $button = $('<a class="btn btn-success margin-l-10" role="button" href="{{ route('users.create') }}"> {{ __('Add user') }}</a>');
                $("#users-table_filter label").append($button);
                $button.button();

            });                                    
        @endif
        
        @if(Auth::user()->hasAnyPermission(['delete users', 'edit users']))
            $("#users-table").one("preInit.dt", function () {
                var buttonDelete = buttonHold = buttonActive = '';
                
                @if(Auth::user()->hasPermissionTo('delete users'))
                    buttonDelete = '<a class="btn btn-danger margin-r-10 jq_mass" data-action="delete" role="button" href=""><i class="fa fa-trash"></i></a>';
                @endif
                
                @if(Auth::user()->hasPermissionTo('edit users'))
                    buttonHold = '<a class="btn btn-warning margin-r-10 jq_mass" data-action="activate" role="button" href=""><i class="fa fa-ban"></i></a>';
                    buttonActive = '<a class="btn btn-info margin-r-10 jq_mass" data-action="hold" role="button" href=""><i class="fa fa-asterisk"></i></a>';
                @endif
                
                $("#users-table_filter label").prepend('<div class="dt-mass-block">'+buttonActive+buttonHold+buttonDelete+'</div>');
                $button.button();
            }); 
        @endif
        
        var usersTable = $('#users-table').DataTable({
            ajax: "{{ url('admin/users_dt_ajax') }}",
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'language': {
                'url': '{{ asset('/bower_components/datatables.net/'.App::getLocale().'.json') }}'
            },
            'order': [[1, 'asc']],
            columns: [
                {   data: 'id',
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, full, meta){
                        return '<input type="checkbox" name="id[]" class="jq_dt-checkbox" value="' + $('<div/>').text(data).html() + '">';
                    }
                },
                {   data: 'logo',
                    render: function ( data, type, row ) {                        
                        return data ? '<img class="dt-logo" src="'+data+'">' : '';
                    }
                },
                {   data: 'name'},
                {   data: 'first_name',
                    render: function ( data, type, row ) {                        
                        return (row.last_name ? row.last_name + ' ' : '') + (row.first_name ? row.first_name + ' ' : '') + (row.patron_name ? row.patron_name + ' ' : '');
                    }
                },
                {   data: 'email'},
                {   data: 'phone'},
                {   data: 'roles'},
                {   data: 'pos_status',
                    render: function ( data, type, row ) {                        
                        return data ? '<span class="text-green"><i class="fa fa-check"></i> '+row.position+'</span>' : '<span class="text-red"><i class="fa fa-ban"></i> '+row.position+'</span>' ;
                    }
                },                
                {   data: 'status',
                    render: function ( data, type, row ) {                        
                        return data ? '<span class="text-green">{{ __('Active') }}</span>' : '<span class="text-red">{{ __('Blocked') }}</span>' ;
                    }
                },                
                {   data: 'actions',
                    orderable: false,
                    render: function ( data, type, row ) {
                        var actions = '';
                                                
                        actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                        actions += '<a href="{{ url('admin/users') }}/'+row.id+'" class="btn btn-success"><i class="fa fa-eye"></i></a>';
                        actions += '</div>';
                        
                        @if(Auth::user()->hasPermissionTo('edit users'))
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                            actions += '<a href="{{ url('admin/users') }}/'+row.id+'/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
                            actions += '</div>';
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('delete users'))
                        
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-'+(row.status?"warning":"info")+' jq_user-ban" data-jq_user="'+row.id+'"><i class="fa '+(row.status?"fa-ban":"fa-asterisk")+'"></i></a>';
                            actions += '</div>';

                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-danger jq_user-delete" data-jq_user="'+row.id+'"><i class="fa fa-trash"></i></a>';
                            actions += '</div>';
                        
                        @endif
                        
                        return actions;
                    }
                }
            ]
        });    
        
        usersTable.on( 'page.dt', function () {
            unselectAllCheckbox();
        } );
        
        usersTable.on( 'draw.dt', function () {
            unselectAllCheckbox();
        } );
        
        $('#users-select-all').on('click', function(){           
           var rows = usersTable.rows({ 'page': 'current','search': 'applied' }).nodes();           
           $('input[type="checkbox"]', rows).prop('checked', this.checked);   
           isCheckboxChecked();
        });
        $('#users-table tbody').on('change', 'input[type="checkbox"]', function(){           
           if(!this.checked){
              var el = $('#users-select-all').get(0);              
              if(el && el.checked && ('indeterminate' in el)){
                 el.indeterminate = true;
              }
           }
           isCheckboxChecked();
        });
        
        $(document).on('click','.jq_mass',function (e){
            e.preventDefault(); 
            var action = $(this).attr('data-action');
            var ids = isCheckboxChecked(ids);                        
            if(action == 'delete'){
                $('#jq_user-delete-form').attr('action',$('#jq_user-delete-form').attr('data-url')+ '/' + ids);
                confirmDelete();
            }else{                
                userBan(ids, action);
            }
        });
        
        function unselectAllCheckbox(){
            var rows = usersTable.rows({ 'page': 'all','search': 'applied' }).nodes();           
            $('input[type="checkbox"]', rows).prop('checked', false);
            $('#users-select-all').prop('checked', false);
            $('.jq_mass').hide();
        }
        
        function isCheckboxChecked(){
            
            var ids = [];
            
            $('.jq_dt-checkbox').each(function(){                
                if($(this)[0].checked == true){
                    ids.push($(this).val());
                }                
            });
                        
            ids = ids.join(',');
            if(ids){
                $('.jq_mass').show();
            }else{
                $('.jq_mass').hide();
            }
                
            return ids;
        }
        
        $(document).on('click','.jq_user-delete',function (e){
            e.preventDefault(); 
            $('#jq_user-delete-form').attr('action',$('#jq_user-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_user'));
            confirmDelete();            
        }); 
        
        function confirmDelete(){
            var dialog = bootbox.dialog({
                title: "{{ __('Are you sure you want to delete a user?') }}",
                message: "<p>{{ __('Maybe better to block access?') }}</p>",
                buttons: {
                    cancel: {
                        label: "{{ __('Cancel') }}",
                        className: 'btn-default pull-left',
                        callback: function(){
                        }
                    },                    
                    delere: {
                        label: "{{ __('Delete') }}",
                        className: 'btn-danger pull-right',
                        callback: function(){                            
                            $('#jq_user-delete-form').submit();
                        }
                    }
                }
            });
        }
        
        $(document).on('click','.jq_user-ban',function (e){
            e.preventDefault(); 
            userBan($(this).attr('data-jq_user'));      
        }); 
                        
        function userBan(ids, action=''){
            
            var href = '{{ url('admin/users') }}/'+ids+'/ban';
            
            $.get(href, {action: action, _token: $("input[name=_token]").val()})
		.done(function (data) {
                    if(data.success == 'ok'){
                        $.each( data.statuses, function( key, value ) {
                            usersTable.rows().every( function () {
                                var d = this.data();
                                if(d.id == key){
                                    d.status = value;
                                    this.invalidate();
                                }                                
                            } );
                        });  
                        
                        usersTable.draw();                                                
                    }                    
            });
        }
                  
      });
    </script>
@endpush