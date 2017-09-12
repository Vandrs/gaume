<script>
	import { GameProvider } from '../../providers/gameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default {
		data() {
			return {
				teacherUrl: Laravel.baseUrl+'/app/treinadores',
				games: []
			}
		},
		mounted() {
			this.getGames();
		},
		methods: {
			getGames: function () {
				GameProvider.list()
							.then((response) => {
								this.games = response.data;
							})
							.catch((error) => {
								console.log(error);
								var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
							  	window.app.$emit('app:show-alert', errors, "danger");
							});
			}
		}
	};
</script>
<template>
	<div>
		<div class="row">
			<div class="col-xs-12 text-center">
				<span class="fa-stack fa-lg">
  					<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
  					<i class="fa fa-gamepad fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{$t('game.pick_game')}}</h1>
			</div>
		</div>
		<div class='row margin-top-20'>
			<div v-for="game of games"  class="col-xs-12 game-home" :style="{'background-image':'url('+game.photo+')'}">
				<div class="layer">
				</div>
				<div class="row margin-top-10">
					<div class="col-xs-12">
						<h2>{{game.name}}</h2>
					</div>
				</div>
				<div class="row margin-top-10 game-description">
					<div class="col-xs-12 col-md-8 col-sm-8">
						{{game.description}}
					</div>
				</div>
				<div class="row margin-top-20">
					<div class="col-xs-12">
						<a :href="teacherUrl+'?game='+game.id" class="btn yellow-btn play-btn">{{$t('app.play')}}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>