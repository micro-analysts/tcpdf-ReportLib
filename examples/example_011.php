<?php
/*
 * //============================================================+
 * // File name     : example_011.php
 * // Version       : 1.0.0
 * // Last Update   : 20.12.22, 08:56
 * // Author        : Michael Hodel - reportlib.adiuvaris.ch - info@adiuvaris.ch
 * // License       : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
 * //
 * // Copyright (C) 2022 - 2022 Michael Hodel
 * //
 * // This file is part of ReportLib software library.
 * //
 * // ReportLib is free software: you can redistribute it and/or modify it
 * // under the terms of the GNU Lesser General Public License as
 * // published by the Free Software Foundation, either version 3 of the
 * // License, or (at your option) any later version.
 * //
 * // ReportLib is distributed in the hope that it will be useful, but
 * // WITHOUT ANY WARRANTY; without even the implied warranty of
 * // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * // See the GNU Lesser General Public License for more details.
 * //
 * // You should have received a copy of the GNU Lesser General Public License
 * // along with ReportLib.  If not, see <http://www.gnu.org/licenses/>.
 * //
 * // See LICENSE.TXT file for more information.
 * //============================================================+
 */

include_once "../src/Report.php";

use Adi\ReportLib as ReportLib;

// Create report instance
//  default format A4, portrait with margins left = 20mm, top = 10mm, right = 10mm and bottom = 10mm
$report = new ReportLib\Report();

// Get ref to the report body
$body = $report->getBody();

// QR code in a square
$body->AddBarcode("reportlib.adiuvaris.ch", "QRCODE", 50.0, 50.0);
$body->AddVDistance(5.0);

// QR code in a rectangle height>width
$body->AddBarcode("reportlib.adiuvaris.ch", "QRCODE", 50.0, 80.0);
$body->AddVDistance(5.0);

// QR code in a rectangle height>width
$body->AddBarcode("reportlib.adiuvaris.ch", "QRCODE,H", 100.0, 40.0);
$body->AddVDistance(5.0);

// PDF417 code in a square
$body->AddBarcode("reportlib.adiuvaris.ch", "PDF417", 50.0, 50.0);
$body->AddVDistance(5.0);

// Datamatrix code in a square
$body->AddBarcode("reportlib.adiuvaris.ch", "DATAMATRIX", 50.0, 50.0);
$body->AddVDistance(5.0);

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_011.pdf", 'I');

