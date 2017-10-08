var MessageProvider = {
	createThread: function(data) {
		return axios.post('/api/message/thread', data);
	}
};

export { MessageProvider };