<?php
/*
 * //============================================================+
 * // File name     : example_019.php
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

// Add the table
$table = $body->AddTable();
$table->setInterRowSpace(1.0);

// Add four columns to the table
$table->addColumn("frametype", "Frame type", 40.0);
$table->addColumn("container", "Container type", 30.0, 'C');
$table->addColumn("description", "Description", 60.0);
$table->addColumn("number", "Number", 20.0, 'R');

// Add a data row to the table with the column widths
$row = new ReportLib\TableRow('D');
$row->setText("frametype", "40mm width");
$row->setText("container", "30mm width");
$row->setText("description", "60mm width");
$row->setText("number", "20mm width");
$table->addDataRow($row);

// Add 60 data rows to the table
for ($i = 0; $i < 60; $i++) {
    $row = new ReportLib\TableRow('D');
    $row->setText("frametype", "LineFrame");
    $row->setText("container", "No");
    $row->setText("description", "This frame type represents a line on the report.");
    $row->setText("number", $i + 1);
    $table->addDataRow($row);
}

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_019.pdf", 'I');

