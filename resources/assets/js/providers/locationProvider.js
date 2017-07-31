var LocationProvider = {
	get: function (cep) {
		return axios.get('/api/address/'+cep, {});
	}
};

export { LocationProvider };