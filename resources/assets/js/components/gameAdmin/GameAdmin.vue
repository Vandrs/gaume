<script>
	export default {
		props: ['id'],
		data () {
			return {
				game: {},
				errors: {}
			}
		}
	}
</script>
<template>
	<div>
		<div class="row">
			<div class="col-xs-12">
				PHOTO
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 control-group margin-top-10" v-bind:class="{'has-error':errors.title}">
				<label for="name">{{$t('game.title')}}</label>
				<input type="text" class='form-control' name="name" v-model="game.name" id="name">
				<span v-if="errors.title" class="help-block">
					<strong>{{errors.title[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 control-group margin-top-10" v-bind:class="{'has-error':errors.description}">
				<label for="description">{{$t('game.description')}}</label>
				<textarea class='form-control' name="description" v-model="game.description" id="description"></textarea>
				<span v-if="errors.description" class="help-block">
					<strong>{{errors.description[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 control-group margin-top-10">
				<label for="developer_site">{{$t('game.developer_site')}}</label>
				<div class="input-group">
					<span class="input-group-addon">http(s)://</span>
					<input type="text" class='form-control' name="name" v-model="game.developer_site" id="developer_site">
				</div>
				<span v-if="errors.developer_site" class="help-block">
					<strong>{{errors.developer_site[0]}}</strong>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 control-group margin-top-10">
				<label for="status">{{$t('game.status')}}</label>
			</div>
		</div>
	</div>
</template>
