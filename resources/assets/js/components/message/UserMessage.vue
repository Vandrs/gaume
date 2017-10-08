<script>
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { MessageProvider } from '../../providers/messageProvider';
	export default {
		props: ['showMessageBox','recipient'],
		data() {
			return {
				message: null,
			}
		},
		methods: {
			sendMessage: function () {
				window.app.$emit('app:close-alert');
				var data = {
					'message': this.message,
					'recipients': this.recipient
				};
				MessageProvider.createThread(data)
							   .then((response) => {
							   		this.message = '';
							   		window.app.$emit('app:show-alert', [response.data.msg], "success", 3000);
							   })
							   .catch((error) => {
							   		var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
							   		window.app.$emit('app:show-alert', errors, "danger");
							   });
			}
		}
	}
</script>
<template>
	<div>
		<div v-if="showMessageBox">
			<div class="row">
				<div class="col-xs-12">
					<textarea rows="5" class="form-control" v-model="message" :placeholder="$t('app.write_here')"></textarea>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 text-center">
					<button class='btn btn-primary' v-on:click="sendMessage"><i  class="glyphicon glyphicon-envelope"></i> {{$t('buttons.send')}}</button>
				</div>
			</div>
		</div>
	</div>
</template>