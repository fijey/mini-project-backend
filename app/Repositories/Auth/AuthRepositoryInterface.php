<?php

namespace App\Repositories\Auth;

interface AuthRepositoryInterface
{
    // make login function will return bearer token to front
    public function login(array $credentials);

    // register new account
    public function register(array $userData);
}
