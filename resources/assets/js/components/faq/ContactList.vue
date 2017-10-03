<script>
	import { ContactProvider } from '../../providers/contactProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data() {
			return {
				filters: {
					name: null,
					type: null,
					page: 1
				},
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				},
				contacts: []
			}
		},
		mounted() {
			this.listContacts();
		},
		methods: {
			listContacts: function() {
				ContactProvider.list(this.filters)
							   .then((response) => {
							   		console.log('RESPONSE OK');
							   		this.contacts = response.data.data;
							   		console.log(this.contacts);
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
			search: function () {
				this.filters.page = 1;
				this.listContacts();
			},
			paginate: function (page) {
				this.filters.page = page;
				this.listContacts();
			},
			clear: function () {
				this.filters.page = 1;
				this.filters.name = null;
				this.filters.type = null;
				this.listContacts();
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
					<i class="fa fa-envelope fa-stack-1x fa-inverse"></i>
			</span>
		</div>
		<div class="col-xs-12 text-center">
			<h1>{{$t('faq.messages')}}</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-3 control-group">
			<label>{{$t('profile.name')}}</label>
			<input  type="text" v-model="filters.name" name="teacher" class="form-control">
		</div>
		<div class="col-xs-12 col-md-3 control-group">
			<label>{{$t('faq.type')}}</label>
			<select class="form-control" name="status" v-model="filters.type">
				<option value=""></option>
				<option value="1">{{$t('faq.types.1')}}</option>
				<option value="2">{{$t('faq.types.2')}}</option>
				<option value="3">{{$t('faq.types.3')}}</option>
				<option value="4">{{$t('faq.types.4')}}</option>
			</select>
		</div>
		<div class="col-xs-12 col-md-3">
			<label class="block-label">&nbsp;</label>
			<button v-on:click="search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
			<button v-on:click="clear" class="btn btn-default"><i class="fa fa-eraser"></i></button>
		</div>
	</div>
	<div class="row margin-top-20">
		<div class="col-xs-12">
			<table class="table table-default table-bordered table-striped">
				<thead>
					<tr>
						<th>{{$t('profile.name')}}</th>
						<th>{{$t('profile.email')}}</th>
						<th>{{$t('faq.type')}}</th>
						<th>{{$t('faq.message')}}</th>
					</tr>
				</thead>
				<tbody v-if="contacts.length > 0">
					<tr v-for="contact of contacts">
						<td>{{contact.name}}</td>
						<td>{{contact.email}}</td>
						<td>{{contact.type_label}}</td>
						<td>{{contact.comment}}</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="4">{{$t('app.noRegisterFound')}}</td>
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