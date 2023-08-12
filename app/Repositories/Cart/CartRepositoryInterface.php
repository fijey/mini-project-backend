<?php

namespace App\Repositories\Cart;

interface CartRepositoryInterface
{
    // get list cart from user
    public function getCart();

    // insert product to cart
    public function store($data);


}
