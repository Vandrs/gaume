var NeighborhoodProvider = {
	list: function (uf, params) {
		return axios.get('/api/neighborhoods/'+uf,{"params":params});
	},
}

export { NeighborhoodProvider };