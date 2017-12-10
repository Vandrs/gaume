var WalletProvider = {
	get: function () {
		return axios.get('/api/me/wallet');
	},
	addCoupon: function (code) {
		return axios.put('/api/me/wallet/coupon', {'code': code});	
	}
};

export { WalletProvider };

	