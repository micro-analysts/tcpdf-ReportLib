<?php
/*
 * //============================================================+
 * // File name     : example_025.php
 * // Version       : 1.0.0
 * // Last Update   : 20.12.22, 11:13
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
$report->setCountPages(true);

// Get ref to the report body
$body = $report->getBody();
$header = $report->getHeader();
$footer = $report->getFooter();

addHeader($header);
addFooter($footer, ReportLib\PageFrame::C_OnOddPages, 'R');
addFooter($footer, ReportLib\PageFrame::C_OnEvenPages, 'L');

$body->AddPageBreak();
$body->AddPageBreak();

// Produce the output of the report
//  uses the same params as TCPDF (F = File, I = Preview etc.)
$report->output(__DIR__ . "/example_025.pdf", 'I');


function addHeader(ReportLib\SerialFrame $header) : void
{
    $tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

    $pf = $header->AddPageFrame(ReportLib\PageFrame::C_OnAllButFirstPage);

    $box = $pf->AddBox();
    $box->setUseFullWidth(true);
    $box->setPadding(1.0);
    $box->setHeight(15.0);
    $box->setBackground("#EEEEEE");

    $hf = $box->AddHContainer();
    $tf = $hf->AddText("Header for all pages but not on the first page.", $tsBold);
}

function addFooter(ReportLib\SerialFrame $footer, int $onPageNr, string $hAlign) : void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);

    $pf = $footer->AddPageFrame($onPageNr);
    $pf->AddHLine(0.3);

    $box = $pf->AddBox();
    $box->setUseFullWidth(true);
    $box->setHeight(10.0);

    $tf = $box->AddText("Page [VAR_PAGE] of [VAR_TOTAL_PAGES]", $tsNormal);
    $tf->setHAlignment($hAlign);
    $tf->setVAlignment('T');
}

