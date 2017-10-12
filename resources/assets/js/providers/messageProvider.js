var MessageProvider = {
	createThread: function(data) {
		return axios.post('/api/message/threads', data);
	},
	getThreads: function(data)
	{
		return axios.get('/api/message/threads', {'params': data});
	},
	deleteThread: function(id) {
		return axios.delete('/api/message/threads/'+id);
	},
	updateThread: function(id, data) {
		return axios.put('/api/message/threads/'+id, data);
	}
};

export { MessageProvider };