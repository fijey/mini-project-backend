<?php
namespace App\Repositories\ExportManager;

use App\Repositories\ExportManager\ExportManagerRepositoryInterface;
use Auth;
use App\Models\ExportManager;
use Illuminate\Support\Facades\Log;

class ExportManagerRepository implements ExportManagerRepositoryInterface
{
    public function all(){
        return ExportManager::all();
    }
    public function create($data)
    {
        
        try {
            return ExportManager::create($data);
        } catch (\Exception $e) {
            Log::error('Error fetching Create Export ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => $user->id,
            ]);

            throw new \RuntimeException('Failed to fetch Export Manager items', 500, $e);
        }
    }

    public function update($id,$data)
    {
        try {
            
            $exportManager = ExportManager::find($id);
            return $exportManager->update($data);

        } catch (\Exception $e) {
            Log::error('Failed to update Export: ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => Auth::user()->id,
            ]);

            throw new \RuntimeException('Failed to save Export Manager items', 500, $e);
        }
    }
}
