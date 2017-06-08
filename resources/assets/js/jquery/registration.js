require('./plugins/bootstrap3-typeahead');
require('./plugins/jquery-mask');
require('./plugins/bootstrap-datepicker');
require('./locales/bootstrap-datepicker.pt-BR');

import { CityProvider } from '../providers/cityProvider';
import { NeighborhoodProvider } from '../providers/neighborhoodProvider';

var CityAutoComplete = {
	cityInput: null,
	setup: function (cityInput, destInput, state) {
		this.cityInput = cityInput;
		if (state) {
			CityProvider.list(selectedState).then((response) => {
				this.cityInput.typeahead({
					source: response.data,
					autoSelect: true
				});
				this.bindChange(destInput);	
			});
		} else {
			this.cityInput.typeahead({
				source: {},
				autoSelect: true
			});
			this.bindChange(destInput);	
		}
	},
	updateSource: function (data) {
		this.cityInput.data('typeahead').source = data;
	},
	bindChange: function (destInput) {
		this.cityInput.on('change', () => {
		  	var current = this.cityInput.typeahead("getActive");
		  	if (current && current.name.toLowerCase() == this.cityInput.val().toLowerCase()) {
			  	destInput.val(current.id);  		
		  	} else {
		  		destInput.val("");
		  	}	  	
		});
	}
};

var NeighborhoodAutoComplete = {
	neighborhoodInput: null,
	setup: function (neighborhoodInput, destInput, state) {
		this.neighborhoodInput = neighborhoodInput;
		if (state) {
			NeighborhoodProvider.list(selectedState).then((response) => {
				this.cityInput.typeahead({
					source: response.data,
					autoSelect: true
				});
				this.bindChange(destInput);	
			});
		} else {
			this.neighborhoodInput.typeahead({
				source: {},
				autoSelect: true
			});
			this.bindChange(destInput);	
		}
	},
	updateSource: function (data) {
		this.neighborhoodInput.data('typeahead').source = data;
	},
	bindChange: function (destInput) {
		this.neighborhoodInput.on('change',()=> { 
			var current = this.neighborhoodInput.typeahead("getActive");
		  	if (current && current.name.toLowerCase() == this.neighborhoodInput.val().toLowerCase()) {
			  	destInput.val(current.id);  		
		  	} else {
		  		destInput.val("");
		  	}
		});
	}
};

function readImageURL(input, target) {
	$(target).html("");
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	console.log(e.target.result);
        	var html = "<img src="+e.target.result+" title='imagem de perfil' alt='imagem de perfil' />";
        	$(target).html(html);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function () {

	$("#cpf").mask('000.000.000-00',{reverse:true});

	$("#birth_date_text").datepicker({
		format: 'dd/mm/yyyy',
		endDate: '0d',
		language: 'pt-BR'
	});

	$("#photoSelect").on('click', function (event) {
		event.preventDefault();
		$("#photo_profile").click();
	});

	$("#birth_date_text").datepicker().on('changeDate', function (event) {
		$('#birth_date').val(event.date.toLocaleString());
	});

	$("#btnShowBirthDateCalendar").on('click', function (event) {
		event.preventDefault();
		var data  = $("#birth_date_text").datepicker('show');
	});

	var cityInput = $('#city_name');
	var destCityInput = $('#city');
	var stateInput = $('#state');
	var neighborhoodInput = $("#neighborhood_name");
	var destNeighborhoodInput = $("#neighborhood");

	CityAutoComplete.setup(cityInput, destCityInput, stateInput.val());
	NeighborhoodAutoComplete.setup(neighborhoodInput, destNeighborhoodInput, stateInput.val());

	stateInput.on('change', () => {

		cityInput.val("");
		destCityInput.val("");
		stateInput.val("");
		neighborhoodInput.val("");
		destNeighborhoodInput.val("");

		var selectedState = stateInput.val();
		if (selectedState) {
			CityProvider.list(selectedState).then((response) => {
				CityAutoComplete.updateSource(response.data);
			});
			NeighborhoodProvider.list(selectedState).then((response) => {
				NeighborhoodAutoComplete.updateSource(response.data);
			});
		}
	});

	$("#photo_profile").on('change', function () {
		console.log('Changed');
    	readImageURL(this, ".img-profile-content");
	});

});