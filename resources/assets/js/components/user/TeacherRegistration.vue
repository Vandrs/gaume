<script>
	import { GameProvider } from '../../providers/gameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default {
		data() {
			return {
				registration : {
					id: null,
					name: null,
					email: null,
					games: {},
				}
			}
		},
		mounted() {
			this.getGames();
		},
		methods: {
			getGames: function () {
				GameProvider.listAdminAvailables()
							.then((response) => {
								console.log(response.data);
							})
						    .catch((error) => {
								var response = error.response;
								var errors = AppErrorBag.parseErrors(
								  				response.status,
								  				response.data
								  			);
							  	window.app.$emit('app:show-alert', errors, "danger");
							  	window.scrollTo(0,0);
								
						    });			
			}
		}
	}
</script>
<template>
<div>
	<div class="row">
		<div class="col-xs-12">
			OK DONE!!!!
		</div>
	</div>
</div>
</template>