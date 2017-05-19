<script> 
	import { Modal } from 'uiv';
	export default  {
		components: { Modal },
		data () {
			return {
				showModal: false,
				title: "Atenção",
				message: "Deseja mesmo iniciar a aula?",
				okText: "Sim",
				cancelText: "Não"
			}
		},

		created () {
			self = this;
			setTimeout(function(){
				window.app.$on('app:start-confirmation-modal',function () {
					self.toggleModal();
				});
			},1000);
		},
		methods: {
			showCallback: function() {
				console.log('ShowCallback');
			},
			dismissCallback: function(event) {
				console.log('DismissCallback');
				console.log(event);
			},
			toggleModal: function() {
				this.showModal = (!this.showModal) ? true : false;
			}	
		}
	}
</script>
<template>
	<div>
		<modal  v-model="showModal" title="title" v-on:show="showCallback" v-on:hide="dismissCallback" ok-text="okText" cancel-text="cancelText">
			<div slot="default">
				{{message}}
			</div>
		</modal>
	</div>
</template>
