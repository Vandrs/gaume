
require('./bootstrap');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

import { ContactProvider } from './providers/contactProvider';

$(document).ready(function () {

	$('#submitForm').on('click', function (event) {
		event.preventDefault();
		AlertContainer.hide();
		var data = getData();
		ContactProvider.guestContact(data).then((response) => {
			AlertContainer.show('success', response.data.msg, 5000);	
		}).catch((error) => {
			if (error.response.status == 400) {
				var objErrors = error.response.data;
				var errors = [];
				var keys = Object.keys(objErrors);
				for (var key of keys) {
					errors.push(objErrors[key][0]);
				}
				var textError = errors.join('<br />');
				AlertContainer.show('danger', textError);	
			} else {
				AlertContainer.show('danger', 'Ocorrou um erro inesperado ao processar o solicitação.');
			}
		});
	});

	$('a[href^="#"]').on('click',function (e) {
	    e.preventDefault();
	    var target = this.hash;
	    var $target = $(target);
	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 900, 'swing', function () {
	        window.location.hash = target;
	    });
	});
});

function getData() {
	var  data = {
		'name': $('#name').val(),
		'email': $('#email').val(),
		'comment': $('#comment').val(),
		'type': $('#type').val()
	};
	return data;
}

var AlertContainer = {
	selector: '.alert-container',
	show: function (alertClass, alertText, timeout) {
		var html = ("<div class='alert alert-"+alertClass+"'>"+alertText+"</div>");	
		$(this.selector).html(html);
		if (timeout) {
			console.log('set timeout', timeout);
			setTimeout(() => {
				this.hide();
			}, timeout);
		}
	},
	hide: function () {
		$(this.selector).html("");	
	}
}