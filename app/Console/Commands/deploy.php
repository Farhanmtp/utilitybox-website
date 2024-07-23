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
            $reset = $this->option('reset');
            if ($force || $this->confirm(($reset ? "All data will erased from database.\n " : '') . "Are you sur you want to run this command? ")) {
                if ($reset) {
                    $this->info("Running: php artisan db:wipe ");
                    Artisan::call("db:wipe");
                    $this->warn("Done: database cleaned. ");
                }

                $this->info("Running: php artisan migrate");
                Artisan::call("migrate");
                $this->warn("Done: migrate completed");


                if (!env('RFM_KEY')) {
                    $this->info("Running: php artisan rfm:generate");
                    Artisan::call("rfm:generate");
                    $this->warn("Done: php artisan rfm:generate");
                }

                if (!$reset) {

                    if (!Schema::hasColumn('powwr_deals', 'envelopeId')) {
                        Schema::table('powwr_deals', function (Blueprint $table) {
                            $table->string('envelopeId', 255)->nullable()->after('dealId');
                        });
                    }
                    if (!Schema::hasColumn('powwr_deals', 'loaEnvelopeId')) {
                        Schema::table('powwr_deals', function (Blueprint $table) {
                            $table->string('loaEnvelopeId', 255)->nullable()->after('dealId');
                        });
                    }
                    if (!Schema::hasColumn('powwr_deals', 'quoteDetails')) {
                        Schema::table('powwr_deals', function (Blueprint $table) {
                            $table->text('quoteDetails')->nullable()->after('bankAddress');
                        });
                    }
                    if (!Schema::hasColumn('powwr_deals', 'consents')) {
                        Schema::table('powwr_deals', function (Blueprint $table) {
                            $table->text('consents')->nullable()->after('quoteDetails');
                        });
                    }
                    if (!Schema::hasColumn('powwr_suppliers', 'status')) {
                        Schema::table('powwr_suppliers', function (Blueprint $table) {
                            $table->boolean('status')->default(1)->after('logo');
                        });
                    }

                    if (!Schema::hasColumn('powwr_suppliers', 'supplier_type')) {
                        Schema::table('powwr_suppliers', function (Blueprint $table) {
                            $table->string('supplier_type', 10)->default('B')->nullable()->after('logo');
                        });
                    }
                    /*if (Schema::hasColumn('powwr_deals', 'user_id')) {
                        Schema::table('powwr_deals', function (Blueprint $table) {
                            $table->text('user_id')->nullable()->change();
                        });
                    }*/
                }


                if ($reset) {
                    $this->info("Running: php artisan db:seed");
                    Artisan::call("db:seed");
                    $this->warn("Done: db seeder completed");
                } else {
                    if (!MeterExclusions::count()) {
                        $this->info("Running: php artisan db:seed");
                        Artisan::call("db:seed", ['class' => 'MeterExclusionsSeeder']);
                        $this->warn("Done: db seeder completed");
                    }

                    $this->info("Running: php artisan db:seed");
                    Artisan::call("db:seed", ['class' => 'PowwrSuppliersTable']);
                    $this->warn("Done: db seeder completed");

                }


                $this->info("Running: php artisan cache:clear");
                Artisan::call("optimize:clear");
                $this->warn("Done: Cache cleared.");
            }

        }
    }
}
