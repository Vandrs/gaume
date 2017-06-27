<script> 
	import { Alert } from 'uiv';
	export default  {
		components: { Alert },
		data () {
			return {
				showAlert: false,
				messages: [],
				alertType: 'dander'
			}
		},
		created () {
			self = this;
			setTimeout(function(){
				window.app.$on('app:show-alert',function (messages, type) {
					self.messages = messages;
					self.showAlert = true;
					self.alertType = type ? type : 'danger';
				});
				window.app.$on('app:close-alert', () =>  {
					this.close();
				});
			},1000);
		},
		methods: {
			close: function(msg) {
				this.showAlert = false;		
				this.messages = [];

			}
		}
	}
</script>
<template>
	<div class="row">
		<div class="col-xs-12">
			<alert :type="alertType" :closable="true" v-if="showAlert" v-on:close="close()">
	            <slot>
	            	<ul class="list-alert-messages">
	            		<li v-for="message in messages ">{{message}}</li>
	            	</ul>
	            </slot>
	         </alert>
		</div>
	</div>
</template>
<style scoped="true">
	ul.list-alert-messages {
		list-style: none;
	    padding-left: 0px;
	}
</style>
