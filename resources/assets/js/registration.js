require('./bootstrap');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');
require('./jquery/plugins/jquery-mask');
require('./jquery/plugins/bootstrap-datepicker');

import { LocationProvider } from './providers/locationProvider';

String.prototype.lpad = function(padString, length) {
    var str = this;
    while (str.length < length)
        str = padString + str;
    return str;
}

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
		var day   = event.date.getDate();
		var month = event.date.getMonth() + 1;
		var year  = event.date.getFullYear();
		day = day.toString().lpad("0",2).toString();
		month = month.toString().lpad("0",2).toString();
		var strDate = year.toString()+"-"+month+"-"+day+" 00:00:00";
		$('#birth_date').val(strDate);
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