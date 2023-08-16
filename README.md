# easy-pdf

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tarfin-labs/easy-pdf.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/easy-pdf)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/tarfin-labs/easy-pdf/tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/tarfin-labs/easy-pdf.svg?style=flat-square)](https://scrutinizer-ci.com/g/tarfin-labs/easy-pdf)
[![Total Downloads](https://img.shields.io/packagist/dt/tarfin-labs/easy-pdf.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/easy-pdf)

## Introduction
easy-pdf is a [tcpdf](https://tcpdf.org/) wrapper for Laravel 6.x, 7.x, 8.x, 9.x, 10.x.

## Installation

You can install the package via composer:

```bash
composer require tarfin-labs/easy-pdf
```

## Usage

### Creating pdf with html.

You can create a pdf with html. Easy-pdf also provides easy configuration with tcpdf pdf settings and informations. 

``` php
$pdf = EasyPdf::withInformation([
    'Creator'   => 'Tarfin',
    'Author'    => 'Faruk Can',
    'Title'     => 'EasyPdf',
    'Keywords'  => 'easy, pdf',
    'AutoPageBreak' => [true, 0],
])
    ->withConfig([
        'ImageScale' => PDF_IMAGE_SCALE_RATIO,
])
    ->setFont('times', 16) // use default fonts
    ->loadHtml($html) // each load html creates a new page
    ->content(); // return pdf content as a string
```

This will return pdf content as a string. If you want save pdf, use save method:

``` php
// This will save pdf to given path.
$pdf->save($filePath);
```

Also you can stream pdf directly to the browser using stream method:
``` php
// This will stream pdf to the directly browser.
$pdf->stream();
```

You can add custom TTF font using addFont or use default fonts:
``` php
// Add custom font using font path
$pdf->addFont($fontPath, $fontSize);

// Use default fonts with supported font name
$pdf->setFont('helvetica', 16);
```

If you want the use default fonts, here is the list:
`courier`, `courierB`, `courierBI`, `courierI`, `helvetica`, `helveticaB`, `helveticaBI`, `helveticaI`, `symbol`, `times`, `timesB`, `timesBI`, `timesI`, `zapfdingbats`

Easy pdf provides barcode and qrcode support.

``` php
// This will add barcode to the pdf with given dimensions.
$code = '[111011101110111][010010001000010][010011001110010][010010000010010][010011101110010]';
$pdf->addBarcode($code, 80, 60, 30, 20); // x-y coordinates and width-height

// // This will add qrcode with best error correction to the pdf with given dimensions.
$pdf->addQrcode('tarfin', 80, 60, 30, 20); // x-y coordinates and width-height
```

You can add image to the pdf with dimensions.
``` php
// This will add image to the pdf with given dimensions.
$pdf->addImage($imagePath, 80, 60, 30, 20); // x-y coordinates and width-height
```

You can set active page using `setPage()` method.
```php
// This will set the active page as 1.
$pdf->setPage(1);
```

You can set margins using `setMargins()` method.
```php
// This will set margin as 10 for left, 15 for top, 20 for right
// and overwrite the default margins.  
$pdf->setMargins(10, 15, 20, true);
```

You can add image to page header using `setHeaderData()` method.
```php
// $textColor and $lineColor must be RGB as array format. '[255, 255, 255]'
$pdf->setHeaderData($image, $width, $textColor, $lineColor);
```

You can set header margin using `setHeaderMargin()` method.
```php
// This will set the minimum distance between header and top page margin. 
$pdf->setHeaderMargin(10);
```

You can set test and line colors of footer using `setFooterData()` method.
```php
// The first parameter is text color and the last one is line color.
$pdf->setFooterData([0, 64, 255], [0, 64, 128]);
```

You can set footer margin using `setFooterMargin()` method.
```php
// This will set the minimum distance between footer and bottom page margin. 
$pdf->setFooterMargin(10);
```

You can set footer font using `setFooterFontSize()` method.
```php
// This will set the footer font size to 10.
$pdf->setFooterFontSize(10);
```
You can set paper handling option to use when printing the file from the print dialog.
```php
// This will set the print to single sided. 
$pdf->setDuplex('Simplex'); 

// This will set duplex and flip on the short edge of the sheet
$pdf->setDuplex('DuplexFlipShortEdge'); 

// This will set duplex and flip on the long edge of the sheet
$pdf->setDuplex('DuplexFlipLongEdge'); 
```

You can add 

### Parsing pdf

You can parse the pdf and get the page you want.
``` php
// This will return pdf page count.
// $file can be path, url or blob.
$fileCount = EasyPdf::parser($file)->count();

// You can use stream or content method here as well.
$parsedPdf = EasyPdf::parser($file)
    ->setPage(1)
    ->save(storage_path('app/imports/new.pdf'));
```

### Merging pdf

You can merge multiple pdf into the one with easily using easy-pdf.
``` php
// Pdf paths.
// Pdf paths can be path, url or blob.
$files = [
    '/path/to/the/file.pdf',
    '/path/to/the/anotherFile.pdf',
];

// You can use stream or content method here as well.
$pdf = EasyPdf::merge($files)
            ->content();
```

### Splitting pdf

You can split pdf file into multiple pdf files easily using easy-pdf.
``` php
// Pdf path.
// Pdf path can be path, url or blob.

$file = '/path/to/the/file.pdf';

// You can use splitTo method to get new pdf contents
// chunkSize argument determines that how many page should contain every pdf file
// For example if you want to split your pdf file as each file includes 10 page
// Then you must set chunkSize argument to 10
$pdfs = EasyPdf::parser($file)
            ->splitTo(10);

foreach($pdfs as $pdfContent) {
    // Your code here
}
```

### Resetting the instance
If you try to generate pdf inside a Laravel queue, sometimes there might occure an error like `undefined property: TCPDF::$h`.

The error occurs 2nd time you use the EasyPdf facade after you already created a PDF. Since EasPdf service is registered as singleton to the service container, it returns the same instance when you use it 2nd time and somehow it's broken.

To avoid the error which mentioned above you can use `reset()` method in the beginning. This will return a new TCPDF instance.

```php
$pdf = EasyPdf::reset()
    ->withInformation([
        'Creator'   => 'Tarfin',
        'Author'    => 'Faruk Can',
        'Title'     => 'EasyPdf',
        'Keywords'  => 'easy, pdf',
        'AutoPageBreak' => [true, 0],
    ])
    ->withConfig([
            'ImageScale' => PDF_IMAGE_SCALE_RATIO,
    ])
    ->setFont('times', 16) // use default fonts
    ->loadHtml($html) // each load html creates a new page
    ->content(); // return pdf content as a string
```

### Pdf Header and Setter
After using `reset()` method for creating new Tcpdf instance for each page, automatically header line added to Pdf. Whether you want or not you can use `setHeader' for print header or `setFooter` for print footer content.

```php
$pdf = EasyPdf::reset()
    ->setHeader(false);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update the tests as appropriate.

### Security

If you discover any security-related issues, please email development@tarfin.com instead of using the issue tracker.

## Credits

- [Faruk Can](https://github.com/frkcn)
- [Yunus Emre Deligöz](https://github.com/deligoez)
- [Hakan Özdemir](https://github.com/hozdemir)
- [Turan Karatuğ](https://github.com/tkaratug)
- [All Contributors](../../contributors)

### License
easy-pdf is open-sourced software licensed under the MIT license.
