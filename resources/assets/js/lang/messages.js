var Messages = {
	pt_BR: {
		app : {
			student: 'Aluno',
			teacher: 'Professor',
			admin: 'Admin',
			loading: 'Carregando...',
			resume: 'Resumo',
			hour: 'hora | horas',
			unavailable: 'Indisponível',
			yes: 'Sim',
			no: 'Não',
			acceptExecuteAction: 'Você confirma a ação selecionada?',
			noRegisterFound: 'Nenhum registro encontrado',
			actions: 'Ações',
			view: "Visualizar",
			search: "Pesquisar",
			select: "Selecione",
			defaultErrors: "Ocorreram alguns erros durante a requisição por favor revise os dados informados e envie novamente.",
			games: 'Jogos',
			game: 'Jogo',
			created_at: 'Data de cadastro'
		},
		modal : {
			warning: "Atenção",
			okText: "Sim",
			cancelText: "Não",
			confirmText: "Confirmar",
			cancel2Text: "Cancelar",
			classMessages : {
				confirmStartClass: "Deseja mesmo iniciar a aula?",
				confirmExtendPeriod: "Deseja mesmo extender esta aula?"
			},
			gameMessages : {
				confirmDelete: "Deseja mesmo excluir este Jogo?"
			}
		},
		lesson: {
			labels: {
				status: 'Situação da aula',
				start_date: 'Data de início',
				duration: 'Duração',
				confirm_class: 'Confirmar aula?',
				remaining_time: 'Tempo restante',
				renew_class: 'Deseja renovar as aula por mais 1 hora?'
			},
			table: {
				status: 'Situação'
			},
			status: {
				1: "Aguardando confirmação do Professor",
				2: "Em andamento ",
				3: "Finalizada",
				4: "Cancelada"
			},
		},
		profile: {
			name: 'Nome',
			email: 'E-mail',
			password: 'Senha',
			password_confirm: 'Confirmar senha',
			nickname: 'Nickname',
			cpf: 'CPF',
			address: 'Informações sobre o seu endereço',
			city: 'Cidade',
			estate: 'Estado',
			street: 'Rua',
			complement: 'Complemento',
			number: 'Nº',
			public_information: 'Informações públicas',
			public_information_help: 'Todos os usuários poderão ver estas informações',
			private_information_help: 'Estas informações não estarão disponíveis para outros usuários',
			private_information: 'Informações privadas',
			profile_photo: 'Foto de perfil',
			birth_date: 'Data de nascimento',
			state: 'Estado',
			city: 'Cidade',
			neighborhood: 'Bairro',
			street: 'Rua',
			number: 'Número',
			complement: 'Complemento',
			profile_image: 'Imagem de Perfil',
			address: 'Informações sobre o seu endereço',
			updateSucces: 'Perfil alterado com sucesso!',
			zipcode: 'CEP',
			zipcode_not_found: 'CEP não encontrato',
			bank_info: 'Informações de Pagamento',
			bank_info_help: 'Estas informações serão utilizadas para que possamos fazer os pagamentos regerentes as aulas. O CPF do títular desta conta deve ser o mesmo que informado acima.',
			bank: 'Banco',
			agency: 'Agência',
			account: 'Conta Corrente',
			digit: 'Dígito',			
		},
		game: {
			title: 'Título',
			description: 'Descrição',
			status: 'Status',
			developer_site: 'Site',
			cover_image: 'Foto de Capa',
			create_success : 'Jogo criado com sucesso!',
			update_success : 'Jogo atualizado com sucesso!',
			delete_success : 'Jogo excluído com sucesso!',
			multiple_update_success : 'Jogos atualizados com sucesso!',
			status_values: {
				0: 'Inativo',
				1: 'Ativo'
			},
			platforms: 'Plataformas',
			platform: 'Plataforma',
			generic_game_error: 'Verifique se você preencheu seu nickname e descrição para cada jogo presente no formulário'
		},
		teacher_game: {
			skill_descripion: 'Descreva seu estilo de jogo e principais habilidades',
			no_game_available: 'O Professor não possui jogos cadastrados no momento!',
			select_game_platform: 'Por favor selecione o Jogo e a Plataforma'
		},
		pre_registration: {
			create_success: 'Pré cadastro criado com sucesso!',
			update_success: 'Pré cadastro alterado com sucesso!',
			mailed_at: 'E-mail de cadastro enviado em',
			resend_email: 'Reenviar e-mail'
		},
		evaluation: {
			message: 'Por favor, avalie esta aula para nos ajudar a oferecer sempre a melhor experiência para nossos usuários',
			evaluation: 'Avaliação',
			evaluate: 'Avaliar',
			note: 'Nota',
			comment: 'Comentário',
			teacher_type_message: 'Com base em sua experiência na aula, avalie o treinador.',
			student_type_message: 'Com base em sua experiência na aula, avalie o aluno.'
		},
		buttons: {
			edit: 'Editar',
			save: 'Salvar',
			delete: 'Excluir'
		}
	}
}

export { Messages };