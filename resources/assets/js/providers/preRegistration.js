var PreRegistration = {
	create: function (data) {
		return axios.post('/api/admin/users/teachers/pre-registration', data);
	}
};
export { PreRegistration };