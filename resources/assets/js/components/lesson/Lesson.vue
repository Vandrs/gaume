<script>
	import { LessonProvider } from '../../providers/lessonProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import * as moment from 'moment';
	import { LessonStatus } from '../../components/lesson/LessonStatus';
	import { AppRoles } from '../../components/shared/AppRoles';
	import StarRating from 'vue-star-rating';
	Vue.component('lesson-accept-modal',require('../../components/lesson/ConfirmationAcceptModal'));
	Vue.component('lesson-renew-modal',require('../../components/lesson/RenewLessonModal'));
	Vue.component('evaluate-lesson-modal', require('../../components/lesson/EvaluateLessonModal'));
	export default {
		components: { StarRating },
		props: {
			id: Number
		},
		data() {
			return {
				lesson: null,
				user: window.Laravel.user,
				status: LessonStatus,
				currentStatus: null,
				duration: null,
				intervelId: null,
				updateInterval: {
					pendingLesson: 10000, // 3 Minutos
					inProgressLesson: (5000*60) // 5 Minutos
				},
				totalHours: 0,
				remainingTime: null,
				remainingTimeInterval: null,
				timeToRenewLesson: (1000*60*10), //10 Minutos
				studentEvaluation: null,
				teacherEvaluation: null,
				showEvaluationButton: false
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
							  	this.lesson = response.data;
							  	this.parseDates();
							  	this.setLessonStatus();
							  	this.setDuration();
							  	this.setUpdateStatus();
							  	this.setRemainingTimeInterval();
							  	this.setupEvaluations();
							  	this.checkShowEvaluationButton();
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
			setLessonStatus: function () {
				if (this.lesson.status == this.status.PENDING) {
					this.currentStatus = this.lesson.status; 
					return;
				}
				for (var period of this.lesson.periods) {
					if (period.status == this.status.PENDING) {
						this.currentStatus = period.status;
						return;
					}
				}
				this.currentStatus = this.lesson.status;
			},
			setDuration: function () {
				var hour = 0;
				var status = [this.status.PENDING, this.status.IN_PROGRESS, this.status.FINISHED];
				for (var period of this.lesson.periods) {
					if (status.indexOf(period.status) >= 0) {
						hour += period.hours;
					}
				}
				this.duration = hour;
			},
			parseDates: function () {
				this.lesson.created_at = moment(this.lesson.created_at);
				for (var i in this.lesson.periods) {
					this.lesson.periods[i].created_at = moment(this.lesson.periods[i].created_at);
					this.lesson.periods[i].updated_at = moment(this.lesson.periods[i].updated_at);
				}
			},
			setTotalHours: function () {
				var validStatus = [this.status.IN_PROGRESS, this.status.FINISHED];
				this.totalHours = 0;
				for (var period of this.lesson.periods) {
					if (validStatus.indexOf(period.status) >= 0) {
						this.totalHours += 1;
					}
				}
			},
			setRemainingTimeInterval: function () {
				this.cancelRemaininTimeInterval();
				if (this.currentStatus == this.status.IN_PROGRESS) {
					this.setRemainingTime();
					this.remainingTimeInterval = setInterval(() => {
													this.setRemainingTime();
												}, (1000*60));
				}
			},
			cancelRemaininTimeInterval: function () {
				if (this.remainingTimeInterval) {
					clearInterval(this.remainingTimeInterval);
				}
			},
			setRemainingTime(){
				this.setTotalHours();
				var period = this.getInProgressPeriod();
				var endTime = period.updated_at.clone().add(period.hours, 'h');
				var now = moment();
				var diff = endTime.diff(now);
				this.remainingTime = moment.duration(diff);
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
			showConfirmationModal: function (confirm) {
				var period = this.getPendingPeriod();
				if (period) {
					window.app.$emit('app:start-accept-period-modal', this.lesson.id, period.id, confirm);
				} else {
					window.app.$emit('app:start-accept-lesson-modal', this.lesson.id, confirm);
				}
			},
			showRenewClassModal: function(){
				window.app.$emit('app:renew-lesson-modal', this.lesson.id);
			},
			updateStatus: function (time) {
				this.intervalId = setInterval(() => {
									this.getLesson();
								  }, time);
			},
			cancelUpdateStatus: function () {
				clearInterval(this.intervalId);
			},
			setUpdateStatus: function () {
				if (this.intervalId) {
					this.cancelUpdateStatus();
				}
				if (this.currentStatus == this.status.PENDING) {
					this.updateStatus(this.updateInterval.pendingLesson);
				} else if (this.currentStatus == this.status.IN_PROGRESS) {
					this.updateStatus(this.updateInterval.inProgressLesson);
				}
			},
			getPendingPeriod: function () {
				var pendindPeriod = null;
				for (var period of this.lesson.periods) {
					if (period.status == this.status.PENDING) {
						if (pendindPeriod) {
							if (period.id > pendindPeriod.id) {
								pendindPeriod = period;
							}
						} else {
							pendindPeriod = period;
						}
					}
				}
				return pendindPeriod;
			},
			getInProgressPeriod: function () {
				var inProgressPeriod = null;
				for (var period of this.lesson.periods) {
					if (period.status == this.status.IN_PROGRESS) {
						if (inProgressPeriod) {
							if (period.id > inProgressPeriod.id) {
								inProgressPeriod = period;
							}
						} else {
							inProgressPeriod = period;
						}
					}
				}
				return inProgressPeriod;
			},
			setupEvaluations: function() {
				if (this.lesson.status == this.status.FINISHED) {
					for (var i = 0; i < this.lesson.evaluations.length; i++) {
						var evaluation = this.lesson.evaluations[i];
						if (evaluation.type == AppRoles.STUDENT) {
							this.teacherEvaluation = evaluation;
						} else if (evaluation.type == AppRoles.TEACHER) {
							this.studentEvaluation = evaluation;
						}
					}
				}
			},
			checkShowEvaluationButton: function() {
				this.showEvaluationButton = false;
				if (this.lesson.status == this.status.FINISHED && !this.isAdmin()) {
					if (this.isTeacher() && this.teacherEvaluation && this.teacherEvaluation.status == this.status.EVALUATION_PENDING) {
						this.showEvaluationButton = true;
					} else if (this.isStudent() && this.studentEvaluation && this.studentEvaluation.status == this.status.EVALUATION_PENDING) {
						this.showEvaluationButton = true;
					}
				}
			},
			showEvaluationModal() {
				if (this.isTeacher() && this.teacherEvaluation.status == this.status.EVALUATION_PENDING) {
					window.app.$emit('app:evaluate-lesson-modal', this.lesson.id, this.teacherEvaluation.id, this.teacherEvaluation.type);
				} else if (this.isStudent() && this.studentEvaluation.status == this.status.EVALUATION_PENDING) {
					window.app.$emit('app:evaluate-lesson-modal', this.lesson.id, this.studentEvaluation.id, this.studentEvaluation.type);
				}
			}
		} 
	}
</script>
<template>
	<div v-if="lesson" id="lesson">
		<lesson-accept-modal>
		</lesson-accept-modal>
		<lesson-renew-modal>
		</lesson-renew-modal>
		<evaluate-lesson-modal>
		</evaluate-lesson-modal>
		<div class="row" v-if="currentStatus == status.PENDING && isTeacher()">
			<div class="col-xs-12">
				<div class="alert alert-warning">
					<span class="text-label">{{$t('lesson.labels.confirm_class')}}</span> 
					<button v-on:click="showConfirmationModal(true)" class="btn btn-success">
						<i class="glyphicon glyphicon-thumbs-up"></i> {{$t('app.yes')}}
					</button>
					<button v-on:click="showConfirmationModal(false)" class="btn btn-danger">
						<i class="glyphicon glyphicon-thumbs-down"></i> {{$t('app.no')}}
					</button>
				</div>
			</div>
		</div>
		<div class="row" v-if="showEvaluationButton">
			<div class="col-xs-12">
				<div class="alert alert-warning">
					<span class="text-label">{{$t('evaluation.message')}}</span> 
					<button v-on:click="showEvaluationModal()" class="btn btn-success">
						<i class="fa fa-star"></i> {{$t('evaluation.evaluate')}}
					</button>
				</div>
			</div>
		</div>

		<div class="row" v-if="isStudent() && remainingTime && (currentStatus != status.PENDING) && (remainingTime.asMilliseconds() > 0 && remainingTime.asMilliseconds() < timeToRenewLesson) ">
			<div class="col-xs-12">
				<div class="alert alert-warning">
					<span class="text-label">{{$t('lesson.labels.renew_class')}}</span>
					<button v-on:click="showRenewClassModal()" class="btn btn-success">
						<i class="glyphicon glyphicon-thumbs-up"></i> {{$t('app.yes')}}
					</button>
				</div>
			</div>
		</div>

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
				<div class="row" v-if="currentStatus != status.CANCELED">
					<div class="col-xs-12">
						<span class="text-label">{{$t('lesson.labels.duration')}}:</span> {{duration ? duration+" "+$tc('app.hour', duration) : $t('app.unavailable') }}
					</div>
				</div>
				<div class="row" v-if="currentStatus == status.IN_PROGRESS && remainingTime">
					<div class="col-xs-12">
						<span class="text-label">{{$t('lesson.labels.remaining_time')}}:</span><span class="bold-blue"><i class="glyphicon glyphicon-hourglass"></i> {{ remainingTime.asMilliseconds() > 0 ? remainingTime.hours()+":"+remainingTime.minutes() : '00:00' }}</span>
					</div>
				</div>
				<div class="row" v-if="(isStudent() || isAdmin()) && studentEvaluation">
					<div class="col-xs-12">
						<span class="text-label">{{$t('evaluation.evaluation')}}:</span>
					</div>
					<div class="col-xs-12">
						<StarRating v-model="studentEvaluation.note" :read-only="true" :star-size="20" :show-rating="false"></StarRating>
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