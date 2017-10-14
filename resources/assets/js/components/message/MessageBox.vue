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
			this.$parent.$on('new-message', (message) => {
				this.updateCurrentThread(message);
			});
		},
		methods: {
			getThreadMessages: function() {
				MessageProvider.getMessages(this.thread.id)
							   .then((response) => {
							   		this.messages = response.data;
							   		this.setListOnBottom();
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
								   		this.setListOnBottom();
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
			},
			updateCurrentThread(message) {
				if (this.thread && message.thread_id == this.thread.id) {
					this.messages.push(message);
					this.setListOnBottom();
					MessageProvider.readThread(this.thread.id)
							   	   .then()
							   	   .catch();
				}
			},
			setListOnBottom: function() {
				setTimeout(function(){
					var objDiv = document.getElementById("list-messages-box");
					objDiv.scrollTop = objDiv.scrollHeight;
				},500);
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
					<div class="list-messages-box margin-bottom-10" id="list-messages-box">
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