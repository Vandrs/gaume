var StateProvider = {
	list: function () {
		return axios.get('/api/states',{});
	},
}

export { StateProvider };