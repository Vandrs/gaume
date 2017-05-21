var LessonProvider = {

	get: function (id) {
		return axios.get('/api/lessons/'+id+'?includes=teacher,student,periods');
	},

	list: function (params) {

	},

	create: function (teacherId) {
		return axios.post('/api/lessons',{'teacher_id': teacherId});
	},

	confirm: function (lessonId, confirm) {
		return axios.patch('/api/lessons/'+lessonId, {'confirmed': confirm});
	},

};

export { LessonProvider };