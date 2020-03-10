# easy-pdf

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tarfin-labs/easy-pdf.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/easy-pdf)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/tarfin-labs/easy-pdf/tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/tarfin-labs/easy-pdf.svg?style=flat-square)](https://scrutinizer-ci.com/g/tarfin-labs/easy-pdf)
[![Total Downloads](https://img.shields.io/packagist/dt/tarfin-labs/easy-pdf.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/easy-pdf)

## Introduction
easy-pdf is a [tcpdf](https://tcpdf.org/) wrapper for Laravel 6.x and 7.x.

## Installation

You can install the package via composer:

```bash
composer require tarfin-labs/easy-pdf
```

## Usage

### Creating pdf loading html.

You can create pdf file loading html and setting pdf informatin. Also you can use default fonts and if you want you can add custom ttf fonts.

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
$pdf->save($filePath);
```

Also you can stream pdf directly to the browser using stream method:

``` php
$pdf->stream();
```

You can add custom TTF font using addFont:
``` php
// Add custom font using font path
$pdf->addFont($fontPath, $fontSize);

// Use default fonts with supported font name
$pdf->setFont('helvetica', 16);
```

If you want the use default fonts here is the list:
`courier`, `courierB`, `courierBI`, `courierI`, `helvetica`, `helveticaB`, `helveticaBI`, `helveticaI`, `symbol`, `times`, `timesB`, `timesBI`, `timesI`, `zapfdingbats`

Easy pdf provides basic barcode support like qrcode and raw2 barcode.

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

### Parsing pdf

You can parse the pdf and get the page you want.

``` php
// This will return pdf page count.
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
$files = [
    '/path/to/the/file.pdf',
    '/path/to/the/anotherFile.pdf',
];

// You can use stream or content method here as well.
$pdf = EasyPdf::merge($files)
            ->content();
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
