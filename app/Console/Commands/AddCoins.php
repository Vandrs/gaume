<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\EnumRole;
use App\Enums\EnumActiveInactive;
use App\Services\Wallet\WalletService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use Log;
use Hash;
use DB;

class AddCoins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wallet:add-coins {email} {password} {coins}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adiciona uma certa quantidade de Monzy Coins a carteira dos alunos';

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
        try {
            // $user = $this->authenticate();
            $coins = (int) $this->argument('coins');
            if (empty($coins)) {
                throw new \Exception('O parâmetro coins é obrigatório');
            }
            // $this->info('Realizando a ação como: '.$user->name);
            $students = $this->getStudents();
            $this->info($students->count().' alunos encontrados');
            $job = $this;
            $students->each(function($student) use ($job, $coins) {
                $job->info('Inserindo '.$coins.' coins para o usuário '.$student->name);
                if ($student->wallet) {
                    WalletService::returnPoints($student->wallet, $coins);
                    $job->info('Coint inseridas com sucesso!');
                } else {
                    $job->error('Usuário não possui carteira.');
                }
            });
        } catch(\Exception $e) {
            $this->error($e->getMessage());
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    private function getStudents()
    {
        return User::query()
                   ->where('users.role_id', '=', EnumRole::STUDENT_ID)
                   ->get();
    }

    private function authenticate()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        if (empty($email)) {
            throw new \Exception('O parâmetro email é obrigatório');
        }
        if (empty($password)) {
            throw new \Exception('O parâmetro password é obrigatório');
        }
        try {
            $user = User::where('email', '=', $email)
                       ->where('password','=', Hash::make($password))
                       ->where('status','=', EnumActiveInactive::ACTIVE)
                       ->where('role_id','=', EnumRole::ADMIN_ID)
                       ->firstOrFail();
            return $user;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Credenciais inválidas');
        }
    }
}
