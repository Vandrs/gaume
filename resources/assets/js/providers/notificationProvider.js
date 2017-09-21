var NotificationProvider = {

	get: function(limit) {
		var params = {'limit':limit};
		return axios.get('/api/notifications', {'params':params});
	},

	delete: function(id) {
		return axios.patch('/api/notifications/'+id+'/read');
	},

	deleteAll: function(){
		return axios.post('/api/notifications/mark-all-read');	
	}
};

export { NotificationProvider };

