var LessonEvaluationProvider = {
	get: function(id) {
		return axios.get('/api/lesson-evaluations/'+id);
	},
	getNotes: function(id) {
		return axios.get('/api/admin/users/'+id+'/evaluations');
	},
	evaluate: function(id, data) {
		return axios.put('/api/lesson-evaluations/'+id, data);
	}
};

export { LessonEvaluationProvider };