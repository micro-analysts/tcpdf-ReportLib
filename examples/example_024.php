<?php
/*
 * //============================================================+
 * // File name     : example_024.php
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
$report->setCountPages(true);

// Get ref to the report body
$body = $report->getBody();
$header = $report->getHeader();
$footer = $report->getFooter();

addHeader($header);
addFooter($footer);

$body->AddPageBreak();

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_024.pdf", 'I');


function addHeader(ReportLib\SerialFrame $header) : void
{
    $tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

    $vc = $header->AddVContainer();
    $vc->setMarginBottom(5.0);

    $box = $vc->AddBox();
    $box->setUseFullWidth(true);
    $box->setPadding(1.0);
    $box->setHeight(15.0);
    $box->setBackground("#EEEEEE");

    $hc = $box->AddHContainer();
    $hc->setUseFullWidth(true);
    $hc->setUseFullHeight(true);

    $if = $hc->AddImage("logo.png", true, 0.0, 10.0);
    $if->setVAlignment('M');

    $tf = $hc->AddText("Fancy report\nwith a header and a footer", $tsBold);
    $tf->setHAlignment('R');
    $tf->setVAlignment('T');

    $vc->AddHLine(0.3,"#00FF00");
}


function addFooter(ReportLib\SerialFrame $footer) : void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);

    $vc = $footer->AddVContainer();
    $vc->setMarginTop(5.0);

    $lf = $vc->AddHLine(0.3);

    $box = $vc->AddBox();
    $box->setUseFullWidth(true);
    $box->setHeight(10.0);

    $tf = $box->AddText("Page [VAR_PAGE] of [VAR_TOTAL_PAGES]", $tsNormal);
    $tf->setHAlignment('R');
    $tf->setVAlignment('T');
}