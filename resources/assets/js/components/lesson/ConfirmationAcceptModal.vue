<script> 
	import { Modal } from 'uiv';
	import { LessonProvider } from '../../providers/lessonProvider';
	import { PeriodProvider } from '../../providers/periodProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default {
		components: { Modal },
		data () {
			return {
				showModalConfirmLesson: false,
				showModalConfirmPeriod: false,
				lessonId: null,
				periodId: null,
				confirm: null,
				urlLogo: window.Laravel.baseUrl + "/images/logo.png"
			}
		},
		created () {
			setTimeout(() => {
				window.app.$on('app:start-accept-lesson-modal', (lessonId, confirm) => {
					this.toggleLessonModal();
					this.lessonId = lessonId;
					this.confirm = confirm;
				});
				window.app.$on('app:start-accept-period-modal', (lessonId, periodId, confirm) => {
					this.togglePeriodModal();
					this.lessonId = lessonId;
					this.periodId = periodId;
					this.confirm = confirm;
				});
			}, 1000);
		},
		methods: {
			toggleLessonModal: function() {
				this.showModalConfirmLesson = (!this.showModalConfirmLesson) ? true : false;
			},
			togglePeriodModal: function() {
				this.showModalConfirmPeriod = (!this.showModalConfirmPeriod) ? true : false;
			},
			closeLessonButton: function(evt) {
				evt.preventDefault();
				this.showModalConfirmLesson = false;
			},
			confirmLessonButton: function(evt){
				window.app.isLoading = true;
				evt.preventDefault();
				LessonProvider.confirm(this.lessonId, this.confirm)
							  .then((response) => {
							  		window.app.$emit('app:lesson-updated', this.lessonId);
							  		this.lessonId = null;
									this.confirm = null;
									window.app.isLoading = false;
							  })
							  .catch((error) => {
							  		var errors = AppErrorBag.parseErrors(
							  				error.response.status,
							  				error.response.data
							  			);
							  		window.app.$emit('app:show-alert', errors, "danger");
							  		window.app.$emit('app:lesson-updated', this.lessonId);
							  		this.lessonId = null;
									this.confirm = null;
									window.app.isLoading = false;
							  });
				this.showModalConfirmLesson = false;		
			},
			closePeriodButton: function(evt) {
				evt.preventDefault();
				this.showModalConfirmPeriod = false;
			},
			confirmPeriodButton: function(evt) {
				window.app.isLoading = true;
				evt.preventDefault();
				PeriodProvider.confirm(this.lessonId, this.periodId, this.confirm)
							  .then((reponse) => {
							  	window.app.$emit('app:lesson-updated', this.lessonId);
							  	this.lessonId = null;
								this.periodId = null;
								this.confirm = null;
								window.app.isLoading = false;
							  })
							  .catch((error) => {
							  	var errors = AppErrorBag.parseErrors(
							  				error.response.status,
							  				error.response.data
							  			);
						  		window.app.$emit('app:show-alert', errors, "danger");
						  		window.app.$emit('app:lesson-updated', this.lessonId);
						  		this.lessonId = null;
						  		this.periodId = null;
								this.confirm = null;
								window.app.isLoading = false;
							  });
				this.showModalConfirmPeriod = false;
			}
		}
	}
</script>
<template>
	<div>
		<modal  v-model="showModalConfirmLesson" :header="false" :footer="false" >
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
				<div class="row margin-top-20">
					<div class="col-xs-12">
						{{$t('app.acceptExecuteAction')}}
					</div>
				</div>
				<div class="row margin-top-20">
					<div class="col-xs-12 text-right">
						<button type="button" class="btn btn-default" v-on:click="closeLessonButton">{{$t('modal.cancelText')}}</button>
						<button type="button" class="btn btn-primary" v-on:click="confirmLessonButton">{{$t('modal.okText')}}</button>
					</div>
				</div>
			</div>
		</modal>
		<modal  v-model="showModalConfirmPeriod" :header="false" :footer="false" >
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
				<div class="row margin-top-20">
					<div class="col-xs-12">
						{{$t('app.acceptExecuteAction')}}
					</div>
				</div>
				<div class="row margin-top-20">
					<div class="col-xs-12 text-right">
						<button type="button" class="btn btn-default" v-on:click="closePeriodButton">{{$t('modal.cancelText')}}</button>
						<button type="button" class="btn btn-primary" v-on:click="confirmPeriodButton">{{$t('modal.okText')}}</button>
					</div>
				</div>
			</div>
		</modal>
	</div>
</template>
