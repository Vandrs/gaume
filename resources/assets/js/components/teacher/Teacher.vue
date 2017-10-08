<script>
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { AppRoles } from '../../components/shared/AppRoles';
	import { TeacherProvider } from '../../providers/teacherProvider';
	import StarRating from 'vue-star-rating';
	Vue.component('send-inbox-message',require('../../components/message/UserMessage'));
	export default {
		props: ['id'],
		components : { StarRating },
		data() {
			return {
				teacher: {
					games: []
				},
				showMessageBox: false,
				recipient: 0
			};
		},
		mounted() {
			this.getTeacher();
			this.recipient = this.id;
		},
		methods: {
			getTeacher: function() {
				TeacherProvider.get(this.id)
							   .then((response) => {
							   		this.teacher = response.data;
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
			showStartLessonModal: function(gameId) {
				window.app.$emit('app:start-confirmation-modal', this.id, gameId);
			},
			toggleMessageBox: function () {
				this.showMessageBox = this.showMessageBox ? false : true;
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
					<h1>{{$t('app.teacher')}}</h1>
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-sm-3 margin-top-20">
			<div class="row">
				<div class='col-xs-12 img-teacher-content'>
					<img :src="teacher.photo" :alt="teacher.name" :title="teacher.name" />
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-9 margin-top-20">
			<div class="row">
				<div class='col-xs-12'>
					<h2>{{teacher.name}}</h2>
				</div>
			</div>
			<div class="row" v-if="teacher.evaluation">
				<div class='col-xs-12'>
					<StarRating v-model="teacher.evaluation.note" :read-only="true" :star-size="25" :show-rating="false"></StarRating>
				</div>
				<div class='col-xs-12' v-if="teacher.evaluation.note == 0">
					<span class="help-block">{{$t('evaluation.bee_first')}}</span>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12">
					<button class='btn btn-primary' v-on:click="toggleMessageBox">{{$t('buttons.send_message')}}</button>
				</div>
				<div class="col-xs-12 col-xs-12 margin-top-20">
					<send-inbox-message :showMessageBox="showMessageBox" :recipient="recipient"></send-inbox-message>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<h3>{{$t('app.games')}}</h3>
					<hr />
				</div>
			</div>
			<div class="row" v-if="teacher.games.length > 0">
				<div class="col-xs-12 margin-bottom-10" v-for="game of teacher.games">
					<div class="row">
						<div class="col-xs-12">
							<h4>{{game.name}}</h4>
						</div>
					</div>
					<div class="row">
						<div class='col-xs-12'>
							{{game.description}}
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 margin-top-10">
							<div>{{$t('teacher_page.nickname')}}</div>	
						</div>
						<div class="col-xs-12">
							<ul class="inline-list">
								<li v-for="platform of game.platforms">
									{{platform.name}}: <strong>{{platform.nickname}}</strong>
								</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 text-left margin-top-10">
							<button class='btn yellow-btn play-btn' v-on:click="showStartLessonModal(game.id)">{{$t('app.play')}}</button>
						</div>
					</div>
					<hr />
				</div>
			</div>
			<div class="row" v-else >
				<div class="col-xs-12">
					{{$t('teacher_page.no_available_game')}}
				</div>
			</div>
		</div>
	</div>
</template>