<?php

namespace App\Console\Commands;

use App\Services\TelegramUpdateService;
use Illuminate\Console\Command;

class GetTelegramUpdatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-telegram-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get telegram updates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        TelegramUpdateService::getTelegramUpdates();
    }
}
