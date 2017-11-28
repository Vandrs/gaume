<script>
	export default {
		data() {
			return {
				coupon: {
					code: null,
					coins: null,
					valid_until: null,
					use_limit: null
				},
				errors: {},
			};
		},
		watch: {
			coupon: function(input) {
				input.coins 	= input.coins.__toString().replace(/\D/gi,"");
				input.use_limit = input.use_limit.__toString().replace(/\D/gi,"");
				this.coupon 	= input;
			}
		},
		methods: {
			submit: function() {
				console.log(this.coupon);
			},
			clear: function() {
				this.coupon.code;
				this.coupon.coins;
				this.coupon.valid_until;
				this.coupon.use_limit;
			}
		}
	};
</script>
<template>
	<div class="row">
		<div class="col-xs-12 col-md-8 col-md-offset-2">
			<div class="row">
				<div class="col-xs-12 text-center">
					<span class="fa-stack fa-lg">
	  					<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
	  					<i class="fa fa-credit-card fa-stack-1x fa-inverse"></i>
					</span>
				</div>
				<div class="col-xs-12 text-center">
					<h1>{{$t('app.coupon')}}</h1>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12">
					<h4>{{$t('coupon.new')}}</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-6 margin-top-10 form-group" v-bind:class="{'has-error':errors.code}">
					<label for="code">{{$t('coupon.code')}}</label>
					<input type="text" id="code" class="form-control" v-model="coupon.code" maxlength="255">
					<span v-if="errors.code" class="help-block">
                        <strong>{{ errors.code[0] }}</strong>
                    </span>
				</div>
				<div class="col-xs-12 col-md-6 margin-top-10 form-group" v-bind:class="{'has-error':errors.coins}">
					<label for="code">{{$t('coupon.coins')}}</label>
					<input type="number" id="coins" class="form-control" v-model="coupon.coins" step="1" min="0">
					<span v-if="errors.coins" class="help-block">
                        <strong>{{ errors.coins[0] }}</strong>
                    </span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-6 margin-top-10 form-group" v-bind:class="{'has-error':errors.valid_until}">
					<label for="valid_until">{{$t('coupon.valid_until')}}</label>
					<input type="date" id="valid_until" class="form-control" v-model="coupon.valid_until">
					<span v-if="errors.valid_until" class="help-block">
                        <strong>{{ errors.valid_until[0] }}</strong>
                    </span>
				</div>
				<div class="col-xs-12 col-md-6 margin-top-10 form-group" v-bind:class="{'has-error':errors.use_limit}">
					<label for="use_limit">{{$t('coupon.use_limit')}}</label>
					<input type="number" id="use_limit" class="form-control" v-model="coupon.use_limit" step="1" min="0">
					<span v-if="errors.use_limit" class="help-block">
                        <strong>{{ errors.use_limit[0] }}</strong>
                    </span>
				</div>				
			</div>
			<div class="row">
				<div class="col-xs-12 margin-top-10">
					<button type="button" class="btn btn-primary" v-on:click="submit"><i class="glyphicon glyphicon-floppy-disk"></i> {{$t('buttons.save')}}</button>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12">
					<h4>{{$t('coupon.list')}}</h4>
				</div>
			</div>
		</div>
	</div>
</template>