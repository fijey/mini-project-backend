<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ExportManager\ExportManagerRepositoryInterface;

class ExportManagerController extends Controller
{
    protected $exportManagerRepository;
    
    public function __construct(ExportManagerRepositoryInterface $exportManagerRepository){
        $this->exportManagerRepository = $exportManagerRepository;
    }

    public function index(){
        try {
            $data = $this->exportManagerRepository->all();
            
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching Export Manager', 'error' => $e->getMessage()], 500);
        }
    }
}
