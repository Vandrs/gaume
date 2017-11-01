<script>
	import { PreRegistrationProvider } from '../../providers/preRegistrationProvider';
	import * as moment from 'moment';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data() {
			return  {
				filter: {
					name: null,
					email: null,
					page: 1
				},
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				},

				preRegistrations: [],
				editLink: '/app/admin/usuarios/professor/pre-cadastro/',
				createLink: '/app/admin/usuarios/professor/pre-cadastro'
			}
		},
		mounted() {
			this.search();
		},
		methods: {
			paginate: function (page) {
				this.filter.page = page;
				this.search();
			},
			search: function() {
				PreRegistrationProvider.list(this.filter)
									   .then((response) => {
									   		window.app.$emit('app:close-alert');
									   		this.preRegistrations = response.data.data;
						  					this.pagination.currentPage = response.data.meta.pagination.current_page;
						  					this.pagination.totalPages = response.data.meta.pagination.total_pages;
									   })
									   .catch((error) => {
									   		window.app.$emit('app:close-alert');
									   		var errors = AppErrorBag.parseErrors(
								  				error.response.status,
								  				error.response.data
								  			);
							  				window.app.$emit('app:show-alert', errors, "danger"); 	
									   });
			},
			clearSearch: function(evt) {
				evt.preventDefault();	
				this.filter = {};
				this.filter.page = 1;
				this.search();
			},
			formatDate: function(strDate) {
				var date = moment(strDate);
				return date.format('D/M/YYYY HH:mm');;
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
						<i class="fa fa fa-id-card fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{ $t('pre_registration.pre_registration') }}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 text-left">
				<a class="btn btn-primary" :href="createLink"><i class="glyphicon glyphicon-plus-sign"></i> {{$t('buttons.add_new_teacher')}}</a>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-4 margin-top-10">
				<label for="name">{{$t('profile.name')}}</label>
				<input type="text" name="name" id="name" v-model="filter.name" class="form-control">
			</div>
			<div class="col-xs-12 col-md-4 margin-top-10">
				<label for="email">{{$t('profile.email')}}</label>
				<input type="text" name="email" id="email" v-model="filter.email" class="form-control">
			</div>
			<div class="col-xs-12 col-md-4 margin-top-10">
				<label class="block-label">&nbsp;</label>
				<button type="button" v-on:click="search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
				<button type="button" v-on:click="clearSearch" class="btn btn-default"><i class="fa fa-eraser"></i></button>
			</div>
		</div>
		<div class="row margin-top-10">
			<div class="col-xs-12 margin-top-10">
				<table id="tablePreRegistration" class="table table-default table-bordered table-striped">
					<thead>
						<th>{{$t('profile.name')}}</th>
						<th>{{$t('profile.email')}}</th>
						<th>{{$t('app.created_at')}}</th>
						<th>{{$t('app.actions')}}</th>
					</thead>
					<tbody v-if="preRegistrations.length">
						<tr v-for="preRegistration of  preRegistrations">
							<td>{{preRegistration.name}}</td>
							<td>{{preRegistration.email}}</td>
							<td>{{formatDate(preRegistration.created_at)}}</td>
							<td>
								<a :href="editLink+preRegistration.id" :title="$t('buttons.edit')" class="btn btn-default">
									<i class="glyphicon glyphicon-edit"></i>
								</a>
							</td>
						</tr>
					</tbody>
					<tbody v-else> 
						<tr>
							<td colspan="4">{{$t('app.noRegisterFound')}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 text-center">
				<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
			</div>
		</div>
	</div>
</template>

