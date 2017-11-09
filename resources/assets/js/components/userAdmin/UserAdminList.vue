<script>
	import { UserAdminProvider } from '../../providers/userAdminProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { AppRoles } from '../../components/shared/AppRoles';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data() {
			return {
				filters: {
					page: 1,
					name: null,
					nickname: null,
					status: null,
					role_id: null
				},
				pagination: {
					currentPage: 1,
					totalPages: 1,
					linksRange: 5
				},
				roles: [1,2,3],
				status: [1,0],
				active: 1,
				inactive: 0,
				users:[],
				createLink: "/app/admin/usuarios/professor/pre-cadastro",
				viewLink: "/app/admin/usuarios/"
			}
		},
		mounted() {
			this.getUsers();
		},
		methods: {
			getUsers: function() {
				window.app.isLoading = true;
				UserAdminProvider.getAll(this.filters)
								 .then((response) => {
								 	this.users = response.data.data;
							  		this.pagination.currentPage = response.data.meta.pagination.current_page;
							  		this.pagination.totalPages = response.data.meta.pagination.total_pages;
							  		window.app.isLoading = false;
								 })
								 .catch((error) => {
								 	var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
								  	window.app.isLoading = false; 	
								 });
			},
			formatDate: function(strData) {
				var arrData = strData.split(" ");
				var date = arrData[0];
				return date.split('-').reverse().join('/');
			},
			search: function() {
				this.filters.page = 1;
				this.getUsers();
			},
			clearSearch: function() {
				this.filters.page = 1;
				this.filters.name = null;
				this.filters.nickname = null;
				this.filters.status = null;
				this.filters.role_id = null;
				this.getUsers();
			},
			paginate: function(page) {
				this.filters.page = page;
				this.getUsers();
			},
			activate: function(user) {
				window.app.isLoading = true;
				UserAdminProvider.activate(user.id)
								 .then((response) => {
								 	window.app.isLoading = false;
								 	user.status = this.active;
								 })
								 .catch((error) => {
								 	var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
								  	window.app.isLoading = false; 	
								 });
			},
			inactivate: function(user) {
				window.app.isLoading = true;
				UserAdminProvider.inactivate(user.id)
								 .then((response) => {
								 	window.app.isLoading = false;
								 	user.status = this.inactive;
								 })
								 .catch((error) => {
								 	var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
								  	window.app.isLoading = false; 	
								 });
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
						<i class="fa fa fa-users fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{ $t('app.users') }}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-3 margin-top-10">
				<label class="control-label">{{$t('profile.name')}}</label>
				<input type="text" class="form-control" v-model="filters.name" name="name">
			</div>
			<div class="col-xs-12 col-md-3 margin-top-10">
				<label class="control-label">{{$t('profile.nickname')}}</label>
				<input type="text" class="form-control" v-model="filters.nickname" name="nickname">
			</div>
			<div class="col-xs-12 col-md-3 margin-top-10">
				<label class="control-label">{{$t('profile.profile')}}</label>
				<select class="form-control" v-model="filters.role_id" name="role">
					<option value="">{{$t('app.select')}}</option>
					<option v-for="role in roles" :value="role">{{$t('roles_id.'+role)}}</option>
				</select>
			</div>
			<div class="col-xs-12 col-md-3 margin-top-10">
				<label class="control-label">{{$t('profile.status')}}</label>
				<select class="form-control" v-model="filters.status" name="status">
					<option value="">{{$t('app.select')}}</option>
					<option v-for="stts in status" :value="stts">{{$t('status.'+stts)}}</option>
				</select>
			</div>
		</div>
		<div class="row margin-top-10">
			<div class="col-xs-12 text-left">
				<button v-on:click="search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
				<button v-on:click="clearSearch" class="btn btn-default"><i class="glyphicon glyphicon-erase"></i></button>
				<a class="btn btn-primary" :href="createLink"><i class="glyphicon glyphicon-plus-sign"></i> {{$t('buttons.add_new_teacher')}}</a>
			</div>
		</div>
		<div class="row margin-top-10">
			<div class="col-xs-12">
				<table class='table table-default table-bordered table-striped'>
					<thead>
						<tr>
							<th>{{$t('user_admin.name')}}</th>
							<th>{{$t('user_admin.nickname')}}</th>
							<th>{{$t('user_admin.email')}}</th>
							<th>{{$t('user_admin.role')}}</th>
							<th>{{$t('user_admin.status')}}</th>
							<th>{{$t('user_admin.created_at')}}</th>
							<th>{{$t('buttons.actions')}}</th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="users.length == 0">
							<td colspan="7">{{$t('app.noRegisterFound')}}</td>
						</tr>
						<tr v-else v-for="user in users">
							<td>{{user.name}}</td>
							<td>{{user.nickname}}</td>
							<td>{{user.email}}</td>
							<td>{{$t('roles.'+user.role.name)}}</td>
							<td>{{$t('status.'+user.status)}}</td>
							<td>{{formatDate(user.created_at)}}</td>
							<td >	
								<a :href="viewLink+user.id" class='btn btn-default'><i class="glyphicon glyphicon-eye-open"></i></a>
								<button v-if="user.status" type="button" class='btn btn-danger' v-on:click="inactivate(user)" :title="$t('buttons.inactivate')"><i class="fa fa-ban"></i></button>
								<button v-else  type="button" class='btn btn-primary' v-on:click="activate(user)" :title="$t('buttons.activate')"><i class="fa fa-thumbs-o-up"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-12 text-center">
			<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
		</div>
	</div>
</template>