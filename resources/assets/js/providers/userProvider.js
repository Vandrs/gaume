var UserProvider = {
	me: function () {
		return axios.get('/api/me');
	},
	updateProfile: function (data) {
		return axios.post('/api/me', data);
	}
};

export { UserProvider };