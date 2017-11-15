<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Validator;
use App\Models\User;
use App\Enums\EnumRole;
use Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user:admin:create {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um usuário com perfil de administrador';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $data = [
            'name'     => $this->argument('name'),
            'email'    => $this->argument('email'),
            'password' => $this->argument('password')
        ];

        $validator = Validator::make($data, $this->getValidation());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            foreach ($errors as $error) {
                $this->error($error);
            }
            return ;
        } else {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id'  => EnumRole::ADMIN_ID
            ]);
            $this->info('Usuário criado com sucesso ID: '.$user->id);
        }

    }

    private function getValidation()
    {
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|min:8',
        ];
    }
}
