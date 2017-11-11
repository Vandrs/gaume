var LessonEvaluationProvider = {
	get: function(id) {
		return axios.get('/api/lesson-evaluations/'+id);
	},
	getNotes: function(id, page) {
		var params = {'page': page};
		return axios.get('/api/admin/users/'+id+'/evaluations', {'params': params});
	},
	evaluate: function(id, data) {
		return axios.put('/api/lesson-evaluations/'+id, data);
	}
};

export { LessonEvaluationProvider };