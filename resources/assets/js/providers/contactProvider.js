var ContactProvider = {
	guestContact : function (data) {
		return axios.post('/api/contact', data);
	}
};
export { ContactProvider };