<script>
	import { WalletProvider } from '../../providers/walletProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	export default {	
		data() {
			return {
				wallet: {},
				code: null,
				showInput: false,
				errors: []
			}
		},
		mounted() {
			this.getWallet();
		},
		methods: {
			getWallet: function() {
				WalletProvider.get()
							.then((response) => {
								this.wallet = response.data;
							})
							.catch((error) => {
								var response = error.response;
								if (response.status == 400) {
									this.errors = response.data.errors;
									var locale = this.$i18n.locale;
									var msg = this.$i18n.messages[locale].app.defaultErrors;
									window.app.$emit('app:show-alert', [msg], "danger");
									window.scrollTo(0,0);
								} else {
									var errors = AppErrorBag.parseErrors(
									  				response.status,
									  				response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
								  	window.scrollTo(0,0);
								}
							});
			},
			parseMoney: function(value) {
				if (value) {
					return value.toFixed(2).toString().replace('.',',');
				}
				return '0,00';
			},
			showInputCode: function() {
				this.showInput = this.showInput ? false : true ;
			},
			sendCode: function() {
				this.errors = [];
				window.app.isLoading = true;
				console.log(this.code);
				WalletProvider.addCoupon(this.code)
							  .then((response) => {
							  		window.app.isLoading = false;
									var locale = this.$i18n.locale;
									var msg = this.$i18n.messages[locale].coupon.insert_success;
									window.app.$emit('app:show-alert', [msg], "success");
									window.scrollTo(0,0);
									this.getWallet();
							  })
							  .catch((error) => {
							  	window.app.isLoading = false;
							  	var response = error.response;
								if (response.status == 400) {
									this.errors = response.data.errors;
									var locale = this.$i18n.locale;
									var msg = this.$i18n.messages[locale].app.defaultErrors;
									window.app.$emit('app:show-alert', [msg], "danger");
									window.scrollTo(0,0);
								} else {
									var errors = AppErrorBag.parseErrors(
									  				response.status,
									  				response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger");
								  	window.scrollTo(0,0);
								}
							  });
			}
		}
	}
</script>
<template>
	<div class='row'>
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 text-center">
					<span class="fa-stack fa-lg">
							<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
							<i class="fa fa-credit-card-alt fa-stack-1x fa-inverse"></i>
					</span>
				</div>
				<div class="col-xs-12 text-center">
					<h1>{{$t('wallet.wallet')}}</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="pull-left">
						<span class="img-coins">
						</span>
					</div>
					<div class="pull-left margin-right-10">
						<h4 class="credit-title">{{$t('wallet.your')}} <strong>{{$t('wallet.credit')}}</strong></h4>
						<strong>{{parseMoney(wallet.amount)}}</strong> {{$t('wallet.coins')}}
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="col-xs-12">
					<div class="margin-right-10">
						<button class='btn btn-default' v-on:click="showInputCode"><i class="fa fa-credit-card"></i> {{$t('wallet.insert')}}</button>
					</div>
				</div>
			</div>
			<div class='row margin-top-20' v-bind:class="{'hidden' : !showInput}">
				<div class="col-xs-12 form-group" v-bind:class="{'has-error':errors.code}">
					<div class='row'>
						<div class='col-xs-12 col-md-4'>
							<label class="control-label" for="coupon">{{$t("wallet.insert_here")}}</label>
							<input type="text" id="coupon" class="form-control" v-model="code" maxlength="255" >
							<span v-if="errors.code" class="help-block">
		                        <strong>{{ errors.code[0] }}</strong>
		                    </span>
						</div>
						<div class='col-xs-12 col-md-4'>
							<label class='block-label'>&nbsp;</label>
							<button class="btn btn-primary" v-on:click="sendCode"><i class="fa fa-thumbs-up"></i> {{$t('buttons.confirm')}}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>