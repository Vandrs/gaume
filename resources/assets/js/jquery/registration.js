require('./plugins/bootstrap3-typeahead');
require('./plugins/jquery-mask');
require('./plugins/bootstrap-datepicker');
require('./locales/bootstrap-datepicker.pt-BR');

import { LocationProvider } from '../providers/locationProvider';

function readImageURL(input, target) {
	$(target).html("");
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	var html = "<img src="+e.target.result+" title='imagem de perfil' alt='imagem de perfil' />";
        	$(target).html(html);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function () {

	$("#cpf").mask('000.000.000-00',{reverse:true});
	$("#zipcode").mask('00000-000');
	$("#agency").mask('00000000000000000000');
	$("#account").mask('00000000000000000000');
	$("#digit").mask('00');

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

	$("#photo_profile").on('change', function () {
    	readImageURL(this, ".img-profile-content");
	});

	$("#zipcode").on('change',function(){
		var cep = $(this).val();
		$("#state, #city, #neighborhood, #street, #complement, #number").val("");
		if (cep.length >= 9) {
			LocationProvider.get(cep)
						    .then((response) => {
						    	var data = response.data;
								$("#state").val(data.state);    	
								$("#city").val(data.city);
								$("#neighborhood").val(data.neighborhood);
						    })
						    .catch((error) => {
						    	alert('Cep não encontrato!');
						    });
		}
	});

});