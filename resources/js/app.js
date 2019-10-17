
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(require('vue-resource'));
Vue.component('InfiniteLoading', require('vue-infinite-loading'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

 const files = require.context('./', true, /\.vue$/i);
 files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',

    data() {
        return {
            messages: [],
            users: [],
            curUser: window.Laravel,
        };
    },

    created() {
        this.fetchMessages();

        Echo.join('chat')            
            .here(users => {
                this.users = users;                
            })
            .joining(user => {                                                   
                if(this.users.findIndex(x => x.id === user.id)){
                    this.users.push(user);
                }                
            })
            .leaving(user => {
                if(this.users.findIndex(x => x.id === user.id)){
                    this.users = this.users.filter(u => u.id !== user.id);
                }                
            })
            .listenForWhisper('typing', ({id, name}) => {
                this.users.forEach((user, index) => {
                    if (user.id === id) {                        
                        user.typing = true;
                        this.$set(this.users, index, user);
                        setTimeout(() => { 
                            user.typing = false;
                            this.$set(this.users, index, user);
                        }, 1000);
                    }
                });
            })
            .listen('MessageSent', (event) => {
                this.messages.push({
                    message: event.message.message,
                    user: event.user
                });
                
                this.scrollToEnd();
                
                this.users.forEach((user, index) => {
                    if (user.id === event.user.id) {
                        user.typing = false;
                        this.$set(this.users, index, user);
                    }
                });
            });
    },
    methods: {
        fetchMessages() {            
            axios.get('/admin/messages').then(response => {
                this.messages = response.data;
                this.messages.curUser = this.curUser;
                this.scrollToEnd();
            });            
        },

        addMessage(message) {                        
            axios.post('/admin/messages', message).then(response => {
                
            });
        },
        scrollToEnd: function() {    	            
            var container = this.$el.querySelector("#messageDisplay");            
            setTimeout(() => { 
                container.scrollTop = container.scrollHeight;            
            }, 100);
        },
        infiniteHandler($state) {
            axios.get('/admin/messages?offset='+this.messages.length).then(response => {
                if (response.data.length) {
                    let listMessages = this;
                    $.each(response.data, function (key, value) {
                        listMessages.messages.unshift(value);
                    });
                    $state.loaded();
                } else {
                    $state.complete();
                }
            });
        },
    }
});
