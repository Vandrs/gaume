<script>
	import { MessageProvider } from '../../providers/messageProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { AppRoles } from '../../components/shared/AppRoles';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data() {
			return {
				threads: [],
				allThreads: 'all',
				unreadThreads: 'unread',
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				},
				filter: {
					mode: null,
					page: 1
				}
			}
		},
		mounted() {
			this.filter.mode = this.unreadThreads;
			this.filter.page = 1;
			this.getThreads();
		},
		methods: {
			getThreads() {
				MessageProvider.getThreads(this.filter)
							   .then((response) => {
							   		this.threads = response.data.data;
							  		this.pagination.currentPage = response.data.meta.pagination.current_page;
							  		this.pagination.totalPages  = response.data.meta.pagination.total_pages;
							   })
							   .catch((error) => {	
							   		var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
							   });
			},
			getAllThreads(evt) {
				if (evt) {
					evt.preventDefault();
				}
				this.filter.mode = this.allThreads;
				this.filter.page = 1;
				this.getThreads();
			},
			getUnreadThreads(evt) {
				if (evt) {
					evt.preventDefault();
				}
				this.filter.mode = this.unreadThreads;
				this.filter.page = 1;
				this.getThreads();		
			},
			paginate(page) {
				this.filter.page = page;
				this.getThreads();		
			},
			selectThread(thread) {
				this.$emit('thread-selected', thread);
			},
			deleteThread(thread) {
				MessageProvider.deleteThread(thread.id)
							   .then((data) => {
									this.$emit('thread-deleted', thread);				   		
									this.filter.page = 1;
									this.getThreads();
							   })
							   .catch((error) => {
							   		var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
							   });
			}
		}
	}
</script>
<template>
	<div class='row'>
		<div class='col-xs-12'>
			<div class="message-thread-list">
				<div class="thread-list-controls margin-bottom-10">
					<a href="#" v-on:click="getUnreadThreads" v-bind:class="{'selected':filter.mode == unreadThreads}">{{$t('messages.unread_only')}}</a> | 
					<a href="#" v-on:click="getAllThreads" v-bind:class="{'selected':filter.mode == allThreads}">{{$t('messages.see_all')}}</a>
				</div>
				<div v-for="thread of threads" class="message-thread margin-bottom-10" v-bind:class="{'unred':!thread.is_read}" v-on:click="selectThread(thread)">
					<div class='thread-hour text-right'>
						{{thread.updated_at_text}}
					</div>
					<div class="img-content">
						<img v-if="thread.last_message.user.photo" :src="thread.last_message.user.photo">
						<icon v-else class="glyphicon glyphicon-user"></icon>
					</div>
					<div class="thread-body">
						<h2>{{thread.participants}}</h2>
						<div class='thread-message'>
							{{thread.last_message.truncated_message}}
						</div>
					</div>
					<div class="thread-actions text-right">
						<a href="#" v-on:click="deleteThread(thread)" v-bind:class="{'selected':filter.mode == allThreads}">{{$t('buttons.delete')}}</a>
					</div>
				</div>
				<div v-if="threads.length == 0">
					<span v-if="filter.mode == allThreads">{{$t('messages.no_messages')}}</span>
					<span v-else>{{$t('messages.no_unread_messages')}}</span>
				</div>
				<div class="col-xs-12 text-center">			
					<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
				</div>
			</div>
		</div>
	</div>
</template>