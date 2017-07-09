var PlatformProvider = {
	list: function () {
		return axios.get('/api/platforms', {});
	}
};

export { PlatformProvider };