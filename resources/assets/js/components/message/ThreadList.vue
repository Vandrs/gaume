<script>
	import { MessageProvider } from '../../providers/messageProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { AppRoles } from '../../components/shared/AppRoles';
	import { Pagination } from 'uiv';
	export default {
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
			getAllThreads() {
				this.filter.mode = this.allThreads;
				this.filter.page = 1;
				this.getThreads();
			},
			getUnreadThreads() {
				this.filter.mode = this.unreadThreads;
				this.filter.page = 1;
				this.getThreads();		
			},
			paginate(page) {
				this.filter.page = page;
				this.getThreads();		
			}
		}
	}
</script>
<template>
	<div class='row'>
		<div class='col-xs-12'>
			<div class="message-thread-list">
				<div v-for="thread of threads" class="message-thread">
					<h2>{{thread.subject}}</h2>
				</div>
			</div>
		</div>
	</div>
</template>