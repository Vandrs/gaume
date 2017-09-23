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
	},
	online: function(id) {
		return axios.post('/api/users/'+id+'/online');
	},
	offline: function(id) {
		return axios.post('/api/users/'+id+'/offline');
	}
};

export { UserProvider };