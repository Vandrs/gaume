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
			},
			save: function(evt) {
				evt.preventDefault();
				TeacherGameProvider.update(this.teacherGames)
								   .then((response) => {
								   		this.getGames();
								   		window.app.isLoading = false;
								   		var locale = this.$i18n.locale;
										var msg = this.$i18n.messages[locale].game.multiple_update_success;
										window.app.$emit('app:show-alert', [msg], "success");
										window.scrollTo(0,0);
								   })
								   .catch((error) => {
								   		window.app.isLoading = false;
								   		if (error.status == 400) {
								   			var locale = this.$i18n.locale;
											var msg = this.$i18n.messages[locale].game.generic_game_error;
											window.app.$emit('app:show-alert', [msg], "danger");
								   		} else {
								   			var response = error.response;
								   			var errors = AppErrorBag.parseErrors(
									  				response.status,
									  				response.data
									  			);
								  			window.app.$emit('app:show-alert', errors, "danger");	
								   		}
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
			<div class="row margin-top-10">
        		<div class="col-xs-12 text-left">
        			<button type="button" class="btn btn-primary" v-on:click="save"><i class="glyphicon glyphicon-floppy-disk"></i> {{$t('buttons.save')}}</button>
        		</div>
        	</div>
		</div>
	</div>
</template>