var PeriodProvider = {
	confirm: function (lessonId, periodId, confirm) {
		return axios.patch('/api/lessons/'+lessonId+'/periods/'+periodId, {'confirmed': confirm});
	},
	create: function (lessonId) {
		return axios.post('/api/lessons/'+lessonId+'/periods');
	}
};

export { PeriodProvider };