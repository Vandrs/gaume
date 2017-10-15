var MessageProvider = {
	createThread: function(data) {
		return axios.post('/api/message/threads', data);
	},
	getThreads: function(data)
	{
		return axios.get('/api/message/threads', {'params': data});
	},
	getThread: function(id)
	{
		return axios.get('/api/message/threads/'+id);
	},
	deleteThread: function(id) {
		return axios.delete('/api/message/threads/'+id);
	},
	updateThread: function(id, data) {
		return axios.put('/api/message/threads/'+id, data);
	},
	readThread: function(id) {
		return axios.put('/api/message/threads/'+id+'/read');
	},
	getMessages: function(id) {
		return axios.get('/api/message/threads/'+id+'/messages');
	}
};

export { MessageProvider };