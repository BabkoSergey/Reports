<div class="modal fade" id="modal-actions" data-response="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title js-modal-title"></h4>
            </div>
            
            <div class="modal-body">                
                <div class="box box-info modal-body-template js-modal-body"></div>
                
                <div class="modal-loader on"><i class="fa fa-refresh fa-spin"></i></div>
            </div>
            
            <div class="modal-footer">
                <div class="text-left js-modal-notifi"></div>                
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('Cancel')}}</button>                
                <button type="submit" class="btn btn-primary js-modal-submite">{{__('Save')}}</button>
            </div>
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('styles')
    
@endpush

@push('scripts')
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
        
    <script>
        $(function () {  
            $("#modal-actions").on('shown.bs.modal', function (e) {
                $("#modal-actions").attr('data-response', '');
                $('.js-modal-title').text($(e.relatedTarget).attr('data-title'));                
                $('.js-modal-body').html('');
                addModalLoader();
                
                var actionUrl = $(e.relatedTarget).attr('data-url');           

                $.get(actionUrl)
                    .done(function(data) {        
                        $('.js-modal-body').html(data);
                        $('.js-modal-body .cke-textarea').each(function(){
                           if($(this).attr('id'))
                               CKEDITOR.replace( $(this).attr('id'),{removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Unlink,Cut,Copy,Undo,Redo,RemoveFormat,Outdent,Indent,Blockquote,About,SpecialChar,PageBreak,Table,Scayt'});
                        });
                        $('.modal-loader').removeClass('on');
                        $('.js-modal-submite').prop('disabled', false);                          
                    })
                    .fail(function(error) { 
                        addErrorMessage('{{ __('Could not load form') }}');                        
                        $('.modal-loader').removeClass('on');
                    });
            });
            
            $("#modal-actions").on('hidden.bs.modal', function (e) {
                $('.js-modal-title').text('');                
                $('.js-modal-body').html('');
            });
            
            $('#modal-actions').on('hidden.bs.modal', function(){
                $('.modal-backdrop').remove();
            })

            $(document).on('click', '.js-modal-submite', function(e){
                e.preventDefault();
                
                var form = $('.modal-body').find('form');
                
                if(!checkReqireFields(form)) return false;
                
                addModalLoader();
                
                $('.js-modal-body .cke-textarea').each(function(){
                    if($(this).attr('id')){
                        var idEditor = $(this).attr('id');
                        $(this).val(CKEDITOR.instances[idEditor].getData());
                    }
                });
                
                $.post(form.attr('action'),  form.serialize())
                    .done(function(data) {  
                        $("#modal-actions").attr('data-response', JSON.stringify(data));
                        $('.modal-loader').removeClass('on');
                        $("#modal-actions").modal('hide');                                                
                    })
                    .fail(function(error) {                                       
                        if(typeof error.responseJSON.error == 'object' && Object.values(error.responseJSON.error).length >= 1){
                            $.each(error.responseJSON.error, function(key, val){
                                addErrorMessage(val);                            
                            });
                        }else{
                            addErrorMessage('{{ __('Error saving form') }}');                            
                        }
                        $('.js-modal-submite').prop('disabled', false);
                        $('.modal-loader').removeClass('on');
                    });
            });
            
            function addErrorMessage(message){
                $('.js-modal-notifi').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> {{ __('Error') }}!</h4>'+message+'</div>');
            }
            
            function addModalLoader(){
                $('.js-modal-submite').prop('disabled', true);
                $('.js-modal-notifi').html('');
                $('.modal-loader').addClass('on');
            }
            
        });        
    </script>
@endpush
