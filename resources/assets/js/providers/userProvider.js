var UserProvider = {
	me: function () {
		return axios.get('/api/me');
	},
	updateProfile: function (data) {
		return axios.post('/api/me', data);
	},
	updatePhotoProfile: function (file) {
		var formData = new FormData();
		formData.append('photo_profile', file, file.name);
		var config = {
            headers: { 'content-type': 'multipart/form-data' }
        };
		return axios.post('/api/me/photo', formData, config);
	}
};

export { UserProvider };