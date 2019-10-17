<div class="modal fade" id="chatModal">
    <div class="modal-dialog chat-modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body">                
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border padding-b-0 chat-modal-header">
                        <h3 class="box-title">Urich {{ __('Chat') }}</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool btn-collapsed-box-collapse jq_chat-collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                                <i class="fa fa-comments"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        <div class="row margin-5">
                            <div class="chat-ava-row" v-for="user in users">
                                <img class="direct-chat-img" :src="user.avatar" :alt="user.shortName" :title="user.shortName">
                                <span v-if="user.typing" class="badge badge-primary">typing...</span>
                            </div>
                        </div>                
                    </div>

                    <div class="box-body">
                        <div class="direct-chat-messages"  id="messageDisplay">
                            <infinite-loading direction="top" @distance="1" @infinite="infiniteHandler"></infinite-loading>
                            
                            <chat-messages :messages="messages"></chat-messages>
                        </div>
                        
                        <div class="direct-chat-contacts">
                            <ul class="contacts-list">
                                <li v-for="user in users">
                                    <a href="#">
                                        <img class="direct-chat-img" :src="user.avatar" :alt="user.shortName" :title="user.shortName">
                                        <div class="contacts-list-info">
                                            <span class="contacts-list-name">
                                                @{{ user.fullName }}                                         
                                            </span>
                                            <span class="contacts-list-msg">@{{ user.email }}</span>
                                        </div>                                
                                    </a>
                                </li>                        
                            </ul>
                        </div>
                    </div>

                    <div class="box-footer">
                        <chat-form @messagesent="addMessage" :user="{{ auth()->user() }}" ></chat-form>                                
                    </div>            
                </div>
            </div>
                        
        </div>
    </div>
</div>

@push('styles')
    
@endpush

@push('scripts')            
    <script>
        $(function () {  
            $("#chatModal").on('show.bs.modal', function (e) {                                
                $('#chatModal .modal-dialog').css({
                    top: 0,
                    right: 0
                });                
          });
            
            $("#chatModal").on('shown.bs.modal', function (e) {                
                $('.modal-backdrop').remove();
                
                $('#chatModal .modal-dialog').draggable({
                    handle: ".chat-modal-header"
                });
                
                scrollMessages();
            });
            $(document).on('click', '.jq_chat-collapse', function (e) {                   
                setTimeout(function(){
                    scrollMessages();
                }, 500);
            });     
            
            function scrollMessages(){
                $('#messageDisplay').scrollTop($('#messageDisplay')[0].scrollHeight);
            }
        });        
    </script>
@endpush
