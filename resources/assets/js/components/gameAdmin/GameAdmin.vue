<script>
	import { GameProvider } from '../../providers/gameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';

	Vue.component('delete-game-modal', require('./ConfirmationDeleteGame'));

	export default {
		props: ['id'],
		data () {
			return {
				game: {
					photo: "",
					name: "",
					description: "",
					developer_site: "",
					status: ""
				},
				errors: {},
				photo: null	
			}
		},
		created () {
			if (this.id) {
				this.getGame();
			}
		},
		methods: {
			submit: function(evt) {
				evt.preventDefault();
				window.app.isLoading = true;
				window.app.$emit('app:close-alert');
				this.errors = false;
				if (this.id) {
					this.update();
				} else {
					this.create();
				}
			},
			create: function () {
				GameProvider.create(this.game, this.photo)
							.then((response) => {
								this.id = response.data.id;
								window.app.isLoading = false;
								var locale = this.$i18n.locale;
								var msg = this.$i18n.messages[locale].game.create_success;
								window.app.$emit('app:show-alert', [msg], "success");
								window.scrollTo(0,0);
								setTimeout(function(){
									window.location = "/app/admin/games";
								},5000);

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
			update: function (){
				GameProvider.update(this.id, this.game)
							.then((response) => {
								window.app.isLoading = false;
								window.app.$emit('app:show-alert', [response.data.msg], "success");
								window.scrollTo(0,0);
								this.getGame();
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
			deleteGame: function () {
				app.$emit('app:delete-game-modal' , this.id, true);
			},
			getGame: function () {
				GameProvider.getAdmin(this.id)
							.then((response) => {
								this.game = response.data;
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
			showPhotoSelection: function (event) {
				var inputFile = document.getElementById('photo');
				inputFile.click();
			},
			uploadPhoto: function(evt) {
				var input = evt.target;
				if (this.id) {
					this.doUpload(input);
				} else {
					this.readPhotoAsUrl(input);
				}	
			},
			readPhotoAsUrl: function(input) {
				if (input.files && input.files[0]) {
					this.photo = input.files[0];
			        var reader = new FileReader();
			        reader.onload = (e) => {
			        	console.log(e.target.result);
			        	this.game.photo = e.target.result;
			        	console.log(this.game);
			        }
			        reader.readAsDataURL(input.files[0]);
			    }
			},
			doUpload: function(input) {
				if (input.files.length == 0 ) {
					return;
				}
				window.app.isLoading = true;
				window.app.$emit('app:close-alert');

				GameProvider.updatePhoto(this.id, input.files[0])
							.then((response) => {
								window.app.isLoading = false;
								this.game.photo = response.data.url;
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
			}	
		}
	}
</script>
<template>
	<div>
		<delete-game-modal>
		</delete-game-modal>
		<div class="row">
			<div class="col-xs-12 col-md-6 margin-bottom-10">
                <div class="img-game-cover-content" v-bind:class="{'hide': game.photo == ''}">
                	<img :src="game.photo" :title="$t('game.cover_image')" :alt="$t('game.cover_image')">
                </div>
                <div class="form-group margin-top-10" v-bind:class="{'has-error' : errors.photo}">
                    <button id="photoSelect" v-on:click="showPhotoSelection" class="btn btn-primary"><i class="glyphicon glyphicon-picture"></i> {{$t('game.cover_image')}}</button>
                    <input type="file" id="photo" name="photo_profile" class="form-control hidden" v-on:change="uploadPhoto">
                    <span v-if="errors.photo" class="help-block">
                        <strong>{{ errors.photo[0] }}</strong>
                    </span>
                </div>
            </div>	
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6 control-group margin-top-10" v-bind:class="{'has-error':errors.name}">
				<label for="name">{{$t('game.title')}}*</label>
				<input type="text" class='form-control' name="name" v-model="game.name" id="name">
				<span v-if="errors.name" class="help-block">
					<strong>{{errors.name[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6 control-group margin-top-10" v-bind:class="{'has-error':errors.description}">
				<label for="description">{{$t('game.description')}}*</label>
				<textarea class='form-control' name="description" v-model="game.description" id="description"></textarea>
				<span v-if="errors.description" class="help-block">
					<strong>{{errors.description[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6 control-group margin-top-10" v-bind:class="{'has-error':errors.developer_site}">
				<label for="developer_site">{{$t('game.developer_site')}}*</label>
				<div class="input-group">
					<span class="input-group-addon">http(s)://</span>
					<input type="text" class='form-control' name="name" v-model="game.developer_site" id="developer_site">
				</div>
				<span v-if="errors.developer_site" class="help-block">
					<strong>{{errors.developer_site[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6 control-group margin-top-10" v-bind:class="{'has-error':errors.status}">
				<label for="status">{{$t('game.status')}}:</label>
				<label class="radio-inline">
					<input type="radio" value="1" v-model="game.status"> Ativo
				</label>
				<label class="radio-inline">
					<input type="radio" value="0" v-model="game.status"> Inativo
				</label>
				<span v-if="errors.status" class="help-block">
					<strong>{{errors.status[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row margin-top-10">
			<div class="col-xs-12 text-left">
				<button type="button" class="btn btn-primary" v-on:click="submit"><i class="glyphicon glyphicon-floppy-disk"></i> {{$t('buttons.save')}}</button>
				<button v-if="id" type="button" class="btn btn-danger" v-on:click="deleteGame"><i class="glyphicon glyphicon-trash"></i> {{$t('buttons.delete')}}</button>
			</div>
		</div>
	</div>
</template>
