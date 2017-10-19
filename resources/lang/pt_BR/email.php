<?php 

return [
	'pre_registration_mail' => [
		'subject' => 'Confirmação de cadastro Monzy!',
		'body' => "<br/>Olá <strong>:name</strong> , bem vindo a Monzy!</br/><br/>
				   Você foi selecionado para fazer parte de nosso time de treinadores.<br/><br/> 
				   É com muita satisfação que agradecemos pela sua escolha de fazer parte de uma das maiores comunidades de experts em e-Sports do país. <br/><br/>
				   Para que você possa começar a treinar novos players é necessário antes finalizar o seu cadastro em nosso plataforma clicando no link abaixo.<br /><br />
				   <strong><a href=':link'>:link</a></strong>"
	],
	'evaluate_lesson_email' => [
		'subject' => 'Olá :name, avalie sua aula do dia :date',
		'student_body' => "<br/>Olá, <strong>:student</strong>!<br/><br/>
						   Você ainda não avaliou a sua aula de <strong>:game</strong> do dia :date com o treinador <strong>:teacher</strong>.<br/>
						   Para realizar a avaliação acesse o link abaixo.
						   <br/><br/><a href=':link'>:link</a><br/><br/>
						   Lembramos que a sua avaliação é de extrema importância para que possamos oferecer sempre a melhor experiência em nossas aulas.",
		'teacher_body' => "<br/>Olá, <strong>:teacher</strong>!<br/><br/>
						   Você ainda não avaliou a sua aula de <strong>:game</strong> do dia :date com o aluno <strong>:student</strong>.<br/>
						   Para realizar a avaliação acesse o link abaixo.
						   <br/><br/><a href=':link'>:link</a><br/><br/>
						   Lembramos que a sua avaliação é de extrema importância para que possamos oferecer sempre a melhor experiência em nossas aulas.",
	],
	'lesson_confirmation_mail' => [
		'subject' => 'Aula de :game em :date',
		'student_body' => "Olá <strong>:student</strong> obrigado por escolher a Monzy! <br/><br/>
						   Este é um comprovante da sua aula com o treinador <strong>:teacher</strong>, referente ao jogo <strong>:game</strong> no dia :date com duração de <strong>:hours horas.</strong> <br/><br/>
						   Caso você ainda não tenha avaliado, clique no link abaixo realizar a avaliação da aula <br/> <a href=':link'>:link</a> <br/></br>
						   Lembramos que a sua avaliação é de extrema importância para que possamos oferecer sempre a melhor experiência em nossas aulas.",
		'teacher_body' => "Olá <strong>:teacher</strong> obrigado por escolher a Monzy! <br/><br/> 
						   Este é um comprovante da sua aula referente ao jogo <strong>:game</strong> com o aluno <strong>:student</strong> no dia :date com duração de <strong>:hours horas.</strong> <br/><br/>
						   Caso você ainda não tenha avaliado, clique no link abaixo realizar a avaliação da aula <br/> <a href=':link'>:link</a> <br/></br>
						   Lembramos que a sua avaliação é de extrema importância para que possamos oferecer sempre a melhor experiência em nossas aulas."
	]
];