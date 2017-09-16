var BillingProvider = {
	get : function(params) {
		return axios.get('/api/admin/billing', {'params': params});
	}	
};
export { BillingProvider };