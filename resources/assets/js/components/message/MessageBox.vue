<script>
	import { MessageProvider } from '../../providers/messageProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default {
		data(){
			return {
				thread: null,	
				messagesShowing: 0,
				messages: [],
				message_text: null,
				last: 'last',
				get_more: 'more',
				messsagesBuffer: 10,
				filter: {
					mode: 'last'
				}
			};
		},
		mounted() {
			this.$parent.$on('thread-selected',(thread) => {
				this.thread = thread;
				if (this.thread) {
					this.getThreadMessages();
				}
			});
		},
		methods: {
			getThreadMessages: function() {
				console.log(this.thread);
			},
			sendMessage: function(evt) {
				if (this.canSendMessage()) {
					var data = {
						'message': this.message_text,
						'recipients': this.thread.recipients
					};
					MessageProvider.updateThread(this.thread.id,data)
								   .then((response) => {
								   		var message = response.data.message;
								   		console.log('OK', message);
								   		this.message_text = null;
								   })
								   .catch((error) => {
								   		var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  		window.app.$emit('app:show-alert', errors, "danger");
								  		window.scrollTo(0,0);
								   });
				}
			},
			canSendMessage: function () {
				if (this.message_text && this.thread) {
					return true;
				}
				return false;
			}

		}
	};
</script>
<template>
	<div class="row">
		<div class="col-xs-12 list-messages-container">
			<div class="row">
				<div class="col-xs-12 margin-bottom-10">
					<div class="list-message-controls">
						<a href="#" v-bind:class="{'selected':filter.mode == last}">{{$t('messages.see_last_only')}}</a> | 
					    <a href="#" v-bind:class="{'selected':filter.mode == get_more}">{{$t('messages.see_last_messages')}}</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="list-messages-box">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="message-box">
						<textarea v-model="message_text" v-on:keyup.enter="sendMessage" :placeholder="$t('messages.placeholder')" class="form-control input-message-box" name="message" rows="5"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>