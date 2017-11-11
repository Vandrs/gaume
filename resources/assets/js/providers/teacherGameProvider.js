var TeacherGameProvider = {
	getById: function (id) {
		return axios.get("api/teachers/"+id+"/games");
	},
	get: function () {
		return axios.get('/api/me/games');
	},
	update: function (data){
		return axios.put('/api/me/games', data);		
	},
	getLessonGames: function (id) {
		return axios.get('/api/teacher/games/'+id);
	}
};
export { TeacherGameProvider };