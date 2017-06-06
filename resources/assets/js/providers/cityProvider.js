var CityProvider = {
	list: function (uf, params) {
		return axios.get('/api/cities/'+uf,{"params":params});
	},
}

export { CityProvider } ;