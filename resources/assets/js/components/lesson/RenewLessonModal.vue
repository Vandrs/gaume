<script> 
	import { Modal } from 'uiv';
	import { PeriodProvider } from '../../providers/periodProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default  {
		components: { Modal },
		data () {
			return {
				showModal: false,
				lessonId: null,
				urlLogo: window.Laravel.baseUrl + "/images/logo.png"
			}
		},
		created () {
			var self = this;
			setTimeout(function(){
				window.app.$on('app:renew-lesson-modal', function (lessonId) {
					self.toggleModal();
					self.lessonId = lessonId;
				});
			},1000);
		},
		methods: {
			toggleModal: function() {
				this.showModal = (!this.showModal) ? true : false;
			},
			closeButton: function(evt) {
				evt.preventDefault();
				this.showModal = false;
			},
			confirmButton: function(evt) {
				evt.preventDefault();
				window.app.isLoading = true;
				PeriodProvider.create(this.lessonId)
							  .then((response) => {
							  		console.log('Period Created and Lesson Updated');
							  		window.app.$emit('app:lesson-updated', this.lessonId);
							  		this.lessonId = null;
							  		window.app.isLoading = false;
							  })
							  .catch((error) => {
							  		var errors = AppErrorBag.parseErrors(
							  				error.response.status,
							  				error.response.data
							  			);
							  		window.app.$emit('app:show-alert', errors, "danger");
							  		this.lessonId = null;
							  		window.app.isLoading = false;
							  });
				this.showModal = false;
			}
		}
	}
</script>
<template>
	<div>
		<modal  v-model="showModal" :header="false" :footer="false" >
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
						<button type="button" class="btn btn-default" v-on:click="closeButton">{{$t('modal.cancelText')}}</button>
						<button type="button" class="btn btn-primary" v-on:click="confirmButton">{{$t('modal.okText')}}</button>
					</div>
				</div>
			</div>
		</modal>
	</div>
</template>
