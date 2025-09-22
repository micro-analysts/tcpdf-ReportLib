<?php
/*
 * //============================================================+
 * // File name     : example_005.php
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

// Print example text with all predefined text styles
$body->AddText("NORMAL (Helvetica, 9 points)", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL));
$body->AddText("BOLD is the NORMAL style but bold font face", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD));
$body->AddText("ITALIC is the NORMAL style but italic", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::ITALIC));
$body->AddText("UNDERLINE is the NORMAL style but underlined", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::UNDERLINE));
$body->AddText("SMALLNOMRMAL is the NORMAL style but one point smaller", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::SMALLNORMAL));
$body->AddText("SMALLBOLD is the NORMAL style but one point smaller and bold face", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::SMALLBOLD));
$body->AddText("HEADING1 is the NORMAL style but nine points taller and bold face", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING1));
$body->AddText("HEADING2 is the NORMAL style but six points taller and bold face", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING2));
$body->AddText("HEADING3 is the NORMAL style but three points taller and bold face and italic", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING3));
$body->AddText("HEADING4 is the NORMAL style but one point taller and bold face and italic", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING4));
$body->AddText("TABLE_HEADER is the NORMAL style but one point smaller and bold", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_HEADER));
$body->AddText("TABLE_ROW is the NORMAL style but one point smaller", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_ROW));
$body->AddText("TABLE_SUBTOTAL is the NORMAL style but one point smaller and italic", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_SUBTOTAL));
$body->AddText("TABLE_TOTAL is the NORMAL style but one point smaller and bold", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::TABLE_TOTAL));
$body->AddText("FOOTER is the NORMAL style but one point smaller", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::FOOTER));
$body->AddText("HEADER is the NORMAL style but one point smaller", ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADER));

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_005.pdf", 'I');
