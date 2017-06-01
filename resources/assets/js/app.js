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

AppPushNotifications.registerServiceWorker();

Vue.use(VueI18n);

var i18n = new VueI18n({
  locale: 'pt_BR',
  messages: Messages
})

Vue.component('confirmation-start-modal', require('./components/lesson/ConfirmationStartModal'));
Vue.component('app-alert', require('./components/app/AppAlert'));
Vue.component('lesson', require('./components/lesson/Lesson'));
Vue.component('lesson-list', require('./components/lesson/ListLessons'));

window.app = new Vue({
    i18n,
    components: { Dropdown },
    data: {
    	menuToggled : false	
    }, 
    methods: {
    	toggleMenu: function(event) {
    		event.preventDefault();
    		if (this.menuToggled) {
    			this.menuToggled = false;
    		} else {
    			this.menuToggled = true;
    		}
    	},
        showConfirmationClassModal: function (teacherId) {
            this.$emit('app:start-confirmation-modal', teacherId);
        }
    }
}).$mount("#app");