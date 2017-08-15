<script>
	import { GameProvider } from '../../providers/gameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		props: ['gameid'],
		data() {
			return {
				games: [],
				teachers: [],
				filters: {
					game_id: "",
					name: ""
				}
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
								this.filters.game_id = this.gameid;
							})
							.catch((error) => {
								console.log(error);
								var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
							  	window.app.$emit('app:show-alert', errors, "danger");
							});
			},
			doSearch: function(ev) {
				ev.preventDefault();
				console.log('Search');
				console.log(this.filters);
			},
			cleanSearch: function(ev) {
				ev.preventDefault();	
				this.filters.game_id = "";
				this.filters.name = "";
			}
		}
	}
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
				<h1>{{$t('teacher_game.pick_teacher')}}</h1>
			</div>
		</div>
		<div class="row margin-top-20">
			<div class="col-xs-12 col-sm-4">
				<label for="games">{{$t('app.game')}}</label>
				<select id="games" name="games" v-model="filters.game_id" class='form-control'>
					<option value="">{{$t('game.select_game')}}</option>
					<option v-for="game of games" :value="game.id">{{game.name}}</option>
				</select>
			</div>
			<div class="col-xs-12 col-sm-4">
				<label for="teacher">{{$t('app.teacher')}}</label> 
				<input type="text" :placeholder="$t('teacher_game.set_teacher_name')" v-model="filters.name" class='form-control'>
			</div>
			<div class="col-xs-12 col-sm-4">
				<label class='block-label'>&nbsp;</label>
				<button v-on:click="doSearch" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
				<button v-on:click="cleanSearch" type="button" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i></button>
			</div>
		</div>
	</div>
</template>