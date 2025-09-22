<?php
/*
 * //============================================================+
 * // File name     : example_029.php
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
$report = new ReportLib\Report();

addHeader($report);
addFooter($report);
addText($report);
addTable($report);
addSource($report);

// Produce the output of the report
$report->output(__DIR__ . "/example_029.pdf", 'I');


/**
 * Adds the header to the report
 * @param ReportLib\Report $report
 * @return void
 */
function addHeader(ReportLib\Report $report): void
{
    $header = $report->getHeader();

    $vc = $header->AddVContainer();
    $vc->setMarginBottom(5.0);

    $box = $vc->AddBox();
    $box->setUseFullWidth(true);
    $box->setHeight(15.0);

    $if = $box->AddImage("logo2.png", true, 0.0, 10.0);
    $if->setVAlignment('M');
    $if->setHAlignment('R');
}


/**
 * Adds the footer to the report
 * @param ReportLib\Report $report
 * @return void
 */
function addFooter(ReportLib\Report $report): void
{
    $footer = $report->getFooter();

    $tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

    $vc = $footer->AddVContainer();
    $vc->setMarginTop(5.0);

    $box = $vc->AddBox();
    $box->setUseFullWidth(true);

    $tf = $box->AddText("Adiuvaris    -    At the lake 901a    -    18957 Lakeside    -    100 000 00 01", $tsBold);
    $tf->setHAlignment('C');
    $tf->setVAlignment('B');
}


/**
 * Add the title texts
 * @param ReportLib\Report $report
 * @return void
 */
function addText(ReportLib\Report $report): void
{
    $tsHeading1 = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING1);
    $tsHeading2 = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING2);
    $tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

    $body = $report->getBody();

    $body->AddText("Project Examples", $tsHeading1);
    $body->AddVDistance(10.0);

    $body->AddText("PHP library 'ReportLib'", $tsHeading2);
    $body->AddText("PHP library for dynamic PDF reports using the TCPDF library", $tsBold);

    $body->AddVDistance(20.0);
}


/**
 * Adds a table with all example files
 * @param ReportLib\Report $report
 * @return void
 */
function addTable(ReportLib\Report $report): void
{
    $tsHeading2 = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING2);
    $tsSmall = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::SMALLNORMAL);
    $tsSmall->setFontFamily('Courier');

    $body = $report->getBody();

    $table = $body->AddTable();
    $table->setUseFullWidth(true);

    // Add four columns to the table
    $table->addColumn("name", "Filename", 40.0);
    $table->addColumn("lines", "Number of lines", 30.0, 'R');
    $table->addColumn("modi", "Last modification", 30.0, 'C');
    $table->addColumn("size", "Filesize (Byte)", 20.0, 'R');

    $files = array_diff(scandir(__DIR__), array(".", ".."));
    foreach ($files as $file) {
        if (!str_starts_with($file, "example")) {
            continue;
        }
        $fileSize = filesize(__DIR__ . '/' . $file);
        $fileTime = filemtime($file);
        $content = file($file);

        $row = new ReportLib\TableRow('D');
        $row->setText("name", $file);
        $row->setText("lines", count($content));
        $row->setText("modi", date("d.m.Y", $fileTime));
        $row->setText("size", $fileSize);
        $table->addDataRow($row);
    }
}

/**
 * Adds the source code of the first three example files
 * on separate pages.
 * @param ReportLib\Report $report
 * @return void
 */
function addSource(ReportLib\Report $report): void
{
    $tsHeading2 = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING2);
    $tsSmall = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::SMALLNORMAL);
    $tsSmall->setFontFamily('Courier');

    $body = $report->getBody();

    $num = 0;
    $files = array_diff(scandir(__DIR__), array(".", ".."));
    foreach ($files as $file) {

        if (!str_starts_with($file, "example")) {
            continue;
        }

        $num++;
        if ($num > 3) {
            break;
        }
        $body->AddPageBreak();
        $body->AddText("Content of file '" . $file . "'", $tsHeading2);
        $body->AddVDistance(5.0);

        $content = file($file);
        foreach ($content as $line) {
            if (strlen($line) > 1 && $line[1] == '*') {
                continue;
            }
            $body->AddText($line, $tsSmall);
        }
    }
}
