<?php
/*
 * //============================================================+
 * // File name     : example_009.php
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

// Get ref to the report body
$body = $report->getBody();

// Get the default text style
$tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
$tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

// Text paragraph
$text = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.";

// Add a paragraph of text 20 times to the report body
$body->AddVDistance(5.0);
for ($i = 0; $i < 20; $i++) {
    $body->AddText("Paragraph number " . $i + 1, $tsNormal);
    $body->AddText($text, $tsNormal);
    $body->AddVDistance(2.0);
}

// Add a manual page break - keep the format settings
$body->AddPageBreak();
$body->AddText($text, $tsNormal);

// Add a manual page break - change the settings to landscape and adjust the margins
$pageFormat = new ReportLib\PageFormat('A4', 'L', 30.0, 20.0, 30.0);
$pb = $body->AddPageBreak($pageFormat);


// Add a paragraph of text 10 times to the report body
for ($i = 0; $i < 10; $i++) {
    $body->AddText("Paragraph number " . $i + 1, $tsNormal);
    $body->AddText($text, $tsNormal);
    $body->AddVDistance(3.0);
}

// Add a manual page break - change the settings to A5 landscape and use default margins
$pageFormat = new ReportLib\PageFormat('A5', 'L');
$pb = $body->AddPageBreak($pageFormat);
$body->AddVDistance(5.0);
$body->AddText($text, $tsNormal);


// Add a manual page break - change the settings to  A5 portrait and adjust the margins - mirror the left and right margins
$pageFormat = new ReportLib\PageFormat('A5', 'P', 20.0, 10.0, 10.0, 10.0, true);
$pb = $body->AddPageBreak($pageFormat);


// Add a paragraph of text 10 times to the report body to force an automatic page break
for ($i = 0; $i < 10; $i++) {
    $body->AddText("Paragraph number " . $i + 1, $tsNormal);
    $body->AddText($text, $tsNormal);
    $body->AddVDistance(3.0);
}



// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_009.pdf", 'I');
