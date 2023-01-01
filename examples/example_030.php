<?php
/*
 * //============================================================+
 * // File name     : example_030.php
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

$pageFormat = new ReportLib\PageFormat("A4", 'P', 25.0, 10.0, 25.0, 10.0);
$report = new ReportLib\Report($pageFormat);

$tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
$tsNormal->setSize(11.0);

addHeader($report);
addFooter($report);

printAddress($report);
printProjectObject($report);
printInvoiceData($report);
printTitle($report);
printInvoiceText($report);
printValues($report);
printPayable($report);
printTextEnd($report);
printGreetings($report);
printQRSlip($report);

try {
    $report->output(__DIR__ . "/example_030.pdf");
} catch (Exception $e) {
    echo($e);
}


/**
 * Adds the header which is printed on every page
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
 * Adds the footer to the report - printed only on the first page
 * @param ReportLib\Report $report
 * @return void
 */
function addFooter(ReportLib\Report $report): void
{
    $footer = $report->getFooter();

    $tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

    $pf = $footer->AddPageFrame(1);
    $pf->setMarginTop(5.0);

    $vc = $pf->AddVContainer();
    $box = $vc->AddBox();
    $box->setUseFullWidth(true);

    $tf = $box->AddText("Adiuvaris    -    At the lake 901a    -    00100 Lakeside    -    100 000 00 01", $tsBold);
    $tf->setHAlignment('C');
    $tf->setVAlignment('B');
}


/**
 * Prints the address with a FixposFrame for window envelope
 * @param ReportLib\Report $report
 * @return void
 */
function printAddress(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $b = new ReportLib\FixposFrame(120.0, 50.0);

    $adr = "Jane Doe\nSamplestreet 11b\n009900 Somewhere";

    $frame = $b->AddVContainer();
    $text = $frame->AddText($adr, $tsNormal);
    $text->setMarginBottom(20.0);

    $report->getBody()->addFrame($b);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printProjectObject(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

    $body = $report->getBody();

    $vFrame = $body->AddVContainer();
    $hFrame = $vFrame->AddHContainer();
    $hFrame->AddTextInBox(35.0, "Project", $tsNormal);
    $hFrame->AddText("Test Building", $tsBold);

    $hFrame = $vFrame->AddHContainer();
    $hFrame->AddTextInBox(35.0, "", $tsNormal);
    $title = "Example structure near the woods\n";
    $desc = "Apartment 45";
    $hFrame->AddText($title, $tsNormal);

    $hFrame = $vFrame->AddHContainer();
    $hFrame->AddTextInBox(35.0, "", $tsNormal);
    $hFrame->AddText($desc, $tsNormal);

    $object = "Apartment\nGarage";

    $vFrame->AddVDistance(2.0);
    $hFrame = $vFrame->AddHContainer();
    $hFrame->AddTextInBox(35.0, "Object", $tsNormal);
    $hFrame->AddText($object, $tsBold);

    $body->AddVDistance(5.0);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printInvoiceData(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsItalic = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::ITALIC);
    $tsItalic->setBold(true);

    $body = $report->getBody();

    $vFrame = $body->AddVContainer();

    $hFrame = $vFrame->AddHContainer();
    $hFrame->AddTextInBox(35.0, "Invoice number", $tsNormal);
    $hFrame->AddText("2022-12-123456", $tsItalic);

    $body->AddVDistance(2.0);

    $vFrame = $body->AddVContainer();
    $hFrame = $vFrame->AddHContainer();
    $hFrame->AddTextInBox(35.0, "Tax number", $tsNormal);
    $hFrame->AddText("YYY-000-111-222", $tsItalic);

    $body->AddVDistance(5.0);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printTitle(ReportLib\Report $report): void
{
    $tsHeading1 = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::HEADING1);

    $body = $report->getBody();
    $f = $body->AddHContainer();
    $f->AddText("Final Certificate", $tsHeading1);
    $body->AddVDistance(5.0);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printInvoiceText(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);

    $body = $report->getBody();
    $f = $body->AddHContainer();
    $f->AddText("According to the contract we allow ourselves to invoice as follows", $tsNormal);
    $body->AddVDistance(5.0);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printValues(ReportLib\Report $report): void
{
    // Names for the columns
    $COL_DESC = "desc";
    $COL_BASE = "base";
    $COL_FACTOR = "factor";
    $COL_CURR = "curr";
    $COL_VALUE = "value";

    $body = $report->getBody();

    $tab = $body->AddTable();
    $tab->setMargin(0.5);
    $tab->setInterRowSpace(0.5);
    $tab->setInnerPenTotalTop(new ReportLib\Pen(0.0));
    $tab->setSuppressHeaderRow(true);
    $tab->setMarginBottomSubtotal(1.2);
    $tab->setUseFullWidth(true);

    $tab->AddColumn($COL_DESC, "Description", 70.0);
    $tab->AddColumn($COL_BASE, "Base", 28, 'R', 2.0);
    $tab->AddColumn($COL_FACTOR, "Factor", 20.0);
    $tab->AddColumn($COL_CURR, "Currency", 10.0);
    $tab->AddColumn($COL_VALUE, "Amount", 30.0, 'R');

    $row = new ReportLib\TableRow('T');

    $row->setText($COL_DESC, "Apartment 45 & Garage");
    $row->setText($COL_CURR, "CHF");
    $row->setText($COL_VALUE, "350'000.00");
    $tab->addDataRow($row);

    $row = new ReportLib\TableRow('D');
    $row->setText($COL_DESC, "  ./. On Account");
    $row->setText($COL_CURR, "CHF");
    $row->setText($COL_VALUE, "-100'000.00");
    $tab->addDataRow($row);

    $row = new ReportLib\TableRow('T');
    $row->setText($COL_DESC, "Pre-tax");
    $row->setText($COL_CURR, "CHF");
    $row->setText($COL_VALUE, "250'000.00");
    $tab->addDataRow($row);

    $row = new ReportLib\TableRow('D');
    $row->setText($COL_DESC, "Tax");
    $row->setText($COL_FACTOR, "10.0%");
    $row->setText($COL_CURR, "CHF");
    $row->setText($COL_VALUE, "25'000.00");
    $tab->addDataRow($row);

    $row = new ReportLib\TableRow('T');
    $row->setText($COL_DESC, "Total");
    $row->setText($COL_CURR, "CHF");
    $row->setText($COL_VALUE, "275'000.00");
    $tab->addDataRow($row);

    $body->AddVDistance(8.0);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printPayable(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsBold = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::BOLD);

    $body = $report->getBody();
    $f = $body->AddHContainer();
    $f->AddTextInBox(35.0, "payable until", $tsNormal);
    $f->AddText("01.01.2024", $tsBold);
    $body->AddVDistance(1.0);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printTextEnd(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $body = $report->getBody();
    $f = $body->AddHContainer();
    $f->AddText("We thank you in advance for the transfer to our account.", $tsNormal);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function printGreetings(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsItalic = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::ITALIC);

    $body = $report->getBody();
    $f = $body->AddVContainer();
    $f->setMarginLeft(95.0);
    $f->AddVDistance(30.0);
    $f->AddText("Kind regards", $tsNormal);
    $f->AddVDistance(10.0);
    $f->AddText("Michael Hodel", $tsItalic);
    $f->AddText("Vice President", $tsNormal);
}


/**
 * @param ReportLib\Report $report
 * @return void
 */
function printQRSlip(ReportLib\Report $report): void
{
    $body = $report->getBody();

    $body->AddPageBreak();

    addQrCodeZ($report);
    addQRTitleE($report);
    addQRDataE($report);
    addQRValueE($report);
    addQRE($report);
    addQRTitleZ($report);
    addQRDataZ($report);
    addQRValueZ($report);
    addQrLines($report);
}


/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQrCodeZ(ReportLib\Report $report): void
{
    $qrTopOffset = 297.0 - 105.0;
    $f = new ReportLib\FixposFrame(67.0, $qrTopOffset + 17.0, true);
    $f->AddBarcode("CH0011112222333344448\nApartment 45\n275000\n01.01.2024\nJane Doe", "QRCODE", 46.0, 46.0);
    $report->getBody()->AddFrame($f);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQRTitleE(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $ts = ReportLib\TextStyles::addTextStyle("TitleE", $tsNormal);
    $ts->setBold(true);
    $ts->setSize(11.0);

    addQrText($report, "Empfangsschein", 5.0, 5.0, 52.0, $ts, 0.0);
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQRTitleZ(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $ts = ReportLib\TextStyles::addTextStyle("TitleE", $tsNormal);
    $ts->setBold(true);
    $ts->setSize(11.0);

    addQrText($report, "Zahlteil", 67.0, 5.0, 51.0, $ts, 0.0);
}


/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQRDataE(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsC = ReportLib\TextStyles::addTextStyle("CaptionE", $tsNormal);
    $tsC->setBold(true);
    $tsC->setSize(6.0);

    $tsV = ReportLib\TextStyles::addTextStyle("ValueE", $tsNormal);
    $tsV->setSize(8.0);

    $nextY = addQrText($report, "Konto / Zahlbar an", 5.0, 12.0, 52.0, $tsC, 9.0);
    $nextY = addQrText($report, "CH00 1111 2222 3333 4444 8", 5.0, $nextY, 52.0, $tsV, 9.0);
    $nextY = addQrText($report, "Adiuvaris", 5.0, $nextY, 52.0, $tsV, 9.0);
    $nextY = addQrText($report, "At the lake 901a", 5.0, $nextY, 52.0, $tsV, 9.0);
    $nextY = addQrText($report, "00100 Lakeside", 5.0, $nextY, 52.0, $tsV, 9.0);
    $nextY = addQrText($report, "", 5.0, $nextY, 52.0, $tsV, 9.0);

    $nextY = addQrText($report, "Zahlbar durch", 5.0, $nextY, 52.0, $tsC, 9.0);
    $nextY = addQrText($report, "Jane Doe", 5.0, $nextY, 52.0, $tsV, 9.0);
    $nextY = addQrText($report, "Samplestreet 11b", 5.0, $nextY, 52.0, $tsV, 9.0);
    $nextY = addQrText($report, "009900 Somewhere", 5.0, $nextY, 52.0, $tsV, 9.0);
}


/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQRDataZ(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsC = ReportLib\TextStyles::addTextStyle("CaptionE", $tsNormal);
    $tsC->setBold(true);
    $tsC->setSize(8.0);

    $tsV = ReportLib\TextStyles::addTextStyle("ValueE", $tsNormal);
    $tsV->setSize(10.0);

    $nextY = addQrText($report, "Konto / Zahlbar an", 118.0, 5.0, 92.0, $tsC, 11.0);
    $nextY = addQrText($report, "CH00 1111 2222 3333 4444 8", 118.0, $nextY, 92.0, $tsV, 11.0);
    $nextY = addQrText($report, "Adiuvaris", 118.0, $nextY, 92.0, $tsV, 11.0);
    $nextY = addQrText($report, "At the lake 901a", 118.0, $nextY, 92.0, $tsV, 11.0);
    $nextY = addQrText($report, "00100 Lakeside", 118.0, $nextY, 92.0, $tsV, 11.0);
    $nextY = addQrText($report, "", 118.0, $nextY, 92.0, $tsV, 11.0);

    $nextY = addQrText($report, "Zusätzliche Informationen", 118.0, $nextY, 92.0, $tsC, 11.0);
    $nextY = addQrText($report, "Apartment 45/275000/01.01.2024", 118.0, $nextY, 92.0, $tsV, 11.0);
    $nextY = addQrText($report, "", 118.0, $nextY, 92.0, $tsV, 11.0);

    $nextY = addQrText($report, "Zahlbar durch", 118.0, $nextY, 92.0, $tsC, 9.0);
    $nextY = addQrText($report, "Jane Doe", 118.0, $nextY, 92.0, $tsV, 9.0);
    $nextY = addQrText($report, "Samplestreet 11b", 118.0, $nextY, 92.0, $tsV, 9.0);
    $nextY = addQrText($report, "009900 Somewhere", 118.0, $nextY, 92.0, $tsV, 9.0);
}


/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQRValueE(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsC = ReportLib\TextStyles::addTextStyle("CaptionE", $tsNormal);
    $tsC->setBold(true);
    $tsC->setSize(6.0);

    $tsV = ReportLib\TextStyles::addTextStyle("ValueE", $tsNormal);
    $tsV->setSize(8.0);

    $nextY = addQrText($report, "Währung", 5.0, 68.0, 15.0, $tsC, 9.0);
    addQrText($report, "Betrag", 20.0, 68.0, 35.0, $tsC, 9.0);

    addQrText($report, "CHF", 5.0, $nextY, 35.0, $tsV, 11.0);
    addQrText($report, "275 000.00", 20.0, $nextY, 35.0, $tsV, 11.0);
}


/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQRValueZ(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsC = ReportLib\TextStyles::addTextStyle("CaptionE", $tsNormal);
    $tsC->setBold(true);
    $tsC->setSize(8.0);

    $tsV = ReportLib\TextStyles::addTextStyle("ValueE", $tsNormal);
    $tsV->setSize(10.0);

    $nextY = addQrText($report, "Währung", 67.0, 68.0, 15.0, $tsC, 11.0);
    addQrText($report, "Betrag", 82.0, 68.0, 35.0, $tsC, 9.0);

    addQrText($report, "CHF", 67.0, $nextY, 34.0, $tsV, 11.0);

    addQrText($report, "275 000.00", 82.0, $nextY, 34.0, $tsV, 11.0);
}


/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQRE(ReportLib\Report $report): void
{
    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $tsC = ReportLib\TextStyles::addTextStyle("CaptionE", $tsNormal);
    $tsC->setBold(true);
    $tsC->setSize(6.0);

    addQrText($report, "Annahmestelle", 5.0, 82.0, 52.0, $tsC, 8.0, 'R');
}

/**
 * @param ReportLib\Report $report
 * @return void
 */
function addQrLines(ReportLib\Report $report): void
{
    $qrTopOffset = 297.0 - 105.0;

    $tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);
    $ts = ReportLib\TextStyles::addTextStyle("TitleE", $tsNormal);
    $ts->setBold(true);
    $ts->setSize(8.0);

    addQrText($report, "Hier abtrennen", 0.0, -3.5, 210.0, $ts, 0.0, 'C');

    $f = new ReportLib\FixposFrame(0.0, $qrTopOffset, true);
    $f->AddHLine();
    $report->getBody()->AddFrame($f);

    $f = new ReportLib\FixposFrame(62.0, $qrTopOffset, true);
    $f->AddVLine();
    $report->getBody()->AddFrame($f);
}


/**
 * @param ReportLib\Report $report
 * @param string $text
 * @param float $x
 * @param float $y
 * @param float $w
 * @param ReportLib\TextStyle $ts
 * @param float $fontSize
 * @param string $hAlign
 * @return float
 */
function addQrText(ReportLib\Report $report, string $text, float $x, float $y, float $w, ReportLib\TextStyle $ts, float $fontSize, string $hAlign = 'L'): float
{
    $qrTopOffset = 297.0 - 105.0;
    $f = new ReportLib\FixposFrame($x, $qrTopOffset + $y, true);
    $tf = $f->AddTextInBox($w, $text, $ts, $hAlign);
    $report->getBody()->AddFrame($f);

    return $y + convertPtToMM($fontSize);
}


/**
 * @param float $pt
 * @return float
 */
function convertPtToMM(float $pt): float
{
    return $pt * 25.4 / 72.0;
}
