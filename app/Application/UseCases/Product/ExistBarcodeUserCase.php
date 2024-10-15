<?php
namespace App\Application\UseCases\Product;

use App\Models\Product;

class ExistBarcodeUserCase {
    public function __construct()
    {
        //
    }

    static public function execute($barcode) : bool
    {
        return Product::where('barcode', $barcode)->exists();
    }
}
