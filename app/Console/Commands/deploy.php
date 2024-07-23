<?php

namespace App\Console\Commands;

use App\Models\MeterExclusions;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy
                            {--force}
                            {--reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->isProduction()) {
            $this->error("This command not allowed in production environment.");
        } else {
            $force = $this->option('force');
            if ($force || $this->confirm("Are you sur you want to run this command?")) {

                if (!env('RFM_KEY')) {
                    $this->info("Running: php artisan rfm:generate");
                    Artisan::call("rfm:generate");
                    $this->warn("Done: php artisan rfm:generate");
                }

                if (Schema::hasColumn('messages', 'attachment')) {
                    Schema::table('messages', function (Blueprint $table) {
                        $table->text('attachment')->nullable()->change();
                    });
                }

                /*$this->info("Importing suppliers");
                Artisan::call("import:suppliers");
                $this->warn("Done: Importing suppliers");*/

                $this->info("Running: php artisan cache:clear");
                Artisan::call("optimize:clear");
                $this->warn("Done: Cache cleared.");
            }
        }
    }
}
