<?php
/*
 * //============================================================+
 * // File name     : example_021.php
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

// Text in box
$body->AddText("", $tsBold);
$body->AddVDistance(5.0);
$body->AddTextInBox(50.0, $text, $tsNormal);
$body->AddVDistance(10.0);

// Bold text in a wider box
$body->AddTextInBox(120.0, $text, $tsBold);
$body->AddVDistance(10.0);

// Centered text in a box
$body->AddTextInBox(40.0, $text, $tsNormal, 'C');
$body->AddVDistance(10.0);

// Justified text in box with a border and a right margin
$box = $body->AddBox(140.0, true, 0.1);
$box->setMarginLeft(20.0);
$box->setPadding(1.0);
$box->AddText($text, $tsNormal, true, 'J');
$body->AddVDistance(10.0);

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_021.pdf", 'I');
