<script>
	import { TeacherGameProvider } from '../../providers/teacherGameProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default {
		data() {
			return {
				teacherGames: [],
				errors: []
			}
		},
		mounted() {
			this.getGames();
		},
		methods: {
			getGames: function () {
				window.app.isLoading = true;
				TeacherGameProvider.get()
								   .then((response) => {
								   		this.teacherGames = response.data;
								   })
								   .catch((error) => {
								   		window.app.isLoading = false;
										var response = error.response;
								   		var errors = AppErrorBag.parseErrors(
									  				response.status,
									  				response.data
									  			);
								  		window.app.$emit('app:show-alert', errors, "danger");
								  		window.scrollTo(0,0);	
								   });
			}
		}
	};
</script>
<template>
	<div class="row">
		<div class="col-xs-12 col-md-8 col-md-offset-2">
			<div class="row">
				<div v-for="teacherGame of teacherGames" class="col-xs-12 col-md-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							{{teacherGame.game}}
						</div>
						<div class="panel-body">
							<div class="row">
	                        	<div class='col-xs-12'>
	                            	<label>{{$t('teacher_game.skill_descripion')}}</label>
	                            	<textarea class="form-control" name="teacher_game_description" v-model="teacherGame.description" rows="5"></textarea>
	                        	</div>
	                    	</div>
	                    	<div class="row" v-for="platform of teacherGame.platforms">
	                    		<div class="col-xs-12">
	                    			<label class="control-label">{{$t('profile.nickname')}} {{platform.platform}}</label>
	                    			<input type="text" class="form-control" v-model="platform.nickname">
	                    		</div>
	                    	</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</template>