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
			created_at: 'Data de cadastro'
		},
		modal : {
			warning: "Atenção",
			okText: "Sim",
			cancelText: "Não",
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
			updateSucces: 'Perfil alterado com sucesso!'
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
			status_values: {
				0: 'Inativo',
				1: 'Ativo'
			},
			platforms: 'Plataformas'
		},
		pre_registration: {
			create_success: 'Pré cadastro criado com sucesso!',
			update_success: 'Pré cadastro alterado com sucesso!',
			mailed_at: 'E-mail de cadastro enviado em'
		},
		buttons: {
			edit: 'Editar',
			save: 'Salvar',
			delete: 'Excluir'
		}
	}
}

export { Messages };