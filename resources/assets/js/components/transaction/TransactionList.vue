<script>
	import { TransactionProvider } from '../../providers/transactionProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data() {
			return {
				transactions: [],
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				}
			}
		},
		mounted() {
			this.getTransactions({});
		},
		methods: {
			getTransactions: function (data) {
				TransactionProvider.list(data)
							.then((response) => {
								this.transactions = response.data.data;
							  	this.pagination.currentPage = response.data.meta.pagination.current_page;
							  	this.pagination.totalPages = response.data.meta.pagination.total_pages;
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
			paginate: function (page) {
				var filters = {};
				filters.page = page;
				this.getTransactions(filters);
			}
		}
	};
</script>
<template>
	<div>
		<div class="row margin-top-20">
			<div class='col-xs-12'>
				<h2>{{$t('wallet.transaction_history')}}</h2>
			</div>
		</div>
		<div class="row margin-top-20">
			<div class="col-xs-12">
				<table class="table table-default table-bordered table-striped">
					<thead>
						<tr>
							<th>{{$t('wallet.code')}}</th>
							<th>{{$t('wallet.product')}}</th>
							<th>{{$t('wallet.status')}}</th>
							<th>{{$t('wallet.last_event')}}</th>
						</tr>
					</thead>
					<tbody v-if="transactions.length > 0">
						<tr v-for="transaction in transactions">
							<td>{{transaction.code}}</td>
							<td>{{transaction.monzyPoint.description}}</td>
							<td>{{transaction.status_label}}</td>
							<td>{{transaction.last_event}}</td>
						</tr>
					</tbody>
					<tbody v-else>
						<tr>
							<td colspan="5">{{$t('app.noRegisterFound')}}</td>
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