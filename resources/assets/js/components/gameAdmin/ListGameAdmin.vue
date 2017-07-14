<script>

	import { GameProvider } from '../../providers/gameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	Vue.component('delete-game-modal', require('./ConfirmationDeleteGame'));
	export default {
		components: { Pagination },
		data() {
			var locale = this.$i18n.locale;
			var gameStatus = this.$i18n.messages[locale].game.status_values;
			return {
				filter: {
					name: null,
					status: null,
					page: 1
				},
				status: gameStatus,
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				},
				editLink: '/app/admin/games/editar/',
				games: []
			}
		},
		mounted() {
			this.getGames({});
			setTimeout(() => {
				window.app.$on('app:game-deleted', () => {
					this.getGames(this.filter);
				});
			}, 1000);
		},
		methods: {
			paginate: function (page) {
				this.filter.page = page;
				this.getGames(this.filter);
			},
			getGames: function (filters) {
				GameProvider.listAdmin(filters)
							.then((response) => {
						  		this.games = response.data.data;
						  		this.pagination.currentPage = response.data.meta.pagination.current_page;
						  		this.pagination.totalPages = response.data.meta.pagination.total_pages;
						  	})
						  	.catch((error) => {
						  		var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
							  	window.app.$emit('app:show-alert', errors, "danger"); 	
						  	});
			},
			search: function (evt) {
				evt.preventDefault();
				this.getGames(this.filter);
			},
			clearSearch: function (evt) {
				evt.preventDefault();
				this.filter.name = null;
				this.filter.status = null;
				this.filter.page = 1;
				this.getGames(this.filter);
			},
			deleteGame: function(id) {
				app.$emit('app:delete-game-modal' , id, false);
			},
		}
	}
</script>
<template>
	<div>
		<delete-game-modal>
		</delete-game-modal>
		<div class='row margin-top-10'>
			<div class='col-xs-12 col-md-4 margin-top-10'>
				<label for="name">{{$t('game.title')}}</label>
				<input type="text" class="form-control" name="name" id="name" v-model="filter.name">
			</div>
			<div class='col-xs-12 col-md-4 margin-top-10'>
				<label for="status">{{$t('game.status')}}</label>
				<select id="status" name="status" class="form-control" v-model="filter.status">
					<option value=""></option>
					<option v-for="(label,id) in status" v-bind:value="id">{{label}}</option>
				</select>
			</div>
			<div class='col-xs-12 col-md-4 margin-top-10'>
				<label class="block-label">&nbsp;</label>
				<button type="button" v-on:click="search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
				<button type="button" v-on:click="clearSearch" class="btn btn-default"><i class="fa fa-eraser"></i></button>
			</div>
		</div>
		<div class='row margin-top-10'>
			<div class='col-xs-12 margin-top-10'>
				<table class='table table-striped'>
					<thead>
						<tr>
							<th>{{$t('game.title')}}</th>
							<th>{{$t('game.status')}}</th>
							<th>{{$t('app.actions')}}</th>
						</tr>
					</thead>
					<tbody v-if="games.length > 0"> 
						<tr v-for="game in games">
							<td>{{game.name}}</td>
							<td>{{$t('game.status_values.'+game.status)}}</td>
							<td>
								<a :href="editLink+game.id" class="btn btn-default" :title="$t('buttons.edit')"><i class="glyphicon glyphicon-edit"></i></a> 
								<button class="btn btn-danger" :title="$t('buttons.delete')" v-on:click="deleteGame(game.id)"><i class="glyphicon glyphicon-trash"></i></button>
							</td>
						</tr>
					</tbody>
					<tbody v-else> 
						<tr>
							<td colspan="3">{{$t('app.noRegisterFound')}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-xs-12 text-center">
				<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
			</div>
		</div>
	</div>
</template>