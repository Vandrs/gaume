<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Billing\CalculateLessonBillingService;
use App\Services\Billing\GetLessonBillingService;

class CalculateBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:billing:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula o repasse que deve ser feito para cada aula finalizada';

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
        $self = $this;
        $service = new CalculateLessonBillingService();
        $lessons = GetLessonBillingService::getAll();
        $this->info('Calculando Repasse de '.$lessons->count().' aulas');
        $lessons->each(function($lesson) use ($self, $service) {
            $self->info('Calculando aula: ID '.$lesson->id);
            $service->calculate($lesson);
        });

        $this->info("Repasse calculado com sucesso");
    }
}
