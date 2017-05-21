<script>
	import { LessonProvider } from '../../providers/lessonProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import * as moment from 'moment';
	Vue.component('lesson-accept-modal',require('../../components/lesson/ConfirmationAcceptModal'));
	export default {
		props: {
			id: Number
		},
		data() {
			return {
				lesson: null,
				user: window.Laravel.user,
				status: {
					PENDING:1,
					IN_PROGRESS:2,
					FINISHED:3,
					CANCELED:4
				},
				currentStatus: null,
				duration: null
			}
		},
		mounted() {
			this.getLesson();
			setTimeout(() => {
				window.app.$on('app:lesson-updated', (lessonId) => {
					if (this.lesson.id == lessonId) {
						this.getLesson();
					}
				});
			}, 1000);
		},
		methods: {
			getLesson: function () {
				LessonProvider.get(this.id)
							  .then((response) => {
							  	console.log(response.data);
							  	this.lesson = response.data;
							  	this.parseDates();
							  	this.setLessonStatus();
							  	this.setDuration();
							  })
							  .catch((response) => {
									var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");	
							  });
			},
			setLessonStatus: function () {
				if (this.lesson.status == this.status.PENDING) {
					this.currentStatus = this.lesson.status; 
				}
				for (var period in this.lesson.periods) {
					console.log(period);
					if (period.status == this.status.PENDING) {
						this.currentStatus = period.status;
					}
				}
				this.currentStatus = this.lesson.status;
			},
			setDuration: function () {
				var hour = 0;
				var status = [this.status.PENDING, this.status.IN_PROGRESS, this.status.FINISHED];
				for (var period in this.lesson.periods) {
					if (status.indexOf(period.status) >= 0) {
						hour += period.hours;
					}
				}
				this.duration = hour;
			},
			parseDates: function () {
				this.lesson.created_at = moment(this.lesson.created_at);
			},
			isTeacher: function () {
				return this.user.id == this.lesson.teacher.id;
			},
			isStudent: function () {
				return this.user.id == this.lesson.student.id;
			},
			isAdmin: function () {
				return (!this.isStudent() && !this.isTeacher());
			},
			showConfirmationAccepModal: function (confirm) {
				console.log('Emit');
				window.app.$emit('app:start-accept-lesson-modal', this.lesson.id, confirm);
			}
		} 
	}
</script>
<template>
	<div v-if="lesson" id="lesson">
		<lesson-accept-modal>
		</lesson-accept-modal>
		<div class="row">
			<div class="col-xs-12 col-md-4">
				<div class="row">
					<div class="col-xs-12">
						<h2>{{$t('app.resume')}}</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<span class="text-label">{{$t('lesson.labels.start_date')}}:</span>{{lesson.created_at.format('D/M/YY H:m')}}
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<span class="text-label">{{$t('lesson.labels.status')}}:</span>{{$t('lesson.status.'+currentStatus)}}
					</div>
				</div>
				<div class="row" v-if="currentStatus == status.PENDING && isTeacher()">
					<div class="col-xs-12">
						<span class="text-label">{{$t('lesson.labels.confirm_class')}}</span> 
						<button v-on:click="showConfirmationAccepModal(true)" class="btn btn-success">
							<i class="glyphicon glyphicon-thumbs-up"></i> {{$t('app.yes')}}
						</button>
						<button v-on:click="showConfirmationAccepModal(false)" class="btn btn-danger">
							<i class="glyphicon glyphicon-thumbs-down"></i> {{$t('app.no')}}
						</button>
					</div>
				</div>
				<div class="row" v-if="currentStatus != status.CANCELED">
					<div class="col-xs-12">
						<span class="text-label">{{$t('lesson.labels.duration')}}:</span> {{duration ? duration+" "+$t('app.duration', duration) : $t('app.unavailable') }}
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="row">
					<div class="col-xs-12">
						<h2>{{$t('app.teacher')}}</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						{{lesson.teacher.name}}
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						{{lesson.teacher.email}}
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="row">
					<div class="col-xs-12">
						<h2>{{$t('app.student')}}</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						{{lesson.student.name}}
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						{{lesson.student.email}}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div v-else>
		{{$t('app.loading')}}
	</div>
</template>