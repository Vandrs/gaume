<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$qtd = $this->command->ask("Quantos usuários deseja criar?", 1);   
    	$role = $this->command->ask("Qual perfil deseja criar ADMIN(1) TEACHER(2) STUDENT(3) ?", 1);
        $users = factory(\App\Models\User::class, (int) $qtd)->create(['role_id' => (int) $role]);
        $seeder = $this;
        $this->command->info($users->count()." Usuários criados");
        $users->each(function($user) use ($seeder) {
            $seeder->command->info($user->name." ".$user->email);
        });
    }
}
