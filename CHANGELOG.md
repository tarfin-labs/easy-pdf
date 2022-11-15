# Changelog
All notable changes to `easy-pdf` will be documented in this file.

## [Unreleased]

## 2.5.1 - 2022-15-11
- Add `setPrintHeader(false)` and `setPrintFooter(false)` to delete unnecessary line in header and footer.

## 2.5.0 - 2022-01-11
- `splitTo()` method added to `Parser` class.

## 2.4.0 - 2022-05-30
- `setPage()` method added for setting current page.

## 2.3.0 - 2022-02-08
- Add support for Laravel 9.

## 2.2.0 - 2022-01-14
- `$pdf` variable changed to public variable in `EasyPdf.php`
- SVG support added to `setImage` method.

## 2.1.0 - 2021-06-03
- PHP 8 support added.

## 2.0.0 - 2020-10-06
- Add support for Laravel 8.

## 1.2.1 - 2020-07-09
- Now `setHeader()` and `setFooter()` methods returns EasyPdf instance.

## 1.2.0 - 2020-06-15
- `reset()` method added for creating new tcpdf instance.

## 1.1.0 - 2020-03-20
- Read from `url` and `pdf blob` support added for parser and merge.

## 1.0.0 - 2020-03-10
- Initial release.
