<?php

namespace App\Repositories\ExportManager;

interface ExportManagerRepositoryInterface
{

    //Create Export Manager
    public function create($data);

    // Update Export manager
    public function update($id,$data);

}
