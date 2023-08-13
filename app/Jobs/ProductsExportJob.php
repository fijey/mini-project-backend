<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ExportManager;
use Excel;
use Storage;
use Auth;
use App\Exports\ProductsExport;

class ProductsExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

     protected $user_id;
     protected $exportManagerRepository;
    public function __construct($id, $exportManagerRepository)
    {
        $this->user_id = $id;
        $this->exportManagerRepository = $exportManagerRepository;
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
            $id = $this->insertExportManager($fileName);
            $temporaryPath = storage_path('app/excel/' . $fileName); // Path sementara

            Excel::store($export, 'excel/' . $fileName); // Menyimpan ekspor ke penyimpanan sementara

            Storage::disk('uploads')->put($fileName, file_get_contents($temporaryPath));

            unlink($temporaryPath); // Menghapus file sementara

            $this->updateExportManager($id,$fileName);


        } catch (\throw $e) {

            
        }
    }

    public function insertExportManager($name){
        $export=[
            'name' => $name,
            'from_page' => 'products',
            'export_start' => now()->format('Y-m-d H:i:s'),
            'status' => "running",
            'url_file' => "",
            'user_id' => $this->user_id
        ];

        $result = $this->exportManagerRepository->create($export);

        return $result->id;
    }
    public function updateExportManager($id,$url_file){
        $exportManager = ExportManager::find($id);
        $exportManager->update([
            'export_end' => now()->format('Y-m-d H:i:s'),
            'status' => "finished",
            'url_file' => asset('report/'.$url_file)
        ]);
    }

}
