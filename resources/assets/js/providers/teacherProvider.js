var TeacherProvider = {
	list: function (data) {
		return axios.get('/api/teachers', {'params': data});
	}
};

export { TeacherProvider };