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
				redirectToList: false,
				urlLogo: window.Laravel.baseUrl + "/images/logo.png"
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
				
				this.gameId = null;
				this.redirectToList = false;
			},
			toggleModal: function() {
				this.showModal = (!this.showModal) ? true : false;
			},
			closeModal: function() {
				this.showModal = false;
				this.gameId = null;
				this.redirectToList = false;
			},
			confirmAction: function(){
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
							});
				this.showModal = false;
				this.gameId = null;
			}
		}
	}
</script>
<template>
	<div>
		<modal  v-model="showModal" :footer="false" :header="false" >
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
						{{$t('modal.gameMessages.confirmDelete')}}
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-xs-12 text-right">
						<button type="button" class="btn btn-default" v-on:click="closeModal">{{$t('modal.cancel2Text')}}</button>
						<button type="button" class="btn btn-primary" v-on:click="confirmAction">{{$t('modal.confirmText')}}</button>
					</div>
				</div>
			</div>
		</modal>
	</div>
</template>