var LessonProvider = {

	get: function (id) {
		return axios.get('/api/lessons/'+id+'?includes=teacher,student,periods');
	},

	list: function (params) {
		return axios.get('/api/lessons',{"params":params});
	},

	create: function (data) {
		return axios.post('/api/lessons', data);
	},

	confirm: function (lessonId, confirm) {
		return axios.patch('/api/lessons/'+lessonId, {'confirmed': confirm});
	},

};

export { LessonProvider };