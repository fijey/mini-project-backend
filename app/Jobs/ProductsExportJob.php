<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Excel;
use Storage;
use App\Exports\ProductsExport;

class ProductsExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $export = new ProductsExport();
            $fileName = 'products_' . now()->format('YmdHis') . '.xlsx'; // Nama file unik dengan timestamp

            $temporaryPath = storage_path('app/excel/' . $fileName); // Path sementara

            Excel::store($export, 'excel/' . $fileName); // Menyimpan ekspor ke penyimpanan sementara

            Storage::disk('uploads')->put($fileName, file_get_contents($temporaryPath));

            unlink($temporaryPath); // Menghapus file sementara


        } catch (\Exception $e) {

        }
    }

}
