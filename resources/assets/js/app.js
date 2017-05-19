/**
 * First we will load all of this project's JavaScript dependencies wheventich
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { Dropdown } from 'uiv';


Vue.component('confirmation-start-modal', require('./components/lesson/ConfirmationStartModal'));


window.app = new Vue({
    el: '#app',
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
        showConfirmationClassModal: function () {
            console.log('Vai Emitir');
            this.$emit('app:start-confirmation-modal');
        }
    }
});