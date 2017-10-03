<script>
	import { ContactProvider } from '../../providers/contactProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default {
		data() {
			return {
				data: {
					comment: null,
					type: null
				},
				errors: {},
				disableSubmit: false
			}
		},
		mounted() {
			this.setType();
		},
		methods : {
			setType: function () {
				if (Laravel.user.role == 'STUDENT') {
					this.data.type = 4;
				} else if (Laravel.user.role == 'TEACHER') {
					this.data.type = 3;
				}
			},
			submit: function () {
				this.disableSubmit = true;
				window.app.isLoading = true;
				this.errors = {};
				ContactProvider.createContact(this.data)
							   .then((response) => {
							   		this.data.comment = null;
							   		this.disableSubmit = false;
							   		window.app.isLoading = false;
							   		window.app.$emit('app:show-alert', [response.data.msg], "success");
							   })
							   .catch((error) => {
							   		window.app.isLoading = false;
							   		this.disableSubmit = false;
									var response = error.response;
									if (response.status == 400) {
										this.errors = response.data.errors;
										var locale = this.$i18n.locale;
										var msg = this.$i18n.messages[locale].app.defaultErrors;
										window.app.$emit('app:show-alert', [msg], "danger");
										window.scrollTo(0,0);
									} else {
										var errors = AppErrorBag.parseErrors(
										  				response.status,
										  				response.data
										  			);
									  	window.app.$emit('app:show-alert', errors, "danger");
									  	window.scrollTo(0,0);
									}
							   });
			}
		}
	};
</script>
<template>
	<div>
		<div class="row">
			<div class="col-xs-12 text-center">
				<span class="fa-stack fa-lg">
  					<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
  					<i class="fa fa-question-circle fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{$t('faq.contact_message')}}</h1>
			</div>
		</div>
		<form action="#" method="POST">
			<div class='row margin-top-20'>
				<div class="col-xs-12 col-md-8 col-md-offset-2 control-group" v-bind:class="{'has-error':errors.comment}">
					<label for="comment" class="control-label">{{$t('faq.message')}}</label>
					<textarea name="comment" rows="5" id="comment" v-model="data.comment" class="form-control" :placeholder="$t('faq.message_placeholder')"></textarea>
					<span v-if="errors.comment" class="help-block">
						<strong>{{errors.comment[0]}}</strong>
					</span>
				</div>
			</div>
			<div class='row margin-top-20'>
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<button type="button" class="btn btn-primary" v-bind:class="{'disabled':disableSubmit}" v-on:click="submit"><i class="fa fa-envelope"></i> {{$t('buttons.send')}}</button>
				</div>
			</div>
		</form>
	</div>
</template>