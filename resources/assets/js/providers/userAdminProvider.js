var UserAdminProvider = {
	get: function () {
		return axios.get('/api/me');
	}
};

export { UserAdminProvider };