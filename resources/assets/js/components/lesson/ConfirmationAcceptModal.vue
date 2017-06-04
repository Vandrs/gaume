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
				confirm: null
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
			dismissLessonCallback: function(msg) {
				if (msg == 'ok') {
					window.app.isLoading = true;
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
				}
			},
			dismissPeriodCallback: function(msg) {
				if (msg == 'ok') {
					window.app.isLoading = true;
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
				}
			},
			toggleLessonModal: function() {
				this.showModalConfirmLesson = (!this.showModalConfirmLesson) ? true : false;
			},
			togglePeriodModal: function() {
				this.showModalConfirmPeriod = (!this.showModalConfirmPeriod) ? true : false;
			}
		}
	}
</script>
<template>
	<div>
		<modal  v-model="showModalConfirmLesson" :title="$t('modal.warning')" v-on:hide="dismissLessonCallback" :ok-text="$t('modal.okText')" :cancel-text="$t('modal.cancelText')" >
			<div slot="default">
				{{$t('app.acceptExecuteAction')}}
			</div>
		</modal>
		<modal  v-model="showModalConfirmPeriod" :title="$t('modal.warning')" v-on:hide="dismissPeriodCallback" :ok-text="$t('modal.okText')" :cancel-text="$t('modal.cancelText')" >
			<div slot="default">
				{{$t('app.acceptExecuteAction')}}
			</div>
		</modal>
	</div>
</template>
