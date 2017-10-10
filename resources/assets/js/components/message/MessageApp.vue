<script>
	Vue.component('thread-list', require('../../components/message/ThreadList'));
	export default {
		data() {
			return {
				currentThread: null
			}
		},
		methods: {
			setThread: function(thread) {	
				this.currentThread = thread;
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
  					<i class="fa fa-envelope-o fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{$t('messages.title')}}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-2 col-md-offset-2">
				<thread-list>
				</thread-list>
			</div>
			<div class="col-xs-12 col-md-6">

			</div>
		</div>
	</div>
</template>