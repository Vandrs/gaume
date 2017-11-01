<script>
	export default {
		data() {
			return {
				users:[],
				createLink: '/app/admin/usuarios/professor/pre-cadastro',
				viewLink: ""
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
						<i class="fa fa fa-users fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="col-xs-12 text-center">
				<h1>{{ $t('app.users') }}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 text-left">
				<a class="btn btn-primary" :href="createLink"><i class="glyphicon glyphicon-plus-sign"></i> {{$t('buttons.add_new_teacher')}}</a>
			</div>
		</div>
		<div class="row margin-top-10">
			<div class="col-xs-12">
				<table class='table table-default table-bordered table-striped'>
					<thead>
						<tr>
							<th>{{$t('user_admin.name')}}</th>
							<th>{{$t('user_admin.nickname')}}</th>
							<th>{{$t('user_admin.email')}}</th>
							<th>{{$t('user_admin.role')}}</th>
							<th>{{$t('user_admin.status')}}</th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="users.length == 0">
							<td colspan="5">{{$t('app.noRegisterFound')}}</td>
						</tr>
						<tr v-else>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>