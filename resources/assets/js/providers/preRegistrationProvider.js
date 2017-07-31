var PreRegistrationProvider = {
	get: function(id) {
		return axios.get('/api/admin/users/teachers/pre-registration/'+id);
	},
	create: function (data) {
		return axios.post('/api/admin/users/teachers/pre-registration', data);
	},
	update: function (data, id) {
		return axios.put('/api/admin/users/teachers/pre-registration/'+id, data);
	},
	list: function(data) {
		return axios.get('/api/admin/users/teachers/pre-registration', {"params":data});
	},
	sendEmail: function(id) {
		return axios.post('/api/admin/users/teachers/pre-registration/'+id+'/send-email');
	}
};
export { PreRegistrationProvider };