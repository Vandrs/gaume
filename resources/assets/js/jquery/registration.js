
import { CityProvider } from '../providers/cityProvider';

require('./plugins/bootstrap3-typeahead');

var CityAutoComplete = {

	setup: function (cityInput, destInput) {
		cityInput.typeahead({
			source: {},
			autoSelect: true
		});
		this.bindChange(cityInput, destInput);
	},

	updateSource: function (cityInput, data) {
		cityInput.data('typeahead').source = data;
	},

	bindChange: function (cityInput, destInput) {
		cityInput.on('change', () => {
		  	var current = cityInput.typeahead("getActive");
		  	console.log(current);
		  	if (current && current.name.toLowerCase() == cityInput.val().toLowerCase()) {
			  	destInput.val(current.id);  		
		  	} else {
		  		destInput.val("");
		  	}
		  	
		});
	}

};


$(document).ready(() => {

	var cityInput = $('#city_name');
	var destCityInput = $('#city');
	var stateInput = $('#state');

	CityAutoComplete.setup(cityInput, destCityInput);

	stateInput.on('change', () => {
		var selectedState = stateInput.val();
		if (selectedState) {
			CityProvider.list(selectedState).then((response) => {
				CityAutoComplete.updateSource(cityInput, response.data);
			});
		}
	});

});