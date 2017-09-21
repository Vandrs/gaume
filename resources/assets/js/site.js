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


Vue.use(VueI18n);

var i18n = new VueI18n({
  locale: 'pt_BR',
  messages: Messages
});

Vue.component('notifications-dropdown', require('./components/notification/NotificationsDropdown'));


window.app = new Vue({
    el: '#app',
    i18n,
    components: { Dropdown },
});