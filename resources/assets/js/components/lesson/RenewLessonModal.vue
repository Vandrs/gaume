<script> 
	import { Modal } from 'uiv';
	import { PeriodProvider } from '../../providers/periodProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default  {
		components: { Modal },
		data () {
			return {
				showModal: false,
				lessonId: null
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
			dismissCallback: function(msg) {
				if (msg == 'ok') {
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
				}
			},
			toggleModal: function() {
				this.showModal = (!this.showModal) ? true : false;
			}	
		}
	}
</script>
<template>
	<div>
		<modal  v-model="showModal" :title="$t('modal.warning')" v-on:hide="dismissCallback" :ok-text="$t('modal.okText')" :cancel-text="$t('modal.cancelText')" >
			<div slot="default">
				{{$t('app.acceptExecuteAction')}}
			</div>
		</modal>
	</div>
</template>
