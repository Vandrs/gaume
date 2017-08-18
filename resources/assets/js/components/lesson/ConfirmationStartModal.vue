<script> 
	import { Modal } from 'uiv';
	import { LessonProvider } from '../../providers/lessonProvider';
	import { TeacherGameProvider } from '../../providers/teacherGameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default  {
		components: { Modal },
		data () {
			return {
				showModal: false,
				gameId: null,
				teacherId: null,
				selectedGame: null,
				selectedPlatform: null,
				games: [],
				platforms: [],
				errors: [],
				disableConfirm: false,
				urlLogo: window.Laravel.baseUrl + "/images/logo.png"
			}
		},
		created () {
			var self = this;
			setTimeout(function(){
				window.app.$on('app:start-confirmation-modal', function (teacherId, gameId) {
					self.gameId = gameId;
					self.teacherId = teacherId;
					self.toggleModal();
				});
			},1000);
		},
		methods: {
			dismissCallback: function(msg) {
				this.clean();
			},
			toggleModal: function() {
				if (!this.showModal) {
					window.app.isLoading = true;
					TeacherGameProvider.getLessonGames(this.teacherId)
									   .then((response) => {
									   		window.app.isLoading = false;
									   		this.games = response.data;

									   		if (this.gameId) {
									   			this.selectedGame = this.gameId;
									   			this.showPlatformOptions();
									   		}

									   		this.showModal = true;
									   })
									   .catch((error) => {
									   		window.app.isLoading = false;
								  			var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
								  			window.app.$emit('app:show-alert', errors, "danger");
									   });
				} else {
					this.showModal = false;
					this.clean();					
				}
			},
			clean: function () {
				this.games = [];
				this.platforms = [];
				this.selectedGame = null;
				this.selectedPlatform = null;
				this.teacherId = null;
				this.errors = [];
				self.gameId = null;
			},
			showPlatformOptions: function() {
				for (var game of this.games) {
					if (game.id == this.selectedGame) {
						this.platforms = game.platforms;
					}
				}
			},
			cancelModal: function() {
				this.clean();
				this.showModal = false;
			},
			confirmModal: function() {
				window.app.isLoading = true;
				this.disableConfirm = true;
				var data = {
					teacher_id: this.teacherId,
					game_id: this.selectedGame,
					platform_id: this.selectedPlatform
				};
				LessonProvider.create(data)
							  .then((response) => {
							  		window.app.isLoading = false;
							  		this.disableConfirm = false;
							  		window.location.href = window.Laravel.baseUrl+"/app/aula/"+response.data.id;
							  })
							  .catch((error) => {
							  		window.app.isLoading = false;
							  		var errors = AppErrorBag.parseErrors(
							  				error.response.status,
							  				error.response.data
							  			);
							  		this.errors = errors;
							  		this.disableConfirm = false;
							  });
			}
		}
	}
</script>
<template>
	<div>
		<modal  v-model="showModal" :header="false" v-on:hide="dismissCallback" :footer="false" >
			<div slot="default">
				<div class="row">
					<div class="col-xs-12 text-center">
						<img class="modal-logo" :src="urlLogo" title="Logo Monzy" alt="Logo Monzy">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 text-center">
						<h2>{{$t('modal.warning')}}</h2>
					</div>
				</div>
				<div v-if="errors.length" class="row margin-top-10">
					<div class="col-xs-12">
						<div class="alert alert-danger">		
							<ul class="alert-list">
								<li v-for="error of errors">{{error}}</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-xs-12">
						{{$t('teacher_game.select_game_platform')}}
					</div>
				</div>
				<div v-if="games.length" class="row margin-top-10">
					<div class="col-xs-12">
						<label for="lesson_game">{{$t('app.game')}}</label>
						<select class="form-control" id="lesson_game" name="lesson_game" v-model="selectedGame" v-on:change="showPlatformOptions">
							<option value="">{{$t('app.select')}}</option>
							<option v-for="game of games" :value="game.id">{{game.game}}</option>
						</select>
					</div>
				</div>
				<div v-else class="row">
					<div class="col-xs-12">
						{{$t('teacher_game.no_game_available')}}
					</div>
				</div>
				<div v-if="selectedGame" class="row margin-top-10">
					<div class="col-xs-12">
						<label>{{$t('game.platform')}}</label>
					</div>
					<div class="col-xs-12">
						<label v-for="platform of platforms" class="radio-inline">
							<input type="radio" name="platform" v-model="selectedPlatform" :value="platform.id">{{platform.platform}}
						</label>
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-xs-12 text-right">
						<button type="button" class="btn btn-default" v-on:click="cancelModal">{{$t('modal.cancel2Text')}}</button>
						<button type="button" class="btn btn-primary" v-bind:class="{disabled:disableConfirm}" v-on:click="confirmModal">{{$t('modal.confirmText')}}</button>
					</div>
				</div>
			</div>
		</modal>
	</div>
</template>