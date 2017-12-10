<script>
	import { CouponProvider } from '../../providers/couponProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data() {
			return {
				coupons: [],
				filters: {
					dt_ini: null,
					dt_end: null,
					page: 1
				},
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				},
				errors: {}
			};
		},
		mounted(){
			this.getCoupons();
			setTimeout(() => {
				window.app.$on('coupon:created',() => {
					this.search();
				});	
			},1000);
		},
		methods: {
			search: function() {
				this.filters.page = 1;
				this.getCoupons();
			},
			clear: function() {
				this.filters.dt_ini = null;
				this.filters.dt_end = null;
				this.filters.page = 1;
				this.getCoupons();
			},
			getCoupons: function (){
				this.errors = {};
				CouponProvider.getAll(this.filters)
							  .then((response) => {
							  		this.coupons = response.data.data;
							  		this.pagination.currentPage = response.data.meta.pagination.current_page;
							  		this.pagination.totalPages = response.data.meta.pagination.total_pages;
							  })
							  .catch((error) => {
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
			},
			paginate: function(page) {
				this.filters.page = page;
				this.getCoupons();
			}
		}
	};
</script>
<template>
	<div class="row">
		<div class="col-xs-12 col-md-8 col-md-offset-2">
			<div class="row margin-top-10">
				<div class="col-xs-12">
					<h4>{{$t('coupon.list')}}</h4>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 col-md-3 control-group" v-bind:class="{'has-error':errors.dt_ini}">
					<label class="control-label" for="dt_ini">{{$t('coupon.valid_until')+": "+$t('app.from')}}</label>
					<input type="date" v-model="filters.dt_ini" class="form-control" id="dt_ini">
					<span v-if="errors.dt_ini" class="help-block">
                        <strong>{{ errors.dt_ini[0] }}</strong>
                    </span>
				</div>
				<div class="col-xs-12 col-md-3 control-group" v-bind:class="{'has-error':errors.dt_end}">
					<label class="control-label" for="dt_end">{{$t('app.to')}}</label>
					<input type="date" v-model="filters.dt_end" class="form-control" id="dt_end">
					<span v-if="errors.dt_end" class="help-block">
                        <strong>{{ errors.dt_end[0] }}</strong>
                    </span>
				</div>
				<div class="col-xs-12 col-md-3">
					<span class="block-label">&nbsp;</span>
					<button class="btn btn-primary" v-on:click="search"><i class="glyphicon glyphicon-search"></i></button>
					<button class="btn btn-default" v-on:click="clear"><i class="glyphicon glyphicon-erase"></i></button>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12">
					<table class="table table-default table-bordered table-striped">
						<thead>
							<tr>
								<th>{{$t('coupon.code')}}</th>
								<th>{{$t('coupon.coins')}}</th>
								<th>{{$t('coupon.valid_until')}}</th>
								<th>{{$t('coupon.use_limit')}}</th> 
								<th>{{$t('coupon.used_times')}}</th>
							</tr>
						</thead>
						<tbody v-if="coupons.length">
							<tr v-for="coupon of coupons">
								<td>{{coupon.code}}</td>
								<td>{{coupon.coins}}</td>
								<td>{{coupon.formated_valid_until}}</td>
								<td>{{coupon.use_limit}}</td>
								<td>{{coupon.used_times}}</td>
							</tr>
						</tbody>
						<tbody v-else>
							<tr>
								<td colspan="5">{{$t('app.noRegisterFound')}}</td>
							</tr>
						</tbody>
					</table>	
				</div>
				<div class="col-xs-12 text-center marin-top-10">
					<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
				</div>
			</div>
		</div>
	</div>
</template>