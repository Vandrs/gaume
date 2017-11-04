var UserAdminProvider = {

	getAll: function (data) {
		return axios.get('/api/admin/users', {'params':data});
	}
	
};

export { UserAdminProvider };