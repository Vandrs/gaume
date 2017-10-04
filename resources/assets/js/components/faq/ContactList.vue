<script>
	import { ContactProvider } from '../../providers/contactProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { Pagination, Modal } from 'uiv';
	export default {
		components: { Pagination, Modal },
		data() {
			return {
				urlLogo: window.Laravel.baseUrl + "/images/logo.png",
				filters: {
					name: null,
					type: null,
					page: 1,
					status: null
				},
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				},
				contacts: [],
				selectedId: null,
				showModal: false
			}
		},
		mounted() {
			this.listContacts();
		},
		methods: {
			listContacts: function() {
				ContactProvider.list(this.filters)
							   .then((response) => {
							   		this.contacts = response.data.data;
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
				this.filters.status = null;
				this.listContacts();
			},
			markAsRead: function(id) {
				window.app.isLoading = true;
				ContactProvider.setRead(id)
							   .then((response) => {
							   		this.listContacts();
							   		window.app.isLoading = false;
							   })
							   .catch((error) => {
							   		window.app.isLoading = false;
							   		var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
							   });
			},
			closeButton: function(evt) {
				evt.preventDefault();
				this.showModal = false;
				this.selectedId = false;
			},
			confirmButton: function(evt) {
				evt.preventDefault();
				ContactProvider.delete(this.selectedId)
							   .then((response) => {
							   		this.listContacts();
							   		window.app.isLoading = false;
							   		this.showModal = false;
							   })
							   .catch((error) => {
							   		window.app.isLoading = false;
							   		var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
								  	this.showModal = false;
							   });
				window.app.isLoading = true;
			},
			openModal: function (id) {
				this.selectedId = id;
				this.showModal = true;
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
			<label for="teacher">{{$t('profile.name')}}</label>
			<input id="teacher"  type="text" v-model="filters.name" name="teacher" class="form-control">
		</div>
		<div class="col-xs-12 col-md-3 control-group">
			<label for="type">{{$t('faq.type')}}</label>
			<select class="form-control" id="type" name="type" v-model="filters.type">
				<option value=""></option>
				<option value="1">{{$t('faq.types.1')}}</option>
				<option value="2">{{$t('faq.types.2')}}</option>
				<option value="3">{{$t('faq.types.3')}}</option>
				<option value="4">{{$t('faq.types.4')}}</option>
			</select>
		</div>
		<div class="col-xs-12 col-md-3 control-group">
			<label for="status">{{$t('faq.status')}}</label>
			<select class="form-control" id="status" name="status" v-model="filters.status">
				<option value=""></option>
				<option value="0">{{$t('faq.message_not_read')}}</option>
				<option value="1">{{$t('faq.message_read')}}</option>
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
			<table class="table table-default table-bordered table-striped" id="table-messages">
				<thead>
					<tr>
						<th>{{$t('profile.name')}}</th>
						<th>{{$t('profile.email')}}</th>
						<th>{{$t('faq.type')}}</th>
						<th>{{$t('faq.message')}}</th>
						<th>{{$t('buttons.actions')}}</th>
					</tr>
				</thead>
				<tbody v-if="contacts.length > 0">
					<tr v-for="contact of contacts" v-bind:class="{'read':contact.status}">
						<td>{{contact.name}}</td>
						<td>{{contact.email}}</td>
						<td>{{contact.type_label}}</td>
						<td>{{contact.comment}}</td>
						<td>
							<button v-if="!contact.status" v-on:click="markAsRead(contact.id)" class="btn btn-default" :title="$t('buttons.mark_as_read')"><i class="fa fa-check-square-o"></i></button>
							<button class="btn btn-danger" v-on:click="openModal(contact.id)" :title="$t('buttons.delete')"><i class="fa fa-trash"></i></button>
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
		<div class="col-xs-12 text-center">
			<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
		</div>
	</div>	
	<modal  v-model="showModal" :header="false" :footer="false" >
			<div slot="default">
				<div class="row">
					<div class="col-xs-12 text-center">
						<img class="modal-logo" :src="urlLogo" title="Logo Monzy" alt="Logo Monzy">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 text-center">
						<h2>{{$t('modal.warning')}}</h2>
					</div>
				</div>
				<div class="row margin-top-20">
					<div class="col-xs-12">
						{{$t('app.acceptExecuteAction')}}
					</div>
				</div>
				<div class="row margin-top-20">
					<div class="col-xs-12 text-right">
						<button type="button" class="btn btn-default" v-on:click="closeButton">{{$t('modal.cancelText')}}</button>
						<button type="button" class="btn btn-primary" v-on:click="confirmButton">{{$t('modal.okText')}}</button>
					</div>
				</div>
			</div>
		</modal>	
</div>
</template>
<style>
	#table-messages tr.read td {
		background-color: #CCCCCC;
	} 
</style>