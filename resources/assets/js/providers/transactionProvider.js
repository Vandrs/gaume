var TransactionProvider = {
	list: function (params) {
		return axios.get('/api/transactions', {"params":params});
	}
};

export { TransactionProvider };