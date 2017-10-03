var ContactProvider = {
	guestContact : function (data) {
		return axios.post('/api/contact', data);
	},
	createContact: function (data) {
		return axios.post('/api/faq-contact', data);	
	},
	list: function (data) {
		return axios.get('/api/admin/contacts',{params:data});
	}
};
export { ContactProvider };