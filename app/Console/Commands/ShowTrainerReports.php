<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Certificate;
use App\Models\AssessmentAttempt;

class ShowTrainerReports extends Command
{
    protected $signature = 'report:trainer';
    protected $description = 'Ipinapakita ang DRR Trainer Reports sa Terminal';

    public function handle()
    {
        $totalCertificates = Certificate::count();
        $attemptsCount = AssessmentAttempt::count();

        $this->info('📊 DRR Trainer - Monitoring & Implementation Report');
        $this->line('--------------------------------------------------');
        $this->line("Total Completed Trainings (Certificates Issued): {$totalCertificates}");
        $this->line("Total Assessment Attempts: {$attemptsCount}");
        $this->info('--------------------------------------------------');
        
        return Command::SUCCESS;
    }
}