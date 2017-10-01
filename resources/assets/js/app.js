/**
 * First we will load all of this project's JavaScript dependencies wheventich
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Vue = require('vue');

import { Messages } from './lang/messages';
import { Dropdown } from 'uiv';
import VueI18n from 'vue-i18n';
import VueTimeago from 'vue-timeago'
import { AppPushNotifications } from './components/app/AppPushNotifications';
import Autocomplete from 'v-autocomplete';
import { VueMaskDirective } from 'v-mask';
import { UserProvider } from './providers/userProvider';


AppPushNotifications.registerServiceWorker();

Vue.use(VueI18n);
Vue.directive('mask', VueMaskDirective);

var i18n = new VueI18n({
  locale: 'pt_BR',
  messages: Messages
});

Vue.use(Autocomplete);

Vue.use(VueTimeago, {
  name: 'timeago',
  locale: 'pt-BR',
  locales: {
    'pt-BR': require('vue-timeago/locales/pt-BR.json')
  }
})


Vue.component('confirmation-start-modal', require('./components/lesson/ConfirmationStartModal'));
Vue.component('app-alert', require('./components/app/AppAlert'));
Vue.component('lesson', require('./components/lesson/Lesson'));
Vue.component('lesson-list', require('./components/lesson/ListLessons'));
Vue.component('teacher-list', require('./components/teacher/TeacherList'))
Vue.component('profile', require('./components/user/Profile'));
Vue.component('game-admin', require('./components/gameAdmin/GameAdmin'));
Vue.component('game-admin-list', require('./components/gameAdmin/ListGameAdmin'));
Vue.component('teacher-admin-registration', require('./components/user/TeacherRegistration'));
Vue.component('pre-registration-list', require('./components/user/ListTeacherRegistration'));
Vue.component('teacher-game', require('./components/teacherGame/TeacherGame'));
Vue.component('teacher-profile', require('./components/teacher/Teacher'));
Vue.component('game-list', require('./components/game/GameList'));
Vue.component('transaction-list', require('./components/transaction/TransactionList'));
Vue.component('billing-users', require('./components/billing/ListBillingUsers'));
Vue.component('notifications-dropdown', require('./components/notification/NotificationsDropdown'));


window.app = new Vue({
    i18n,
    components: { Dropdown },
    mounted () {
        this.setOnline();
        this.listen();
        document.addEventListener('beforeunload', this.setOffline);
    },
    data: {
        isLoading: false
    }, 
    methods: {
        showConfirmationClassModal: function (teacherId) {
            this.$emit('app:start-confirmation-modal', teacherId);
        },
        listen: function() {
            Echo.join('online-users')
                .joining((user) => {
                    this.$emit('user-online', user);
                })
                .leaving((user) => {
                    this.$emit('user-offline', user);
                    UserProvider.offline(user.id)
                                .then(() => {})
                                .catch(() => {});  
                });
        },
        setOnline : function() {
            UserProvider.online(window.Laravel.user.id)
                        .then(() => {})
                        .catch(() => {});
        },
        setOffline : function () {
            UserProvider.offline(window.Laravel.user.id)
                        .then(() => {})
                        .catch(() => {});
        }
    }
}).$mount("#app");