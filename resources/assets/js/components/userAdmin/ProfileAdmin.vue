<script>
	import { Tabs,Tab } from 'uiv';
	import { UserAdminProvider } from '../../providers/userAdminProvider';
	import { TeacherGameProvider } from '../../providers/teacherGameProvider';
	import { LessonEvaluationProvider } from '../../providers/lessonEvaluationProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { AppRoles } from '../../components/shared/AppRoles';
	export default {
		props: ['id'],
		components: { Tabs, Tab },
		data(){
			return {
				user: {
					address: {},
					media: {},
					role: {
						name: null
					}
				},
				medias: [
					{type:'DISCORD',label:'Discord'},
					{type:'SKYPE',label:'Skype'}
				],
				teacherGames: [],
				roles: AppRoles
			}
		},
		mounted() {
			this.getUser();
		},
		computed: {
			cpf: function() {
				if (this.user.cpf) {
					var part1 = this.user.cpf.substring(0,3);
					var part2 = this.user.cpf.substring(3,6);
					var part3 = this.user.cpf.substring(6,9);
					var part4 = this.user.cpf.substring(9,11);
					return part1+"."+part2+"."+part3+"-"+part4;
				}
				return null;
			},
			birth_date: function() {
				if (this.user.birth_date) {
					var dateTime =  this.user.birth_date.split(" ");
					return dateTime[0].split("-").reverse().join("/");
				}
				return null; 
			}
		},
		methods: {
			getUser: function () {	
				UserAdminProvider.get(this.id).then((userResponse) => {
					this.user = userResponse.data;
					this.getGames();
					this.getEvaluations();
				});	
			},
			getGames: function () {
				window.app.isLoading = true;
				TeacherGameProvider.getById(this.user.id)
								   .then((response) => {
								   		this.teacherGames = response.data;
								   		window.app.isLoading = false;
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
			getEvaluations: function () {
				window.app.isLoading = true;
				LessonEvaluationProvider.getNotes(this.user.id)
								   .then((response) => {
								   		console.log(response.data);
								   		window.app.isLoading = false;
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
	}
</script>
<template>
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 text-center">
					<span class="fa-stack fa-lg">
	  					<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
	  					<i class="fa fa-user fa-stack-1x fa-inverse"></i>
					</span>
				</div>
				<div class="col-xs-12 text-center">
					<h1>{{$t('app.profile')+': '+user.name}}</h1>
				</div>
			</div>
		</div>
        <div class="col-xs-12 col-md-8 col-md-offset-2 margin-top-20">
        	<tabs>
			    <tab title="<i class='glyphicon glyphicon-user'></i>" :html-title="true">
			    	<div class="row margin-top-20">
						<div class="col-xs-12">
							<div class="row">
					            <div class="col-xs-12 col-md-3 margin-bottom-10">
					                <div class="img-profile-content">
					                	<img v-if="user.photo_profile != ''" :src="user.photo_profile" title="imagem de perfil" alt="imagem de perfil">
					                </div>
					            </div>
					            <div class="col-xs-12 col-md-9">
					                <div class="row">
					                    <div class="col-xs-12">
					                        <div class="form-group">
					                            <label for="nickname" class="control-label">{{$t('profile.nickname')}}*</label>
					                            <input id="nickname" type="text" class="form-control" v-model="user.nickname" name="nickname" :disabled="true">
					                        </div>
					                    </div>
					                </div>
					                <div class="row">
				                        <div class="col-xs-12">
				                            <div class="form-group">
				                                <label for="media_type" class="control-label">{{$t('profile.media_type')}}({{user.media.nickname}})</label>
				                                <input id="media_type" type="text" class="form-control" v-model="user.media.media" name="media_type" :disabled="true">
				                            </div>
				                        </div>
				                    </div>
					            </div>
					        </div>

					        <div class='row'>
				                <div class="col-xs-12 margin-top-10">
				                    <span class="form-title">{{$t('profile.private_information')}}</span>
				                </div>
				            </div>

				            <div class="row">
				                <div class="col-xs-12 col-md-6">
				                	<div class="form-group">
				                        <label for="cpf" class="control-label">{{$t('profile.cpf')}}</label>
				                        <input id="cpf" type="text" class="form-control" name="cpf" :value="cpf"  :disabled="true">
				                    </div>
				                </div>
				                <div class="col-xs-12 col-md-6">
				                	<div class="form-group">
				                    	<label for="birth_date_text" class="control-label">{{$t('profile.birth_date')}}</label>
				                    	<input id="birth_date" type="text" class="form-control" name="birth_date" :value="birth_date" :disabled="true">
				                    </div>
				                </div>
				            </div>

				            <div class="row">    
				                <div class="col-xs-12 col-md-12">
				                    <div class="form-group">
				                        <label for="email" class="control-label">{{$t('profile.email')}}*</label>
				                        <input id="email" type="email" class="form-control" name="email" v-model="user.email" :disabled="true">
				                    </div>
				                </div>
				            </div>

				            <div class="row">
				                <div class="col-xs-12 col-md-12">
				                    <span class="help-block">
				                        {{$t('profile.address')}}
				                    </span>
				                </div>
				            </div>
				            <div class="row">
				            	<div class="col-xs-12 col-md-4">
				                    <div class="form-group">
				                        <label for="zipcode" class="control-label">{{$t('profile.zipcode')}}*</label>
				                        <input type="text" id="zipcode" name="zipcode" class="form-control" v-model="user.address.zipcode" v-mask="'#####-###'" :disabled="true">
				                    </div>
				                </div>
				                <div class="col-xs-12 col-md-4">
				                    <div class="form-group">
				                        <label for="state" class="control-label">{{$t('profile.state')}}*</label>
				                        <input type="text" id="state" name="state" class="form-control" v-model="user.address.state" readonly="">
				                    </div>
				                </div>
				                <div class="col-xs-12 col-md-4">
				                    <div class="form-group">
				                        <label for="city" class="control-label">{{$t('profile.city')}}*</label>
				                        <input type="text" id="city" name="city" class="form-control" v-model="user.address.city" readonly="">
				                    </div>
				                </div>
				            </div>
				            <div class="row">
				                <div class="col-xs-12 col-md-6">
				                    <div class="form-group">
				                        <label for="neighborhood" class="control-label">{{$t('profile.neighborhood')}}*</label>
				                        <input type="text" id="neighborhood" name="neighborhood" class="form-control" v-model="user.address.neighborhood" readonly="">
				                    </div>
				                </div>
				                <div class="col-xs-12 col-md-6">
				                    <div class="form-group">
				                        <label for="street" class="control-label">{{$t('profile.street')}}*</label>
				                        <input id="street" type="text" name="street" v-model="user.address.street" class="form-control" :disabled="true">
				                    </div>
				                </div>
				            </div>
				            <div class="row">                        
				                <div class="col-xs-12 col-md-6">
				                    <div class="form-group">
				                        <label for="number" class="control-label">{{$t('profile.number')}}</label>
				                        <input id="number" type="text" name="number" v-model="user.address.number" class="form-control" :disabled="true">
				                    </div>
				                </div>
				                <div class="col-xs-12 col-md-6">
				                    <div class="form-group">
				                        <label for="complement" class="control-label">{{$t('profile.complement')}}</label>
				                        <input id="complement" type="text" name="complement" v-model="user.address.complement" class="form-control" :disabled="true">
				                    </div>
				                </div>
				            </div>

				            <div v-if="user.bankAccount">
					            <div class="row margin-top-10">
					                <div class="col-xs-12 col-md-12">
					                    <span class="form-title">{{$t('profile.bank_info')}}</span>
					                </div>
					            </div>

					            <div class="row">
					                <div class="col-xs-12 col-md-6" >
					                    <div class="form-group">
					                        <label for="bank">{{$t('profile.bank')}}*</label>
					                        <input type="text" name="bank" id="bank" class="form-control" v-model="user.bankAccount.bank" maxlength="100" :disabled="true">
					                    </div>
					                </div>
					                <div class="col-xs-12 col-md-6" >
					                    <div class="form-group">
					                        <label for="bank">{{$t('profile.agency')}}*</label>
					                        <input type="text" name="agency" id="agency" class="form-control" v-model="user.bankAccount.agency" maxlength="20" :disabled="true">
					                    </div>
					                </div>
					            </div>
					            <div class="row">
					                <div class="col-xs-12 col-md-6">
					                    <div class="form-group">
					                        <label for="account">{{$t('profile.account')}}*</label>
					                        <input type="text" name="account" id="account" class="form-control" v-model="user.bankAccount.account" maxlength="20" :disabled="true">
					                    </div>
					                </div>
					                <div class="col-xs-12 col-md-3" >
					                    <div class="form-group">
					                        <label for="digit">{{$t('profile.digit')}}*</label>
					                        <input type="text" name="digit" id="digit" class="form-control" v-model="user.bankAccount.digit" maxlength="2" :disabled="true">
					                    </div>
					                </div>
					            </div>
				            </div>
				        </div>
					</div>
			    </tab>
			    <tab title="<i class='fa fa-vcard-o'></i>" :html-title="true">
			      	<p>Profile tab.</p>
			    </tab>
			    <tab :disabled="user.role.name != roles.TEACHER" title="<i class='fa fa-gamepad'></i>" :html-title="true">
			    	<div class="row margin-top-20">
						<div class="col-xs-12">
							<div class="row margin-top-20">
								<div v-for="teacherGame of teacherGames" class="col-xs-12 col-md-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											{{teacherGame.game}}
										</div>
										<div class="panel-body">
											<div class="row margin-bottom-10">
												<div class="col-xs-12">
													<img class="game-img-container" :src="teacherGame.photo" :title="teacherGame.game" :alt="teacherGame.game" />
												</div>
											</div>
											<div class="row">
					                        	<div class='col-xs-12'>
					                            	<p>{{teacherGame.description}}</p>
					                        	</div>
					                    	</div>
					                    	<div class="row margin-top-10" v-for="platform of teacherGame.platforms">
					                    		<div class="col-xs-12">
					                    			<label class="control-label">{{$t('profile.nickname')}} {{platform.platform}}</label>
					                    			<p>{{platform.nickname}}</p>
					                    		</div>
					                    	</div>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
			    </tab>
			</tabs>
        </div>
	</div>
</template>