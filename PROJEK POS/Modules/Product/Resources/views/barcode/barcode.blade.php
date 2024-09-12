<!DOCTYPE html>
<html>
<head>
    <title>Product Barcodes</title>
    <style>
        .barcode {
            margin: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Product Barcodes</h1>
    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    <div>
        @foreach ($barcodes as $barcode)
            <div class="barcode">
                {!! $barcode !!}
            </div>
        @endforeach
    </div>
</body>
</html>
