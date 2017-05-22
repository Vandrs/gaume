var Messages = {
	pt_BR: {
		app: {
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
			view: "Visualizar"
		},
		modal : {
			warning: "Atenção",
			okText: "Sim",
			cancelText: "Não",
			classMessages : {
				confirmStartClass: "Deseja mesmo iniciar a aula?",
				confirmExtendPeriod: "Deseja mesmo extender esta aula?"
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
		}
	}
};

export { Messages };