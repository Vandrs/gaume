var TeacherProvider = {
	list: function (data) {
		return axios.get('/api/teachers', {'params': data});
	},
	get: function (id) {
		return axios.get('/api/teachers/'+id, {});	
	}
};

export { TeacherProvider };