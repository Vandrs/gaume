var ContactProvider = {
	guestContact : function (data) {
		return axios.post('/api/contact', data);
	},
	createContact: function (data) {
		return axios.post('/api/faq-contact', data);	
	},
	list: function (data) {
		return axios.get('/api/admin/contacts',{params:data});
	},
	delete: function (id) {
		return axios.delete('/api/admin/contacts/'+id);
	},
	setRead: function (id) {
		return axios.post('/api/admin/contacts/'+id+'/read');
	}
};
export { ContactProvider };