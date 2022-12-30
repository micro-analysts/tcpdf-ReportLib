<?php
/*
 * //============================================================+
 * // File name     : example_028.php
 * // Version       : 1.0.0
 * // Last Update   : 30.12.22, 06:35
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

// Get the default text style
$tsNormal = ReportLib\TextStyles::getTextStyle(ReportLib\TextStyles::NORMAL);

// Text paragraph
$text = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.";

$errorText = "";

// Endless loop because of circle reference in the structure
$box1 = $body->AddBox();
$box2 = $box1->AddBox();
$box2->addFrame($body);
try {
    $report->output(__DIR__ . "/example_028.pdf");
} catch (Exception $e) {
    $errorText .= $e;
    $errorText .=  "<br><br><br>";
    $body->clearFrames();
}

// Exception because of a frame that has to be kept together but is to height for a page.
$box = $body->AddBox();
$box->setKeepTogether(true);
$vc = $box->AddVContainer();
for ($i = 0; $i < 20; $i++) {
    $vc->AddText("Paragraph number " . $i+1, $tsNormal);
    $vc->AddText($text, $tsNormal);
    $vc->AddVDistance(2.0);
}
try {
    $report->output(__DIR__ . "/example_028.pdf");
} catch (Exception $e) {
    $errorText .= $e;
    $errorText .=  "<br><br><br>";
    $body->clearFrames();
}


// Exception because there is no space for a frame
$hc = $body->AddHContainer();
$hc->AddText($text, $tsNormal);
$hc->AddText($text, $tsNormal);

try {
    $report->output(__DIR__ . "/example_028.pdf");
} catch (Exception $e) {
    $errorText .= $e;
    $errorText .=  "<br><br><br>";
    $body->clearFrames();
}

// Exception because of FixposFrame offsets outside printable area
$f = new ReportLib\FixposFrame(0.0, 0.0);
$f->AddHLine();
$report->getBody()->AddFrame($f);
try {
    $report->output(__DIR__ . "/example_028.pdf");
} catch (Exception $e) {
    $errorText .= $e;
    $errorText .=  "<br><br><br>";
    $body->clearFrames();
}

// Exception because of Image does not exist
$f = new ReportLib\ImageFrame("");
$report->getBody()->AddFrame($f);
try {
    $report->output(__DIR__ . "/example_028.pdf");
} catch (Exception $e) {
    $errorText .= $e;
    $errorText .=  "<br><br><br>";
    $body->clearFrames();
}

echo $errorText;

