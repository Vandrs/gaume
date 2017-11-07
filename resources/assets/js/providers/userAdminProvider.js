var UserAdminProvider = {
	getAll: function (data) {
		return axios.get('/api/admin/users', {'params':data});
	},
	activate: function (id) {
		return axios.put('/api/admin/users/'+id+'/activate');
	},
	inactivate: function (id) {
		return axios.put('/api/admin/users/'+id+'/inactivate');
	}
};

export { UserAdminProvider };