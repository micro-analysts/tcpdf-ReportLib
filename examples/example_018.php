<?php
/*
 * //============================================================+
 * // File name     : example_018.php
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

// Get the default text style
$tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
$tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

$tsHeader = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_HEADER);
$tsHeader->setSize(11.0);

$tsSubtotal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_SUBTOTAL);
$tsSubtotal->setFontFamily("Courier");

$tsTotal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_TOTAL);
$tsTotal->setFontFamily("Times");

$tsRow = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_ROW);
$tsRow->setTextColor("#0000FF");

// Get ref to the report body
$body = $report->getBody();

// Add the table
$table = $body->AddTable();

// Add four columns to the table
$table->addColumn("frametype", "Frame type", 40.0);
$table->addColumn("container", "Container type", 30.0, 'C');
$table->addColumn("description", "Description", 60.0);
$table->addColumn("number", "Number", 20.0, 'R');

// Add a data row to the table with the column widths
$row = new ReportLib\TableRow('S');
$row->setText("frametype", "width 40mm");
$row->setText("container", "width 30mm");
$row->setText("description", "v60mm");
$row->setText("number", "width 20mm");
$table->addDataRow($row);

// Add a data row to the table
$row = new ReportLib\TableRow('D');
$row->setText("frametype", "LineFrame");
$row->setText("container", "No");
$row->setText("description", "This frame type represents a line on the report.");
$row->setText("number", "1");
$table->addDataRow($row);

// Add a second data row to the table
$row = new ReportLib\TableRow('D');
$row->setText("frametype", "SerialFrame");
$row->setText("container", "Yes");
$row->setText("description", "This is a frame container for a series of frames which will be printed one after the other.");
$row->setText("number", "2");
$table->addDataRow($row);

// Add a third data row to the table
$row = new ReportLib\TableRow('D');
$row->setText("frametype", "TextFrame");
$row->setText("container", "No");
$row->setText("description", "A simple frame type to print text.");
$row->setText("number", "3");
$table->addDataRow($row);

// Add a total data row to the table
$row = new ReportLib\TableRow('T');
$row->setText("frametype", "Total");
$row->setText("container", "");
$row->setText("description", "");
$row->setText("number", "100.00");
$table->addDataRow($row);

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_018.pdf", 'I');

