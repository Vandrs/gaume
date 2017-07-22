var AppErrorBag = {
	parseErrors: function (status, data) {	
		if (!data) {
			return null;
		}
		var arrStatus = [401, 404, 500];
		if (arrStatus.indexOf(status) >= 0) {
			return [data.msg];
		} else if (status == 400) {
			return data.errors;
		}
		return null;
	}
};

export { AppErrorBag };