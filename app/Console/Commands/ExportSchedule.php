<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProductsExportJob;
class ExportSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:exportSchedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Will Use For Run All Schedule Export Jobs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return ProductsExportJob::dispatch();
    }
}
