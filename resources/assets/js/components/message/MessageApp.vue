<script>
	Vue.component('thread-list', require('../../components/message/ThreadList'));
	Vue.component('inbox-messages', require('../../components/message/MessageBox'));
	export default {
		data() {
			return {
				currentThread: null
			}
		},
		mounted() {
			window.Echo.private(`chat-room.${window.Laravel.user.id}`)
				       .listen('NewMessage', (event) => {
				       		this.$emit('new-message', event.message);
						});
		},
		methods: {
			setThread: function(thread) {	
				this.currentThread = thread;
				this.currentThread.is_read = true;
				this.$emit('thread-selected', this.currentThread);
			}
		}
	}
</script>
<template>
	<div>
		<div class="row">
			<div class="col-xs-12 text-center">
				<span class="fa-stack fa-lg">
  					<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
  					<i class="fa fa-envelope-o fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{$t('messages.title')}}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-10 col-md-offset-1">
				<div class="panel panel-primary" id="panel-inbox-messages">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12 col-md-4">
								<div class="trhreads-title">
									<strong>{{$t('messages.threads')}}</strong>
								</div>
							</div>
							<div class="col-xs-12 col-md-8">
								<div class="messages-title">
									<strong>{{$t('messages.title')}}</strong>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12 col-md-4">
								<thread-list v-on:thread-selected="setThread">
								</thread-list>
							</div>
							<div class="col-xs-12 col-md-8">
								<inbox-messages>
								</inbox-messages>
							</div>
						</div>			
					</div>
				</div>
			</div>
		</div>
	</div>
</template>