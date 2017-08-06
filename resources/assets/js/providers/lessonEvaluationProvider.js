var LessonEvaluationProvider = {

	get: function(id) {
		return axios.get('/api/lesson-evaluations/'+id);
	},

	evaluate: function(id, data) {
		return axios.put('/api/lesson-evaluations/'+id, data);
	}
	
};

export { LessonEvaluationProvider };