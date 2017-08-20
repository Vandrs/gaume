<script>
	import { Modal } from 'uiv';
	import StarRating from 'vue-star-rating';
	import { LessonEvaluationProvider } from '../../providers/lessonEvaluationProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { AppRoles } from '../../components/shared/AppRoles';
	export default {
		components: { Modal, StarRating },
		data () {
			return {
				showModal: false,
				evaluation: {},
				evaluationId: null,
				lessonId: null,
				errors: [],
				type: null,
				disableConfirmButton: false,
				urlLogo: window.Laravel.baseUrl + "/images/logo.png"
			}
		},
		created () {
			window.app.$on('app:evaluate-lesson-modal', (lessonId, evaluationId, type) => {
				this.lessonId = lessonId;
				this.evaluationId = evaluationId;
				this.type = type;
				this.toggleModal();
			});
		},
		methods: {
			toggleModal: function() {
				if (!this.showModal) {
					window.app.isLoading = true;
					LessonEvaluationProvider.get(this.evaluationId)
										    .then((response) => {
										    	this.evaluation = response.data;
										    	this.showModal = true;
										    	window.app.isLoading = false;
										    })
										    .catch((error) => {
										    	console.log(error);
												var errors = AppErrorBag.parseErrors(
												  				error.response.status,
												  				error.response.data
												  			);
												window.app.$emit('app:show-alert', errors, "danger");
												window.scrollTo(0,0);
											  	window.app.isLoading = false;
											  	this.showModal = false;
										    });
				} else {
					this.showModal = false;
					this.clean();					
				}
			},
			isStudent: function(){
				return this.evaluation.status == AppRoles.STUDENT;
			},
			isTeacher: function(){
				return this.evaluation.status == AppRoles.TEACHER;
			},
			clean: function () {
				this.evaluation = {};
				this.evaluationI = null;
				this.lessonId = null;
				this.type = null;
				this.errors = [];
			},
			closeModal: function () {
				this.showModal = false;
				this.clean();
			},
			sendEvaluation: function () {
				window.app.isLoading = true;
				this.errors = [];
				this.disableConfirmButton = true;
				LessonEvaluationProvider.evaluate(this.evaluation.id, this.evaluation)
									    .then((response) => {
											window.app.$emit('app:lesson-updated', this.lessonId);
											window.app.$emit('app:show-alert', [response.data.msg], "success");
											this.showModal = false;
											window.app.isLoading = false;
											window.scrollTo(0,0);
											this.clean();					    	
											this.disableConfirmButton = false;
									    })
									    .catch((error) => {
									    	console.log(error);
											var errors = AppErrorBag.parseErrors(
											  				error.response.status,
											  				error.response.data
											  			);
											this.errors = errors;
										  	window.app.isLoading = false;
										  	this.disableConfirmButton = false;
									    });
			}
		}
	}
</script>
<template>
	<div>
		<modal v-model="showModal" :footer="false" :header="false" >
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
				<div v-if="errors.length" class="row">
					<div class="col-xs-12">
						<div class="alert alert-danger">		
							<ul class="alert-list">
								<li v-for="error of errors">{{error}}</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row margin-top-10">
					<div  class="col-xs-12">
						{{ isStudent() ? $t('evaluation.student_type_message') : $t('evaluation.teacher_type_message') }}
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-xs-12">
						<label>{{$t('evaluation.note')}}</label>
					</div>
					<div class="col-xs-12">
						<star-rating v-model="evaluation.note" :show-rating="false"></star-rating>
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-xs-12">
						<label class='control-label'>{{$t('evaluation.comment')}}</label>
						<textarea class="form-control" v-model="evaluation.comment"></textarea>
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-xs-12 text-right">
						<button type="button" class="btn btn-default" v-on:click="closeModal">{{$t('modal.cancel2Text')}}</button>
						<button type="button" class="btn btn-primary" v-bind:class="{disabled:disableConfirmButton}" v-on:click="sendEvaluation">{{$t('modal.confirmText')}}</button>
					</div>
				</div>
			</div>
		</modal>
	</div>
</template>