<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use \DB;
use App\Enums\EnumRole;
use App\Services\Wallet\CreateWalletService;


class CreateWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wallets:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = $this->getUsers();
        $this->info("Criando Wallets para ".$users->count()." usuários");
        $self = $this;
        $users->each(function($user) use ($self) {
            $self->info("Criando carteira para o usuário: ".$user->name);
            CreateWalletService::create($user);
            $self->info("Carteira criado com sucesso!");
        });
        $this->info('Carteiras criadas com sucesso.');
    }

    private function getUsers()
    {
        return User::query()
                  ->select(DB::RAW("users.*"))
                  ->leftJoin('wallets','users.id', '=', 'wallets.user_id')
                  ->where('users.role_id', '=', EnumRole::STUDENT_ID)
                  ->whereNull('wallets.user_id')
                  ->get();
    }
}
