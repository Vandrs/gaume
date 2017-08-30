<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'O campo :attribute deve ser confirmado.',
    'active_url'           => 'O campo :attribute não é uma URL válida.',
    'after'                => 'O campo :attribute deve ser uma data posterior à :date.',
    'after_or_equal'       => 'O campo :attribute deve ser uma data posterior ou igual à :date.',
    'alpha'                => 'O campo :attribute deve conter apenas letras.',
    'alpha_dash'           => 'O campo :attribute deve conter apenas letras, números e "-".',
    'alpha_num'            => 'O campo :attribute deve conter apenas letras e números.',
    'array'                => 'O campo :attribute deve ser um array.',
    'before'               => 'O campo :attribute deve ser uma data anterior a :date.',
    'before_or_equal'      => 'O campo :attribute deve ser uma data anterior ou igual a :date.',
    'between'              => [
        'numeric' => 'O campo :attribute deve ser um valor entre :min e :max.',
        'file'    => 'O campo :attribute deve ser um valor entre :min e :max kilobytes.',
        'string'  => 'O campo :attribute deve ser um valor entre :min e :max caracteres.',
        'array'   => 'O campo :attribute deve ter entre :min e :max itens.',
    ],
    'boolean'              => 'O campo :attribute deve ser true ou false.',
    'confirmed'            => 'O campo :attribute de confirmação não bate.',
    'date'                 => 'O campo :attribute não é uma data válida.',
    'date_format'          => 'O campo :attribute não é compatível com o formato :format.',
    'different'            => 'O campo :attribute e :other devem ser diferentes.',
    'digits'               => 'O campo :attribute deve ter :digits digitos.',
    'digits_between'       => 'O campo :attribute deve ter entre :min e :max digitos.',
    'dimensions'           => 'O campo :attribute é uma imagem dimensões inválidas.',
    'distinct'             => 'O campo :attribute já possui outro elemento com o mesmo valor.',
    'email'                => 'O campo :attribute deve ser um enderço de e-mail válido.',
    'exists'               => 'O campo selecionado ":attribute" é inválido.',
    'file'                 => 'O campo :attribute deve ser um arquivo.',
    'filled'               => 'O campo :attribute deve ser preenchido.',
    'image'                => 'O campo :attribute deve ser uma imagem válida (png, jpg).',
    'in'                   => 'O campo selectionado ":attribute" é inválido.',
    'in_array'             => 'O campo :attribute não existe em :other.',
    'integer'              => 'O campo :attribute deve ser um número inteiro.',
    'ip'                   => 'O campo :attribute deve ser um endereço de IP válido.',
    'json'                 => 'O campo :attribute deve de conter o formato JSON.',
    'max'                  => [
        'numeric' => 'O campo :attribute deve ser menor que :max.',
        'file'    => 'O campo :attribute deve ser menor que :max kilobytes.',
        'string'  => 'O campo :attribute deve ser menor que :max characters.',
        'array'   => 'O campo :attribute não deve conter mais que :max itens.',
    ],
    'mimes'                => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes'            => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'min'                  => [
        'numeric' => 'O campo :attribute deve conter no máximo :min.',
        'file'    => 'O campo :attribute deve conter no máximo :min kilobytes.',
        'string'  => 'O campo :attribute deve conter no máximo :min characters.',
        'array'   => 'O campo :attribute deve conter ao mínimo :min itens.',
    ],
    'not_in'               => 'O campo :attribute selecionado é inválido.',
    'numeric'              => 'O campo :attribute deve ser um número.',
    'present'              => 'O campo :attribute deve estar presente.',
    'regex'                => 'O campo :attribute possui um formato inválido.',
    'required'             => 'O campo :attribute é obrigatório.',
    'required_if'          => 'O campo :attribute é obrigatório quando :other és :value.',
    'required_unless'      => 'O campo :attribute é obrigatório a menos que :other esteja em :values.',
    'required_with'        => 'O campo :attribute é obrigatório quando :values for selecionado.',
    'required_with_all'    => 'O campo :attribute é obrigatório quando :values for selecionado.',
    'required_without'     => 'O campo :attribute é obrigatório quando :values não for selecionado.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nennhum dos valores :values forem selecionados.',
    'same'                 => 'O campo :attribute e :other devem ser iguais.',
    'size'                 => [
        'numeric' => 'O campo :attribute deve ser :size.',
        'file'    => 'O campo :attribute deve ser :size kilobytes.',
        'string'  => 'O campo :attribute deve ser :size caracteres.',
        'array'   => 'O campo :attribute deve conter :size itens.',
    ],
    'string'               => 'O campo :attribute deve ser uma string.',
    'timezone'             => 'O campo :attribute deve ser um TimeZone válido.',
    'unique'               => 'O campo :attribute já foi utilizado.',
    'uploaded'             => 'O campo :attribute não foi possível fazer o upload.',
    'url'                  => 'O campo :attribute possui um formato inválido.',
    'cpf' => 'CPF inválido',
    'cnpj' => 'CNPJ inválido',
    'cep' => 'CEP inválido',

    'attributes' => [],

    'custom' => [
        'is_not_teacher' => 'O usuário selecionado não é um professor',
        'class_confirmation_expired' => "Prazo para confirmação da aula expirado.",
        'terms' => 'Você precisa aceitar os termos de usa para prosseguir',
        'min_age' => 'Você deve ser maior de :age anos para se registrar',
        'unauthorized' => 'Você não possui permissão para realizar a ação solicitada.',
        'unexpected' => 'Ocorreu um erro ineperado tente novamente mais tarde e se o erro persistir contate o administrador do sistema.'        
    ],

    'dimension_min' => ':attribute deve ser no míminio :width de largura por :height de altura'
];
