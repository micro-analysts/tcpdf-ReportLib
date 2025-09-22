<?php
/*
 * //============================================================+
 * // File name     : example_022.php
 * // Version       : 1.0.0
 * // Last Update   : 01.01.23, 10:52
 * // Author        : Michael Hodel - adiuvaris.ch/reportlib - info@adiuvaris.ch
 * // License       : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
 * //
 * // Copyright (C) 2022 - 2023 Michael Hodel
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

use MicroAnalysts\TcpdfReportLib as ReportLib;

// Create report instance
//  default format A4, portrait with margins left = 20mm, top = 10mm, right = 10mm and bottom = 10mm
$report = new ReportLib\Report();

// Get NORMAL text style
$tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
$tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

// Text paragraph
$text = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.\n";

// Get ref to the report body
$body = $report->getBody();

// Text in box in a horizontal SerialFrame
$sf = $body->AddHContainer();

// Add two boxes with text in them
$sf->AddTextInBox(50.0, $text, $tsNormal);
$sf->AddTextInBox(120.0, $text, $tsBold);
$body->AddVDistance(10.0);

// Three columns of justified text in boxes
$sf = $body->AddHContainer();

// First column
$box = $sf->AddBox(55.0);
$box->AddText($text, $tsNormal, false, 'J');
$sf->AddHDistance(4.0);

// Second column
$box = $sf->AddBox(55.0);
$box->AddText($text, $tsNormal, false, 'J');
$sf->AddHDistance(4.0);

// Third column
$box = $sf->AddBox(55.0);
$box->AddText($text, $tsNormal, false, 'J');
$body->AddVDistance(10.0);


// Three columns with an image and a barcode and some text
$sf = $body->AddHContainer();

// First column with a QR-code
$sf->AddBarcode("adiuvaris.ch/reportlib", "QRCODE", 40.0, 40.0);
$sf->AddHDistance(5.0);

// Second column with an image
$box = $sf->AddImage("image.jpg", true, 50.0, 30.0);
$sf->AddHDistance(5.0);

// Third column with text
$sf->AddTextInBox(60.0, $text, $tsNormal);
$body->AddVDistance(10.0);


// Labels with text
$sf = $body->AddHContainer();

// First line with label and text
$sf->AddTextInBox(25.0, "Label 1: ", $tsNormal, 'R');
$sf->AddText("Text for Label 1", $tsBold);

// Second line with label and text
$sf = $body->AddHContainer();
$sf->AddTextInBox(25.0, "Another Label: ", $tsNormal, 'R');
$sf->AddText("Text for the second label", $tsBold);

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_022.pdf", 'I');
