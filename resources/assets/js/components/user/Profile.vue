<script>
	import { UserProvider } from '../../providers/userProvider';
	export default {
		data() {
			return { 
				user: {},
				errors: null,
				defaultError: null
			}
		},
		mounted() {
			this.getUser();
		},
		methods: {
			getUser: function () {
				UserProvider.me().then((response) => {
					this.user = response.data;
					console.log(this.user);
				});
			}
		}
	}
</script>
<template>
	<div class="row">
		<div class="col-xs-12">
		<div class="row">
            <div class="col-xs-12 col-md-2 col-md-offset-2 margin-bottom-10">
                <div class="img-profile-content">
                </div>
                <div>
                    <input type="file" name="photo_profile" id="photo_profile" class="hidden"/>
                    <button id="photoSelect" class="btn btn-primary"><i class="glyphicon glyphicon-picture"></i> {{$t('profile.profile_image')}}</button>
                </div>
                <div class="form-group">
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="nickname" class="control-label">{{$t('profile.nickname')}}*</label>
                            <input id="nickname" type="text" class="form-control" v-model="user.nickname" name="nickname">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="name" class="control-label">{{$t('profile.name')}}*</label>
                            <input id="name" type="text" class="form-control" v-model="user.name" name="name" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
	</div>
</template>