<script> 
	import { Modal } from 'uiv';
	import { GameProvider } from '../../providers/gameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default  {
		components: { Modal },
		data () {
			return {
				showModal: false,
				gameId: null,
				redirectToList: false
			}
		},
		created () {
			setTimeout(() => {
				window.app.$on('app:delete-game-modal', (gameId, redirect) => {
					this.toggleModal();
					this.gameId = gameId;
					this.redirectToList = redirect ? redirect : false;
				});
			},1000);
		},
		methods: {
			dismissCallback: function (msg) {
				if (msg == 'ok') {
					window.app.isLoading = true;
					var redirect = this.redirectToList;
					GameProvider.delete(this.gameId)
								.then((response) => {
									window.app.isLoading = false;
									window.app.$emit('app:show-alert', [response.data.msg], "success");
									window.scrollTo(0,0);
									if (redirect) {
										setTimeout(function(){
											window.location = "/app/admin/games";
										},5000);
									} else {
										window.app.$emit('app:game-deleted');
									}								
								})
								.catch((error) => {
									window.app.isLoading = false;
									var response = error.response;
									if (response.status == 400) {
										this.errors = response.data.errors;
										var locale = this.$i18n.locale;
										var msg = this.$i18n.messages[locale].app.defaultErrors;
										window.app.$emit('app:show-alert', [msg], "danger");
										window.scrollTo(0,0);
									} else {
										var errors = AppErrorBag.parseErrors(
										  				response.status,
										  				response.data
										  			);
									  	window.app.$emit('app:show-alert', errors, "danger");
									  	window.scrollTo(0,0);
									}
								})
				}
				this.gameId = null;
				this.redirectToList = false;
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
				{{$t('modal.gameMessages.confirmDelete')}}
			</div>
		</modal>
	</div>
</template>