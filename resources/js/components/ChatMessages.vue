<template>
    <div>
        <div class="direct-chat-msg" v-for="message in messages" v-bind:class="{ 'right': message.user.id == messages.curUser.user_id }">

            <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-left" v-bind:class="{ 'pull-right': message.user.id == messages.curUser.user_id }">
                    {{ message.user.shortName }}
                </span>
                <span class="direct-chat-timestamp pull-right" v-bind:class="{ 'pull-left': message.user.id == messages.curUser.user_id }">
                    {{ message.created_at }} 
                </span>
            </div>

            <img class="direct-chat-img" :src="message.user.avatar">
            <div class="direct-chat-text">
                {{ message.message }}
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: ['messages']
    };
</script>