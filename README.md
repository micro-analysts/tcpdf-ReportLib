# ReportLib

*PHP Report Library*

* **category**    Library
* **author**      Michael Hodel <info@adiuvaris.ch>
* **copyright**   2022-2023 Michael Hodel - Adiuvaris
* **license**     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
* **link**        https://www.adiuvaris.ch/reportlib
* **source**      https://github.com/adiuvaris/ReportLib


## Description

PHP library for generating dynamic PDF reports using the TCPDF library to create the PDF documents. 
The library works with nested rectangle regions on the paper where the sizes can be defined in millimeters 
or in percent of the surrounding rectangle or the library calculates them by the content.

### Main Features:
* all standard page formats (from TCPDF), custom page margins;
* different page formats in one report;
* management of text styles;
* images, 2D barcodes (e.g. QR);
* page header and footer management;
* automatic page break, line break and text alignments;
* automatic page numbering;
* support for tables with a lot of features (e.g. automatic repeat the table header row after page break)
* nested structure of rectangles to create the report structure


## Install

Via Composer

``` bash
$ composer require adiuvaris/ReportLib
```

## Usage

``` php
<?php
require __DIR__ . "/vendor/autoload.php";
use MicroAnalysts\TcpdfReportLib as ReportLib;
$rp = new ReportLib\Report();
$rp->getBody()->AddText("ReportLib test.",ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL));
$rp->output(__DIR__ . "/test.pdf");
```
 

## License

GNU LESSER GENERAL PUBLIC LICENSE. Please see [License File](LICENSE.TXT) for more information.

