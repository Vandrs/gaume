var GameProvider = {
	create: function (data, photo) {
		var formData = new FormData();
		if (photo) {
			formData.append('photo', photo, photo.name);	
		}
		formData.append('name', data.name);
		formData.append('description', data.description);
		formData.append('developer_site', data.developer_site);
		formData.append('status', data.status);
		for (var i = 0; i < data.platforms.length; i++) {
			formData.append('platforms[]',data.platforms[i]);
		}
		var config = {
            headers: { 'content-type': 'multipart/form-data' }
        };
		return axios.post('/api/admin/game', formData, config);
	},
	getAdmin: function(id) {	
		return axios.get('/api/admin/game/'+id);
	},
	update: function(id, data) {
		return axios.put('/api/admin/game/'+id, data);
	},
	updatePhoto: function(id, file) {
		var formData = new FormData();
		formData.append('photo', file, file.name);
		var config = {
            headers: { 'content-type': 'multipart/form-data' }
        };
		return axios.post('/api/admin/game/'+id+'/photo', formData, config);
	},
	delete: function(id) {
		return axios.delete('/api/admin/game/'+id);
	},
	listAdmin: function (data) {
		return axios.get('/api/admin/games',{"params":data});
	},
	listAdminAvailables: function () {
		return axios.get('/api/admin/games/availables');	
	}

};


export { GameProvider };