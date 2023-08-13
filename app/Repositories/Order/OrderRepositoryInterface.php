<?php

namespace App\Repositories\Order;

interface OrderRepositoryInterface
{

    //getting order list
    public function getOrderList();

    // insert data order
    public function store($data);

}
