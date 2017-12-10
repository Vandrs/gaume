var CouponProvider = {
	create: function (data) {
		return axios.post('/api/admin/coupons', data);
	},
	getAll: function (data) {
		return axios.get('/api/admin/coupons', {'params': data});	
	}
};

export { CouponProvider };