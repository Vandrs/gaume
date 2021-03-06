<script>
	import { GameProvider } from '../../providers/gameProvider';
	import { TeacherProvider } from '../../providers/teacherProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		props: ['gameid'],
		data() {
			return {
				games: [],
				teachers: [],
				filters: {
					game_id: "",
					name: "",
					page: 1
				},
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				},
				teacherUrl: '/app/treinadores/'
			}
		},
		mounted() {
			this.getGames();
			this.getTeachers();
			setTimeout(() => {
				window.app.$on('user-online', (user) => {
					this.updateUserStatus(user, 1);
				});
				window.app.$on('user-offline', (user) => {
					this.updateUserStatus(user, 0);
				});
			}, 1000);
		},
		methods: {
			getGames: function () {
				GameProvider.list()
							.then((response) => {
								this.games = response.data;
								this.filters.game_id = this.gameid;
							})
							.catch((error) => {
								console.log(error);
								var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
							  	window.app.$emit('app:show-alert', errors, "danger");
							});
			},
			getTeachers() {
				var data = { 'game_id': this.gameid };
				TeacherProvider.list(data)
							   .then((response) => {
							   		this.teachers = response.data.data;
							   		this.pagination.currentPage = response.data.meta.pagination.current_page;
							  		this.pagination.totalPages = response.data.meta.pagination.total_pages;
							   })
							   .catch((error) => {
							   		console.log(error);
									var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
							   });
			},
			doSearch: function(ev, page) {
				if (ev) {
					ev.preventDefault();
				}
				if (page) {
					this.filters.page = page;
				}
				TeacherProvider.list(this.filters)
							   .then((response) => {
							   		this.teachers = response.data.data;
							   		this.pagination.currentPage = response.data.meta.pagination.current_page;
							  		this.pagination.totalPages = response.data.meta.pagination.total_pages;
							   })
							   .catch((error) => {
							   		console.log(error);
									var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
							   });
				
			},
			cleanSearch: function(ev) {
				ev.preventDefault();	
				this.filters.game_id = "";
				this.filters.name = "";
				this.filters.page = 1;
				this.doSearch();
			},
			paginate: function (page) {
				this.doSearch(null, page);
			},
			showStartLessonModal: function (teacherId) {
				window.app.$emit('app:start-confirmation-modal', teacherId, this.filters.game_id);
			},
			updateUserStatus: function (user, status) {
				for (var teacher of this.teachers){
					if (user.id == teacher.id) {
						teacher.is_online = status;
						break;
					}
				}
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
  					<i class="fa fa-gamepad fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{$t('teacher_game.pick_teacher')}}</h1>
			</div>
		</div>
		<div class="row margin-top-20">
			<div class="col-xs-12 col-sm-4">
				<label for="games">{{$t('app.game')}}</label>
				<select id="games" name="games" v-model="filters.game_id" class='form-control'>
					<option value="">{{$t('game.select_game')}}</option>
					<option v-for="game of games" :value="game.id">{{game.name}}</option>
				</select>
			</div>
			<div class="col-xs-12 col-sm-4">
				<label for="teacher">{{$t('app.teacher')}}</label> 
				<input type="text" :placeholder="$t('teacher_game.set_teacher_name')" v-model="filters.name" class='form-control'>
			</div>
			<div class="col-xs-12 col-sm-4">
				<label class='block-label'>&nbsp;</label>
				<button v-on:click="doSearch" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
				<button v-on:click="cleanSearch" type="button" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i></button>
			</div>
		</div>
		<div class="row">
			<div v-for="teacher of teachers" class="col-xs-6 col-sm-4 col-md-3 margin-top-20">
				<div class="teacher-container" :style="{'background-image':'url('+teacher.photo+')'}">
					<div class='teacher-container-info'>
						<div class='row'>
							<div class='col-xs-12 text-center teacher-container-name'>
								<h2 class='text-shadow'>{{teacher.nickname ? teacher.nickname : teacher.name }}</h2>
							</div>
						</div>
						<div class='row margin-top-10'>
							<div class='col-xs-12 text-center'>
								<button class='btn yellow-btn play-btn full-size-button' v-on:click="showStartLessonModal(teacher.id)">{{$t('app.play')}}</button>
							</div>
						</div>
						<div class='row margin-top-10'>
							<div class='col-xs-12 text-center'>
							 	<a class='btn btn-primary full-size-button' :href="teacherUrl+teacher.id">{{$t('app.see_profile')}}</a>
							</div>
						</div>		
					</div>
				</div>
				<div class="teacher-status text-center margin-top-10" v-bind:class="{'online':teacher.is_online}">
					<span class="fa-stack fa-lg">
  						<i class="fa fa-circle fa-stack-2x"></i>
  						<i class="fa fa-gamepad fa-stack-1x fa-inverse"></i>
					</span>
					<h4>{{ teacher.is_online ? 'Online' : 'Offline' }}</h4>
				</div>
			</div>
		</div>
		<div class="row margin-top-20">
			<div class="col-xs-12 text-center">
				<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
			</div>
		</div>
	</div>
</template>