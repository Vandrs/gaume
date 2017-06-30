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
		var config = {
            headers: { 'content-type': 'multipart/form-data' }
        };
		return axios.post('/api/admin/game', formData, config);
	}
};


export { GameProvider };