var CouponProvider = {
	create: function (data) {
		return axios.post('/api/admin/coupons', data);
	}
};

export { CouponProvider };