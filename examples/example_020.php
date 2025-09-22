<?php
/*
 * //============================================================+
 * // File name     : example_020.php
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

use Adi\ReportLib as ReportLib;

// Create report instance
//  default format A4, portrait with margins left = 20mm, top = 10mm, right = 10mm and bottom = 10mm
$report = new ReportLib\Report();

// Get ref to the report body
$body = $report->getBody();

$box = new ReportLib\BoxFrame(70.0, 50.0, 0.1, "#FF0000");
$body->addFrame($box);
$body->AddVDistance(10.0);

$box = new ReportLib\BoxFrame(150.0, 20.0, 0.1, "#FF0000");
$box->getBorder()->getLeftPen()->setExtent(5.0);
$box->getBorder()->getRightPen()->setExtent(5.0);
$body->addFrame($box);
$body->AddVDistance(10.0);

$box = new ReportLib\BoxFrame(0.0, 10.0, 0.1, "#0000FF");
$box->setUseFullWidth(true);
$box->getBorder()->getTopPen()->setExtent(1.0);
$box->getBorder()->getLeftPen()->setExtent(1.0);
$box->getBorder()->getBottomPen()->setExtent(3.0);
$box->getBorder()->getBottomPen()->setColor("#FF00FF");
$box->getBorder()->getRightPen()->setExtent(3.0);
$box->getBorder()->getRightPen()->setColor("#FF00FF");
$body->addFrame($box);


// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_020.pdf", 'I');

