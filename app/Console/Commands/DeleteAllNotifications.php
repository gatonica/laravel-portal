<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class DeleteAllNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deleteAllNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will remove all notifications for all users';

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
     * @return int
     */
    public function handle()
    {
        $this->info('The command will delete all notifications');
        global $confirmation, $validInput;
        $validInput = false;
        while (!$validInput) {
            $confirmation = $this->ask('Are you sure?[Y/n]');
            $validInput = $confirmation == null || strtolower($confirmation) == 'y' || strtolower($confirmation) == 'n';
        }
        if ($confirmation == null || strtolower($confirmation) == 'y')
        {
            $notifications = Notification::all();
            $ids = [];
            foreach ($notifications as $notification)
            {
                array_push($ids, $notification->id);
            }
            Notification::destroy($ids);
            $this->info('Done');
            return 0;
        }
        return 0;
    }
}
