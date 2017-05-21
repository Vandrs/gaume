<script> 
	import { Modal } from 'uiv';
	import { LessonProvider } from '../../providers/lessonProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default  {
		components: { Modal },
		data () {
			return {
				showModal: false,
				teacherId: null
			}
		},
		created () {
			var self = this;
			setTimeout(function(){
				window.app.$on('app:start-confirmation-modal', function (teacherId) {
					self.toggleModal();
					self.teacherId = teacherId;
				});
			},1000);
		},
		methods: {
			dismissCallback: function(msg) {
				if (msg == 'ok') {
					LessonProvider.create(this.teacherId)
								  .then((response) => {
								  		window.location.href = window.Laravel.baseUrl+"/app/aula/"+response.data.id;
								  })
								  .catch((error) => {
								  		var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
								  		window.app.$emit('app:show-alert', errors, "danger");
								  });
				}
				this.teacherId = null;
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
				{{$t('modal.classMessages.confirmStartClass')}}
			</div>
		</modal>
	</div>
</template>
