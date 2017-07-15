<script>
	import { GameProvider } from '../../providers/gameProvider';
	import { PreRegistrationProvider } from '../../providers/preRegistrationProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import * as moment from 'moment';
	export default {
		props: ['id'],
		data() {
			return {
				registration : {
					id: null,
					name: null,
					email: null,
					mailed_at: null,
					gamePlatforms: []
				},
				gamePlatforms : [],
				errors: {},
				listLink: '/app/admin/usuarios/professor/pre-cadastro/lista'
			}
		},
		mounted() {
			this.getGames();
			if (this.id) {
				this.getPreRegistration();
			}
		},
		computed: {
			mailed_at: function() {
				if (this.registration.mailed_at) {
					var date = moment(this.registration.mailed_at);
					return date.format('D/M/YYYY HH:mm');
				} else {
					return '__/__/__ __:__';
				}			
			}
		},
		methods: {
			getGames: function () {
				GameProvider.listAdminAvailables()
							.then((response) => {
								this.gamePlatforms = response.data;
							})
						    .catch((error) => {
								var response = error.response;
								var errors = AppErrorBag.parseErrors(
								  				response.status,
								  				response.data
								  			);
							  	window.app.$emit('app:show-alert', errors, "danger");
							  	window.scrollTo(0,0);
						    });			
			},
			getPreRegistration: function () {
				PreRegistrationProvider.get(this.id)
									   .then((response) => {
									   		this.registration = response.data;
									   })
									   .catch((error) => {
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
			},
			submit: function (event) {
				event.preventDefault();
				if (this.id) {
					this.update();
				} else {
					this.create();
				}
			},
			create: function() {
				this.errors = {};
				window.app.isLoading = true;
				window.app.$emit('app:close-alert');
				PreRegistrationProvider.create(this.registration)
									   .then((response) => {
									   		window.app.isLoading = false;
									   		var locale = this.$i18n.locale;
											var msg = this.$i18n.messages[locale].pre_registration.create_success;
											window.app.$emit('app:show-alert', [msg], "success");
											window.scrollTo(0,0);
											setTimeout(() => {
							  					window.location.href = this.listLink;
							  				}, 3000);
									   })
									   .catch((error) => {
											window.app.isLoading = false;
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
			},
			update: function() {
				this.errors = {};
				window.app.isLoading = true;
				window.app.$emit('app:close-alert');
				PreRegistrationProvider.update(this.registration, this.id)
									   .then((response) => {
									   		window.app.isLoading = false;
									   		var locale = this.$i18n.locale;
											var msg = this.$i18n.messages[locale].pre_registration.update_success;
											window.app.$emit('app:show-alert', [msg], "success");
											window.scrollTo(0,0);
											this.getPreRegistration();
									   })
									   .catch((error) => {
											window.app.isLoading = false;
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
			},
			sendRegistrationEmail: function () {
				this.errors = {};
				window.app.isLoading = true;
				window.app.$emit('app:close-alert');
				PreRegistrationProvider.sendEmail(this.id)
									   .then((response) => {
									   		window.app.isLoading = false;
									   		window.app.$emit('app:show-alert', [response.data.msg], "success");
											window.scrollTo(0,0);
									   })
									   .catch((error) => {
									   		window.app.isLoading = false;
									   		var response = error.response;
									   		var errors = AppErrorBag.parseErrors(
												  				response.status,
												  				response.data
												  			);
											window.app.$emit('app:show-alert', errors, "danger");
											window.scrollTo(0,0);
									   });
			}
		}
	}
</script>
<template>
<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="row">	
			<div class="col-xs-12 margin-top-10 control-group" v-bind:class="{'has-error':errors.name}">
				<label>{{$t('profile.name')}}</label>
				<input type="text" class="form-control" name="name" id="name" v-model="registration.name">
				<span v-if="errors.name" class="help-block">
					<strong>{{errors.name[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 margin-top-10 control-group" v-bind:class="{'has-error':errors.email}">
				<label>{{$t('profile.email')}}</label>
				<input type="text" class="form-control" name="email" id="email" v-model="registration.email">
				<span v-if="errors.email" class="help-block">
					<strong>{{errors.email[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row" v-if="id">
			<div class="col-xs-12 margin-top-10 control-group" v-bind:class="">
				<label>{{$t('pre_registration.mailed_at')}}:</label> {{mailed_at}} 
				<button v-if="id" type="button" v-on:click="sendRegistrationEmail" class="btn btn-success"><i class="glyphicon glyphicon-envelope"></i> {{$t('pre_registration.resend_email')}}</button>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 margin-top-10 control-group" v-bind:class="{'has-error':errors.gamePlatforms}">
				<label>{{$t('app.games')}}</label>
				<span v-if="errors.gamePlatforms" class="help-block">
					<strong>{{errors.gamePlatforms[0]}}</strong>
				</span>
				<div v-for="game of gamePlatforms">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<label>{{game.name}}</label>		
						</div>
						<div class="panel-body">
							<label class="checkbox-inline" v-for="platform of game.platforms">
								<input type="checkbox" :value="platform.id" v-model="registration.gamePlatforms"> {{platform.name}}
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<button type="button" class="btn btn-primary" v-on:click="submit"><i class="glyphicon glyphicon-floppy-disk"></i> {{$t('buttons.save')}}</button>
			</div>
		</div>
	</div>
</div>
</template>