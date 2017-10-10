var MessageProvider = {
	createThread: function(data) {
		return axios.post('/api/message/threads', data);
	},
	getThreads: function(data)
	{
		return axios.get('/api/message/threads', {'params': data});
	}
};

export { MessageProvider };