var UserProvider = {
	me: function () {
		return axios.get('/api/me');
	}
};

export { UserProvider };