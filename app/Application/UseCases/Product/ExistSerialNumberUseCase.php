<?php

namespace App\Application\UseCases\Product;

use App\Models\Product;

class ExistSerialNumberUseCase
{
    public function __construct()
    {
        //
    }

    static public function execute($serial_number) : bool
    {
        return Product::where('serial_number', $serial_number)->exists();
    }
}
