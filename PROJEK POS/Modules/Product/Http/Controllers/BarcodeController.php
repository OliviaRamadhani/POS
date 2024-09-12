<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class BarcodeController extends Controller
{

    public function showBarcodes()
{
    // Pastikan Anda telah memanggil fungsi generateBarcodes sebelumnya
    return view('product.barcode', ['barcodes' => $this->showBarcodes()]);
}


    public function printBarcode() {
        abort_if(Gate::denies('print_barcodes'), 403);

        return view('product::barcode.index');
    }

}
