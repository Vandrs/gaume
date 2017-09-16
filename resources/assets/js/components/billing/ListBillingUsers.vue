<script>
	import { BillingProvider } from '../../providers/billingProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data() {
			return {
				users: [], 
				filters: {
					page: 1,
					name: null,
					dt_ini: null,
					dt_end: null
				},
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				}
			};
		},
		mounted() {
			this.getUsers(1);
		},
		methods: {
			getUsers: function(page) {
				console.log('Page', page);
				this.filters.page = page ? page : 1;
				BillingProvider.get(this.getFilters())
							   .then((response) => {
							   		this.users = response.data.data;
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
			paginate: function(page) {
				this.getUsers(page);		
			},
			getFilters: function() {
				var filters = {
					page: this.filters.page,
					name: this.filters.name,
					dt_ini: this.filters.dt_ini ? this.filters.dt_ini.split('/').reverse().join('-') : null,
					dt_end: this.filters.dt_end ? this.filters.dt_end.split('/').reverse().join('-') : null
				}
				return filters;
			},
			search: function () {
				this.getUsers(1);	
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
					<i class="fa fa-money fa-stack-1x fa-inverse"></i>
			</span>
		</div>
		<div class="col-xs-12 text-center">
			<h1>{{$t('app.billing')}}</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h2>{{$t('billing.lesson_history')}}</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-3 margin-top-10">
			<label for="name">{{$t('profile.name')}}</label>
			<input type="text" name="name" id="name" v-model="filters.name" class="form-control">
		</div>
		<div class="col-xs-12 col-md-3 margin-top-10">
			<label for="dt_ini">{{$t('app.from')}}</label>
			<input type="text" name="dt_ini" id="dt_ini" v-model="filters.dt_ini" v-mask="'##/##/####'" class="form-control">
		</div>
		<div class="col-xs-12 col-md-3 margin-top-10">
			<label for="dt_end">{{$t('app.to')}}</label>
			<input type="text" name="dt_end" id="dt_end" v-model="filters.dt_end" v-mask="'##/##/####'" class="form-control">
		</div>
		<div class="col-xs-12 col-md-3 margin-top-10">
			<label class="block-label">&nbsp;</label>
			<button type="button" class="btn btn-primary" v-on:click="search"><i class="fa fa-search"></i></button>
		</div>
	</div>
	<div class="row margin-top-10">
		<div class="col-xs-12">
			<table class="table table-default table-bordered table-striped">
				<thead>
					<tr>
						<th>{{$t('profile.name')}}</th>
						<th>{{$t('profile.cpf')}}</th>
						<th>{{$t('profile.email')}}</th>
						<th>{{$t('billing.qtd_lessons')}}</th>
						<th>{{$t('billing.qtd_hours')}}</th>
						<th>{{$t('billing.value')}}</th>
						<th>{{$t('profile.bank_info')}}</th>
					</tr>
				</thead>
				<tbody v-if="users.length > 0">
					<tr v-for="user in users">
						<td>{{user.name}}</td>
						<td>{{user.cpf}}</td>
						<td>{{user.email}}</td>
						<td>{{user.lessons_info.qtd_lessons}}</td>
						<td>{{user.lessons_info.hours}}</td>
						<td>{{user.lessons_info.formated_value}}</td>
						<td>
							{{$t('profile.bank')}} {{user.bankAccount.bank}},
							{{$t('profile.agency')}} {{user.bankAccount.agency}},
							{{$t('profile.account')}} {{user.bankAccount.account}} - {{user.bankAccount.digit}}
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="7">{{$t('app.noRegisterFound')}}</td>
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