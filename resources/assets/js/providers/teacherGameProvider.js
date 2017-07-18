var TeacherGameProvider = {
	get: function (id) {
		return axios.get('/api/me/games');
	}
};
export { TeacherGameProvider };