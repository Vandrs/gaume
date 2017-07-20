var TeacherGameProvider = {
	get: function (id) {
		return axios.get('/api/me/games');
	},
	update: function (data){
		return axios.put('/api/me/games', data);		
	}
};
export { TeacherGameProvider };