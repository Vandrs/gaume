<script>
	import { UserProvider } from '../../providers/userProvider';
	import { StateProvider } from '../../providers/stateProvider';
	import { CityProvider } from '../../providers/cityProvider';
	import { NeighborhoodProvider } from '../../providers/neighborhoodProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import AutocompleteTemplate from '../shared/AutocompleteTemplate'
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
				},
				selecteds: {
					state_id: null,
					city: null,
					neighborhood: null
				},
				itemTemplate: AutocompleteTemplate
			}
		},
		mounted() {
			this.getUser();
			this.getStates();
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
					setTimeout(() => {
						this.selecteds.state_id = this.user.address.state.id;
						this.selecteds.city = this.user.address.city;
						this.selecteds.neighborhood = this.user.address.neighborhood;
					}, 500);

				});	
			},
			getStates: function () {
				StateProvider.list().then((response) => {
					this.location.states = response.data;	
				});
			},
			getCities: function (text) {
				CityProvider.list(this.user.address.state.uf, {"q":text})
						    .then((response) => {
								this.location.cities = response.data;
							});
			},
			getNeighborhoods: function (text) {
				NeighborhoodProvider.list(this.user.address.state.uf, {"q":text})
									.then((response) => {
										this.location.neighborhoods = response.data;
									});
			},
			getLocationLabel: function (item) {
				return item.name;
			},
 			setSelectedState: function (ev) {
				for (var state of this.location.states) {
					if (state.id == ev.target.value) {
						this.user.address.state = state;
						break;
					}
				}
				this.selecteds.city = {};
				this.selecteds.neighborhood = {};
			},
			cityHasUpdated: function (item){
				if (this.user.address.city && item && (item.id != this.user.address.city.id)) {
					this.selecteds.neighborhood = {};
				} else if (!item) {
					this.selecteds.neighborhood = {};
				}
			},
			submit: function (evt) {
				evt.preventDefault();
				this.errors = {};
				window.app.isLoading = true;
				window.app.$emit('app:close-alert');
				var data = this.buildRequestData();
				UserProvider.updateProfile(data)
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
			buildRequestData: function () {
				return {
					'nickname' : this.user.nickname, 
					'email' : this.user.email,
					'state' : (this.user.address ? this.user.address.state.id : null),  
					'city' : (this.selecteds.city ? this.selecteds.city.id : null), 
					'neighborhood' : (this.selecteds.neighborhood ? this.selecteds.neighborhood.id : null),
					'street' : (this.user.address ? this.user.address.street : null),
					'number' : (this.user.address ? this.user.address.number : null),
					'complement' : (this.user.address ? this.user.address.complement : null) 
				}
			}
		}
	}
</script>
<template>
	<div class="row">
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
	                <div>
	                    <input type="file" name="photo_profile" id="photo_profile" class="hidden"/>
	                    <button id="photoSelect" class="btn btn-primary"><i class="glyphicon glyphicon-picture"></i> {{$t('profile.profile_image')}}</button>
	                </div>
	                <div class="form-group">
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
                    <div class="form-group" v-bind:class="{'has-error' : errors.state}">
                        <label for="state" class="control-label">{{$t('profile.state')}}*</label>
                        <select id="state" class="form-control" name="state" v-model="selecteds.state_id" v-on:change="setSelectedState">
						  	<option v-for="state in location.states" :value="state.id">
						    	{{ state.name }}
						  	</option>
						</select>
						<span v-if="errors.state" class="help-block">
	                        <strong>{{ errors.state[0] }}</strong>
	                    </span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group" v-bind:class="{'has-error' : errors.city}">
                        <label for="city_name" class="control-label">{{$t('profile.city')}}*</label>
                        <v-autocomplete :items="location.cities" v-model="selecteds.city" :component-item="itemTemplate" :get-label="getLocationLabel" v-on:update-items="getCities" v-on:input="cityHasUpdated">
  						</v-autocomplete>
  						<span v-if="errors.city" class="help-block">
	                        <strong>{{ errors.city[0] }}</strong>
	                    </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-4 col-md-offset-2">
                    <div class="form-group" v-bind:class="{'has-error' : errors.neighborhood}">
                        <label for="neighborhood_name" class="control-label">{{$t('profile.neighborhood')}}*</label>
                        <v-autocomplete :items="location.neighborhoods" v-model="selecteds.neighborhood" :component-item="itemTemplate" :get-label="getLocationLabel" v-on:update-items="getNeighborhoods">
  						</v-autocomplete>
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
            <div class="row">
            	<div class="col-xs-12 col-md-8 col-md-offset-2">
            		<button type="button" id="submit" v-on:click="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> {{$t('buttons.save')}}</button>
            	</div>
            </div>
        </div>
	</div>
</template>