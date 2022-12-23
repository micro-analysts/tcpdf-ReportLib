<?php
/*
 * //============================================================+
 * // File name     : example_007.php
 * // Version       : 1.0.0
 * // Last Update   : 20.12.22, 08:26
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

// Create a text style with the name "MyTextStyle1", 36 points tall and red
$ts = ReportLib\TextStyles::addTextStyle("MyTextStyle1", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL));
$ts->setSize(36.0);
$ts->setTextColor("#FF0000");

// Print text using "MyTextStyle1"
$body->AddText("Very big red text style. Only the size and the color have been adjusted. The other attributes come from the NORMAL text style.", ReportLib\TextStyles::getTextStyle("MyTextStyle1"));
$body->AddVDistance(2.0);

// Create a text style with the name "MyTextStyle2", size 11 points and grey background color
$ts = ReportLib\TextStyles::addTextStyle("MyTextStyle2", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL));
$ts->setSize(11.0);
$ts->setBackgroundColor("#DDDDDD");

// Print text using "MyTextStyle2"
$body->AddText("A text style with a grey background color. Only the background color and the size have been adjusted. The other attributes come from the NORMAL text style.", ReportLib\TextStyles::getTextStyle("MyTextStyle2"));
$body->AddVDistance(2.0);

// Create a text style with the name "MyTextStyle3" - change all attributes of the base style
$ts = ReportLib\TextStyles::addTextStyle("MyTextStyle3", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL));
$ts->setSize(16.0);
$ts->setBold(true);
$ts->setItalic(true);
$ts->setUnderline(true);
$ts->setFontFamily("Courier");
$ts->setTextColor("#0000FF");
$ts->setBackgroundColor("#DDDDDD");

// Print text using "MyTextStyle3"
$body->AddText("A 16 point bold, italic and underlined Courier text style with a grey background and a blue font color - so all possible attributes have been adjusted.", ReportLib\TextStyles::getTextStyle("MyTextStyle3"));

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_007.pdf", 'I');
