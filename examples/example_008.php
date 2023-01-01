<?php
/*
 * //============================================================+
 * // File name     : example_008.php
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

// Print a default line
$body->AddHLine();
$body->AddVDistance(5.0);

// Print a default line but with an extent of 1mm
$body->AddHLine(1.0);
$body->AddVDistance(5.0);

// Print a centered line
$body->AddLine(100.0, 'H', 'C', 'T', 0.5, "#00FF00");
$body->AddVDistance(5.0);

// Print a left aligned dashed line
$lf = new ReportLib\LineFrame('H');
$pen = new ReportLib\Pen(0.2, "#FF0000", 'dash');
$lf->setPen($pen);
$lf->setLength(120.0);
$body->addFrame($lf);
$body->AddVDistance(5.0);

// Print a right aligned dotted line
$lf = new ReportLib\LineFrame('H');
$pen = new ReportLib\Pen(0.2, "#0000FF", 'dot');
$lf->setPen($pen);
$lf->setLength(50.0);
$lf->setHAlignment('R');
$body->addFrame($lf);
$body->AddVDistance(5.0);

// Print a grey dash-dotted line
$lf = new ReportLib\LineFrame('H');
$pen = new ReportLib\Pen(0.2, "#CCCCCC", 'dashdot');
$lf->setPen($pen);
$body->addFrame($lf);
$body->AddVDistance(5.0);

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_008.pdf", 'I');

