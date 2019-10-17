<template>
    <div class="input-group">
        <input
                id="btn-input"
                type="text"
                name="message"
                class="form-control"
                placeholder="Type your message here..."
                v-model="newMessage"
                @keyup.enter="sendMessage"
                @keyup="sendTypingEvent">

        <span class="input-group-btn">
            <button type="submit" class="btn btn-primary btn-flat" id="btn-chat" @click="sendMessage" >Send</button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendTypingEvent() {
                Echo.join('chat')
                    .whisper('typing', this.user);
            },

            sendMessage() {
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage
                });

                this.newMessage = ''
            }
        }
    }
</script>