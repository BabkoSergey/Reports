<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <span class="text-green js-positions-status-check {{ $user->pos_status ? '' : 'is-hidden' }}"><i class="fa fa-check"></i></span>
            <span class="text-red js-positions-status-ban {{ $user->pos_status ? 'is-hidden' : '' }}"><i class="fa fa-ban"></i></span>
            <span class="js-positions-current">{{ $user->getPositionName() }}</span>
        </h3>
        <div class="box-tools pull-right">
            @if(Auth::user()->hasPermissionTo('office positions edit'))                    
                <button type="button" class="btn btn-danger btn-sm jq_position-lay_off-btn {{ $user->pos_status ? '' : 'is-hidden' }}" data-user_id="{{ $user->id }}">
                    {{ __('Lay off') }}
                </button>

                <button type="button" class="btn btn-success btn-sm jq_position-add-btn"  data-toggle="modal" data-target="#modal-position" data-user_id="{{ $user->id }}" data-position="">
                    {{ __('Add position') }}
                </button>
            @endif
        </div>
    </div>    
    <div class="box-body">
        @include('admin.users.show_box.positions', ['editable' => Auth::user()->hasPermissionTo('office positions edit') ? true : false])                    
    </div>
</div>                                        

@if(Auth::user()->hasPermissionTo('office positions edit'))                    
    <div class="modal fade" id="modal-position" data-response="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ __('Position') }}</h4>
                </div>

                <div class="modal-body">                
                    <div class="box box-info modal-body-template js-modal-body"></div>

                    <div class="modal-loader on"><i class="fa fa-refresh fa-spin"></i></div>
                </div>

                <div class="modal-footer">
                    <div class="text-left js-modal-notifi"></div>                
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('Cancel')}}</button>                
                    <button type="submit" class="btn btn-primary js-modal-position-submite">{{__('Save')}}</button>
                </div>
            </div>
        </div>
    </div>
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@push('scripts')   
    <script src="{{ asset('/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            var token = $("input[name=_token]").val();
            var formateDatepickerPosition = {
                                            autoclose:  true,
                                            format:     'yyyy-mm-dd',
                                            startDate:  moment().formateDate,
                                            minDate:    moment().formateDate,
                                            startView:  'years',
                                        };
                                        
            $(document).on('click','.jq_positions-delete-btn',function (e){
                e.preventDefault(); 
                
                $('#jq_positions-delete-form').attr('action',$('#jq_positions-delete-form').attr('data-url')+ '/' + $(this).val());
                confirmDelete($(this).val());            
            }); 

            function confirmDelete(position){
                var dialog = bootbox.dialog({
                    title: "{{ __('Are you sure you want to delete a position?') }}",
                    message: "<p></p>",
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
                                $.post($('#jq_positions-delete-form').attr('action'), $('#jq_positions-delete-form').serialize())
                                    .done(function(data) { 
                                        
                                        $('.js_positions-row-'+position).remove();
                                        updateBoxHeader(data.userPosition);
                                            
                                        window.scrollTo( 0, 0 );
                                        AddJsNotifi('success', '{{ __('Success') }}!', '{{ __('Deleted successfully') }}!');                        
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
                            }
                        }
                    }
                });
            }
            
            $(document).on('click','.jq_position-lay_off-btn',function (e){   
                layOnOff('{{route('positions.lay_off',['user_id'=>$user->id])}}');                
            });
            
            $(document).on('click','.jq_position-lay_on-btn',function (e){            
                layOnOff('{{route('positions.lay_on',['user_id'=>$user->id])}}');
            });
            
            function layOnOff(action){
                $.post(action, {_token: $("input[name=_token]").val()})                
                    .done(function(data) { 
                        updateBoxHeader(data.userPosition);
                        updateBoxRow(data.position);
                        window.scrollTo( 0, 0 );
                        AddJsNotifi('success', '{{ __('Success') }}!', '');                        
                    })
                    .fail(function(error) { 
                        window.scrollTo( 0, 0 );
                        if(typeof error.responseJSON.error == 'object' && Object.values(error.responseJSON.error).length >= 1){
                            $.each(error.responseJSON.error, function(key, val){
                                AddJsNotifi('danger', '{{ __('Error') }}!', val); 
                            });
                        }else{
                            AddJsNotifi('danger', '{{ __('Error') }}!', ''); 
                        }
                    });
            }
            
            function addBoxRow(positionBefore, positionID){
                var tmpBlock = $('#js-template-row-positions .js_positions-row').first().clone();                    
                tmpBlock.removeClass('js_positions-row-').addClass('js_positions-row-'+positionID);
                tmpBlock.find('.js_positions-row-actions').html('');

                if(positionBefore){
                    tmpBlock.insertAfter('.js_positions-row-'+positionBefore);
                }else{
                    tmpBlock.prependTo('.js-positions-box-body');                    
                }                
            }
            
            function updateBoxRow(position){
                var row = $('.js_positions-row-'+position.id);                    
                
                row.find('.js_positions-row-name').text(position.position);
                row.find('.js_positions-row-from').text(position.from);
                row.find('.js_positions-row-to').text(position.to);
                row.find('.js_positions-row-actions').html('');
                
                if(position.actions.lay_on){
                    var tmpBlockLayOn = $('#js-template-row-positions .js_positions-row .jq_position-lay_on-btn').first().clone();
                    tmpBlockLayOn.val(position.id);
                    row.find('.js_positions-row-actions').append(tmpBlockLayOn);
                }
                if(position.actions.delete){                    
                    var tmpBlockDelete = $('#js-template-row-positions .js_positions-row .jq_positions-delete-btn').first().clone();
                    tmpBlockDelete.val(position.id);
                    row.find('.js_positions-row-actions').append(tmpBlockDelete);
                }
                if(position.actions.edit){
                    var tmpBlockEdit = $('#js-template-row-positions .js_positions-row .jq_positions-edit-btn').first().clone();
                    tmpBlockEdit.val(position.id);
                    row.find('.js_positions-row-actions').append(tmpBlockEdit);
                }
                
            }
            
            function updateBoxHeader(userPosition){                
                if(userPosition.pos_status){
                    $('.js-positions-status-ban').addClass('is-hidden');
                    $('.js-positions-status-check, .jq_position-lay_off-btn').removeClass('is-hidden');                    
                }else{
                    $('.js-positions-status-ban').removeClass('is-hidden');
                    $('.js-positions-status-check, .jq_position-lay_off-btn').addClass('is-hidden');
                }
                $('.js-positions-current').text(userPosition.pos_name);
            }
                                        
            $(document).ready(function () {
                $("#modal-position").on('shown.bs.modal', function (e) {                    
                    $("#modal-position").attr('data-response', '');
                    $('#modal-position .js-modal-title').text($(e.relatedTarget).attr('data-title'));                
                    $('#modal-position .js-modal-body').html('');
                    addModalLoader();

                    var actionUrl = $(e.relatedTarget).attr('data-position') ? '{{ url(('/admin/positions')) }}/'+$(e.relatedTarget).attr('data-position')+'/edit' : '{{ url(('/admin/positions/create')) }}';
                    actionUrl += $(e.relatedTarget).attr('data-position') ? '' : '?user_id='+$(e.relatedTarget).attr('data-user_id');
                    $.get(actionUrl)
                        .done(function(data) {        
                            $('#modal-position .js-modal-body').html(data); 
                            $('.jq_position-from').datepicker(formateDatepickerPosition);
                            $('#modal-position .modal-loader').removeClass('on');
                            $('#modal-position .js-modal-position-submite').prop('disabled', false);                          
                        })
                        .fail(function(error) { 
                            if(typeof error.responseJSON.error == 'object' && Object.values(error.responseJSON.error).length >= 1){
                                $.each(error.responseJSON.error, function(key, val){
                                    addErrorMessage(val);                            
                                });
                            }else{
                                addErrorMessage('{{ __('Could not load form') }}');                        
                            }                            
                            $('#modal-position .modal-loader').removeClass('on');
                        });
                });

                $("#modal-position").on('hidden.bs.modal', function (e) {                    
                    $('#modal-position .js-modal-body').html('');
                });

                $('#modal-position').on('hidden.bs.modal', function(){
                    $('.modal-backdrop').remove();
                })

                $(document).on('click', '.js-modal-position-submite', function(e){
                    e.preventDefault();

                    var form = $('#modal-position').find('form');

                    if(!checkReqireFields(form)) return false;

                    addModalLoader();

                    $.post(form.attr('action'),  form.serialize())
                        .done(function(data) {  
                            updateBoxHeader(data.userPosition);
                            if(data.action && data.action == 'create'){
                                addBoxRow(data.positionBefore, data.position.id);
                            }
                            updateBoxRow(data.position);
                            $('#modal-position .modal-loader').removeClass('on');
                            $("#modal-position").modal('hide');                                                
                        })
                        .fail(function(error) {                                       
                            if(typeof error.responseJSON.error == 'object' && Object.values(error.responseJSON.error).length >= 1){
                                $.each(error.responseJSON.error, function(key, val){
                                    addErrorMessage(val);                            
                                });
                            }else{
                                addErrorMessage('{{ __('Error saving form') }}');                            
                            }
                            $('#modal-position .js-modal-position-submite').prop('disabled', false);
                            $('#modal-position .modal-loader').removeClass('on');
                        });
                });

                function addErrorMessage(message){
                    $('#modal-position .js-modal-notifi').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> {{ __('Error') }}!</h4>'+message+'</div>');
                }

                function addModalLoader(){
                    $('#modal-position .js-modal-position-submite').prop('disabled', true);
                    $('#modal-position .js-modal-notifi').html('');
                    $('#modal-position .modal-loader').addClass('on');
                }
            });
        });

    </script>
@endpush