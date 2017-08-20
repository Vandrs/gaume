<script>
	import { UserProvider } from '../../providers/userProvider';
	import { LocationProvider } from '../../providers/locationProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import VueMask from 'v-mask'
	Vue.use(VueMask);
	
	export default {
		data() {
			return { 
				user: {
					address: {}
				},
				errors: {},
				defaultError: null,
				location: {
					states: [],
					cities: [],
					neighborhoods: [],
				}
			}
		},
		mounted() {
			this.getUser();
		},
		computed: {
			cpf: function() {
				if (this.user.cpf) {
					var part1 = this.user.cpf.substring(0,3);
					var part2 = this.user.cpf.substring(3,6);
					var part3 = this.user.cpf.substring(6,9);
					var part4 = this.user.cpf.substring(9,11);
					return part1+"."+part2+"."+part3+"-"+part4;
				}
				return null;
			},
			birth_date: function() {
				if (this.user.birth_date) {
					var dateTime =  this.user.birth_date.split(" ");
					return dateTime[0].split("-").reverse().join("/");
				}
				return null; 
			}
		},
		methods: {
			getUser: function () {	
				UserProvider.me().then((userResponse) => {
					this.user = userResponse.data;
				});	
			},
			submit: function (evt) {
				evt.preventDefault();
				this.errors = {};
				window.app.isLoading = true;
				window.app.$emit('app:close-alert');
				UserProvider.updateProfile(this.user)
							.then(response => {
								window.app.isLoading = false;
								window.app.$emit('app:show-alert', [response.data.msg], "success");
								window.scrollTo(0,0);
								this.getUser();
							})
							.catch(error => {
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
			getLocation: function() {
				this.user.address.state = null;
				this.user.address.city = null;
				this.user.address.neighborhood = null;
				this.user.address.street = null;
				this.user.address.number = null;
				this.user.address.complement = null;

				if (this.user.address.zipcode.length >= 9) {
					LocationProvider.get(this.user.address.zipcode)
								    .then((response) => {
								    	var data = response.data;
								    	this.user.address.state = data.state;
								    	this.user.address.city = data.city;
								    	this.user.address.neighborhood = data.neighborhood;
								    })
								    .catch((error) => {
								    	var locale = this.$i18n.locale;
										var msg = this.$i18n.messages[locale].profile.zipcode_not_found;
										this.errors.zipcode = [msg];
								    });
				}
			},
			showPhotoSelection: function (event) {
				var inputFile = document.getElementById('photo');
				inputFile.click();
			},
			uploadPhoto: function (event) {
				var input = event.target;
				if (input.files.length > 0) {
					var file = input.files[0];
					this.errors = {};
					window.app.isLoading = true;
					window.app.$emit('app:close-alert');
					UserProvider.updatePhotoProfile(file).then(response => {
						this.user.photo_profile = response.data.url;
						window.app.isLoading = false;
					}).catch(error => {
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
				}
			}
		}
	}
</script>
<template>
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 text-center">
					<span class="fa-stack fa-lg">
	  					<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
	  					<i class="fa fa-user fa-stack-1x fa-inverse"></i>
					</span>
				</div>
				<div class="col-xs-12 text-center">
					<h1>{{$t('app.profile')}}</h1>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <span class="form-title">{{$t('profile.public_information')}}</span>
                    <span class="help-block">
                        {{$t('profile.public_information_help')}}
                    </span>
                </div>
            </div>

			<div class="row">
	            <div class="col-xs-12 col-md-2 col-md-offset-2 margin-bottom-10">
	                <div class="img-profile-content">
	                	<img v-if="user.photo_profile != ''" :src="user.photo_profile" title="imagem de perfil" alt="imagem de perfil">
	                </div>
	                <div class="form-group" v-bind:class="{'has-error' : errors.image}">
	                    <input type="file" name="photo_profile" id="photo_profile" class="hidden"/>
	                    <button id="photoSelect" v-on:click="showPhotoSelection" class="btn btn-primary"><i class="glyphicon glyphicon-picture"></i> {{$t('profile.profile_image')}}</button>
	                    <input type="file" id="photo" name="photo_profile" class="form-control hidden" v-on:change="uploadPhoto">
	                    <span v-if="errors.image" class="help-block">
                            <strong>{{ errors.image[0] }}</strong>
                        </span>
	                </div>
	            </div>
	            <div class="col-xs-12 col-md-6">
	                <div class="row">
	                    <div class="col-xs-12">
	                        <div class="form-group" v-bind:class="{'has-error' : errors.nickname}" >
	                            <label for="nickname" class="control-label">{{$t('profile.nickname')}}*</label>
	                            <input id="nickname" type="text" class="form-control" v-model="user.nickname" name="nickname">
                                <span v-if="errors.nickname" class="help-block">
                                    <strong>{{ errors.nickname[0] }}</strong>
                                </span>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-xs-12">
	                    	<div class="form-group">
	                        	<label for="name" class="control-label">{{$t('profile.name')}}</label>
	                        	<input id="name" type="text" class="form-control" v-model="user.name" name="name" :disabled="true">
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>

	        <div class='row'>
                <div class="col-xs-12 col-md-8 col-md-offset-2 margin-top-10">
                    <span class="form-title">{{$t('profile.private_information')}}</span>
                    <span class="help-block">
                        {{$t('profile.private_information_help')}}
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-4 col-md-offset-2">
                	<div class="form-group">
                        <label for="cpf" class="control-label">{{$t('profile.cpf')}}</label>
                        <input id="cpf" type="text" class="form-control" name="cpf" :value="cpf"  :disabled="true">
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                	<div class="form-group">
                    	<label for="birth_date_text" class="control-label">{{$t('profile.birth_date')}}</label>
                    	<input id="birth_date" type="text" class="form-control" name="birth_date" :value="birth_date" :disabled="true">
                    </div>
                </div>
            </div>

            <div class="row">    
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="form-group" v-bind:class="{'has-error' : errors.email}">
                        <label for="email" class="control-label">{{$t('profile.email')}}*</label>
                        <input id="email" type="email" class="form-control" name="email" v-model="user.email">
                        <span v-if="errors.email" class="help-block">
	                        <strong>{{ errors.email[0] }}</strong>
	                    </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <span class="help-block">
                        {{$t('profile.address')}}
                    </span>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12 col-md-3 col-md-offset-2">
                    <div class="form-group" v-bind:class="{'has-error' : errors.zipcode}">
                        <label for="zipcode" class="control-label">{{$t('profile.zipcode')}}*</label>
                        <input type="text" id="zipcode" name="zipcode" class="form-control" v-model="user.address.zipcode" v-on:change="getLocation" v-mask="'#####-###'">
						<span v-if="errors.zipcode" class="help-block">
	                        <strong>{{ errors.zipcode[0] }}</strong>
	                    </span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="form-group" v-bind:class="{'has-error' : errors.state}">
                        <label for="state" class="control-label">{{$t('profile.state')}}*</label>
                        <input type="text" id="state" name="state" class="form-control" v-model="user.address.state" readonly="">
						<span v-if="errors.state" class="help-block">
	                        <strong>{{ errors.state[0] }}</strong>
	                    </span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="form-group" v-bind:class="{'has-error' : errors.city}">
                        <label for="city" class="control-label">{{$t('profile.city')}}*</label>
                        <input type="text" id="city" name="city" class="form-control" v-model="user.address.city" readonly="">
  						<span v-if="errors.city" class="help-block">
	                        <strong>{{ errors.city[0] }}</strong>
	                    </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-4 col-md-offset-2">
                    <div class="form-group" v-bind:class="{'has-error' : errors.neighborhood}">
                        <label for="neighborhood" class="control-label">{{$t('profile.neighborhood')}}*</label>
                        <input type="text" id="neighborhood" name="neighborhood" class="form-control" v-model="user.address.neighborhood" readonly="">
  						<span v-if="errors.neighborhood" class="help-block">
	                        <strong>{{ errors.neighborhood[0] }}</strong>
	                    </span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="form-group" v-bind:class="{'has-error' : errors.street}">
                        <label for="street" class="control-label">{{$t('profile.street')}}*</label>
                        <input id="street" type="text" name="street" v-model="user.address.street" class="form-control">
                        <span v-if="errors.street" class="help-block">
	                        <strong>{{ errors.street[0] }}</strong>
	                    </span>
                    </div>
                </div>
            </div>
            <div class="row">                        
                <div class="col-xs-12 col-md-3 col-md-offset-2">
                    <div class="form-group" v-bind:class="{'has-error' : errors.number}">
                        <label for="number" class="control-label">{{$t('profile.number')}}</label>
                        <input id="number" type="text" name="number" v-model="user.address.number" class="form-control">
                        <span v-if="errors.number" class="help-block">
	                        <strong>{{ errors.number[0] }}</strong>
	                    </span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <label for="complement" class="control-label">{{$t('profile.complement')}}</label>
                        <input id="complement" type="text" name="complement" v-model="user.address.complement" class="form-control">
                    </div>
                </div>
            </div>

            <div v-if="user.bankAccount">
	            <div class="row margin-top-10">
	                <div class="col-xs-12 col-md-8 col-md-offset-2">
	                    <span class="form-title">{{$t('profile.bank_info')}}</span>
	                    <span class="help-block">
	                        {{$t('profile.bank_info_help')}}
	                    </span>
	                </div>
	            </div>

	            <div class="row">
	                <div class="col-xs-12 col-md-4 col-md-offset-2" >
	                    <div class="form-group" v-bind:class="{'has-error' : errors.bank}">
	                        <label for="bank">{{$t('profile.bank')}}*</label>
	                        <input type="text" name="bank" id="bank" class="form-control" v-model="user.bankAccount.bank" maxlength="100">
                            <span class="help-block" v-if="errors.bank">
                                <strong>{{ errors.bank[0] }}</strong>
                            </span>
	                    </div>
	                </div>
	                <div class="col-xs-12 col-md-4" >
	                    <div class="form-group" v-bind:class="{'has-error' : errors.agency}">
	                        <label for="bank">{{$t('profile.agency')}}*</label>
	                        <input type="text" name="agency" id="agency" class="form-control" v-model="user.bankAccount.agency" maxlength="20">
                            <span class="help-block" v-if="errors.agency">
                                <strong>{{ errors.agency[0] }}</strong>
                            </span>
	                    </div>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-xs-12 col-md-4 col-md-offset-2">
	                    <div class="form-group" v-bind:class="{'has-error' : errors.account}">
	                        <label for="account">{{$t('profile.account')}}*</label>
	                        <input type="text" name="account" id="account" class="form-control" v-model="user.bankAccount.account" maxlength="20">
	                        <span class="help-block" v-if="errors.account">
	                            <strong>{{ errors.account[0] }}</strong>
	                        </span>
	                    </div>
	                </div>
	                <div class="col-xs-12 col-md-2" >
	                    <div class="form-group" v-bind:class="{'has-error' : errors.digit}">
	                        <label for="digit">{{$t('profile.digit')}}*</label>
	                        <input type="text" name="digit" id="digit" class="form-control" v-model="user.bankAccount.digit" maxlength="2">
                            <span class="help-block" v-if="errors.digit">
                                <strong>{{ errors.digit[0] }}</strong>
                            </span>
	                    </div>
	                </div>
	            </div>
            </div>

            <div class="row margin-top-10">
            	<div class="col-xs-12 col-md-8 col-md-offset-2">
            		<button type="button" id="submit" v-on:click="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> {{$t('buttons.save')}}</button>
            	</div>
            </div>
        </div>
	</div>
</template>