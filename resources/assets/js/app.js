/**
 * First we will load all of this project's JavaScript dependencies wheventich
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');

import { Messages } from './lang/messages';
import { Dropdown } from 'uiv';
import VueI18n from 'vue-i18n';
import { AppPushNotifications } from './components/app/AppPushNotifications';
import Autocomplete from 'v-autocomplete';

AppPushNotifications.registerServiceWorker();

Vue.use(VueI18n);

var i18n = new VueI18n({
  locale: 'pt_BR',
  messages: Messages
});

Vue.use(Autocomplete);

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
Vue.component('game-list', require('./components/game/GameList'));

window.app = new Vue({
    i18n,
    components: { Dropdown },
    data: {
        isLoading: false
    }, 
    methods: {
        showConfirmationClassModal: function (teacherId) {
            this.$emit('app:start-confirmation-modal', teacherId);
        }
    }
}).$mount("#app");