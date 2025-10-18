<?php

namespace App\Console\Commands;

use App\Services\DeadlineReminderService;
use Illuminate\Console\Command;

class SendDeadlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send deadline reminder notifications for upcoming tasks';

    protected DeadlineReminderService $deadlineService;

    public function __construct(DeadlineReminderService $deadlineService)
    {
        parent::__construct();
        $this->deadlineService = $deadlineService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending deadline reminders...');

        $count = $this->deadlineService->sendDeadlineReminders();

        $this->info("✅ Sent {$count} deadline reminder notifications.");

        return Command::SUCCESS;
    }
}
