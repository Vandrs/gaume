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
				this.messages = [];
				if (this.thread) {
					this.getThreadMessages();
				}
			});
		},
		methods: {
			getThreadMessages: function() {
				MessageProvider.getMessages(this.thread.id)
							   .then((response) => {
							   		this.messages = response.data;
							   		MessageProvider.readThread(this.thread.id)
							   					   .then()
							   					   .catch();
							   })
							   .catch((error) => {
							   		var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
							  		window.app.$emit('app:show-alert', errors, "danger");
							  		window.scrollTo(0,0);
							   });
			},
			sendMessage: function(evt) {
				if (this.canSendMessage()) {
					var data = {
						'message': this.message_text,
						'recipients': this.thread.recipients
					};
					MessageProvider.updateThread(this.thread.id, data)
								   .then((response) => {
								   		var message = response.data.message;
								   		this.messages.push(message);
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
						<span v-if="thread">{{$t('messages.talking_to')+": "+thread.contact.text}}</span>
						<span v-else>{{$t('messages.no_thread_selected')}}</span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="list-messages-box margin-bottom-10">
						<div v-for="message of messages" class="media message">
						    <div class="pull-left">
						    	<div class='message-photo'>
						        	<img v-if="message.user.photo" :src="message.user.photo">
									<icon v-else class="glyphicon glyphicon-user"></icon>
								</div>
						    </div>
						    <div class="media-body">
						        <h4 class="media-heading">{{message.user.nickname ? message.user.nickname : message.user.name}}</h4>
						        <p>{{message.message}}</p>
						        <div class="text-muted text-right">
						            <small>{{message.created_at_formated}}</small>
						        </div>
						    </div>
						</div>
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