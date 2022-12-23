<?php
/*
 * //============================================================+
 * // File name     : example_023.php
 * // Version       : 1.0.0
 * // Last Update   : 20.12.22, 11:07
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

// get NORMAL text style
$tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);

// Text paragraph
$text = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.\n";

// Get ref to the report body
$body = $report->getBody();

$fix = new ReportLib\FixposFrame(120.0, 50.0);

$box = new ReportLib\BoxFrame();
$box->setWidth(50.0);
$box->setHeight(70.0);
$box->setBorderPen(new ReportLib\Pen(0.1, "#FF0000"));

$fix->addFrame($box);
$body->addFrame($fix);
$body->AddVDistance(10.0);

$body->AddText($text, $tsNormal);

$fix = new ReportLib\FixposFrame(60.0, 130.0);

$box = new ReportLib\BoxFrame();
$box->setWidth(100.0);
$box->setHeight(50.0);
$box->setPadding(1.0);
$box->setBorderPen(new ReportLib\Pen(0.1, "#CCCCCC"));
$fix->addFrame($box);

$body->addFrame($fix);
$body->AddVDistance(10.0);

$body->AddText($text, $tsNormal);


// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_023.pdf", 'I');
