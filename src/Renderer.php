<?php
/*
 * //============================================================+
 * // File name     : Renderer.php
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

namespace Adi\ReportLib;

include_once "Rect.php";
include_once "PageFormat.php";
include_once "TextStyle.php";
include_once "Renderer.php";

if (@file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require __DIR__ . "/../vendor/autoload.php";
} else if (@file_exists(__DIR__ . "/../../../autoload.php")) {
    require __DIR__ . "/../../../autoload.php";
}

use TCPDF;
use TCPDF_COLORS;
use TCPDF_STATIC;



/**
 * @class Renderer
 * This class is an abstraction layer for the TCPDF library
 * It contains the functionality that is needed by the ReportLib
 * to create PDF documents
 * @brief Abstraction layer of TCPDF for the ReportLib
 * @author Michael Hodel - info@adiuvaris.ch
 */
class Renderer
{
    /**
     * Instance of TCPDF class
     * @var TCPDF
     */
    protected TCPDF $pdf;

    /**
     * Number of the current page during calculation and printing
     * @var int
     */
    protected int $currentPage;

    /**
     * Number of total pages in the report
     * @var int
     */
    protected int $totalPages;

    /**
     * Flag if the pages are already counted
     * @var bool
     */
    protected bool $pagesCounted;

    /**
     * An array with PageFormats
     * @var array
     */
    protected array $pageFormats;

    /**
     * Class constructor
     * @param PageFormat $pageFormat
     */
    public function __construct(PageFormat $pageFormat)
    {
        $this->currentPage = 0;
        $this->totalPages = 0;
        $this->pagesCounted = false;

        $this->pageFormats = array();
        $this->pageFormats[0] = $pageFormat;
    }

    /**
     * Returns the printable area on the given page
     * If no page number is given it will use the current page
     * @param int $page Page number
     * @return Rect Printable area
     */
    public function getPageBounds(int $page = 0): Rect
    {
        if ($page == 0) {
            $page = $this->getCurrentPage();
        }

        $pageFormat = $this->getPageFormat($page);

        $size = $this->getPaperSize($page);

        if ($pageFormat->isMirrorMargins()) {
            if ($page % 2 == 0) {
                $pageBounds = new Rect($pageFormat->getMarginRight(), $pageFormat->getMarginTop(), $size->width - $pageFormat->getMarginLeft(), $size->height - $pageFormat->getMarginBottom());
            } else {
                $pageBounds = new Rect($pageFormat->getMarginLeft(), $pageFormat->getMarginTop(), $size->width - $pageFormat->getMarginRight(), $size->height - $pageFormat->getMarginBottom());
            }
        } else {
            $pageBounds = new Rect($pageFormat->getMarginLeft(), $pageFormat->getMarginTop(), $size->width - $pageFormat->getMarginRight(), $size->height - $pageFormat->getMarginBottom());
        }
        return $pageBounds;
    }


    /**
     * Returns the paper size in millimeters
     * If no page number is given it will use the current page
     * @param int $page Page number
     * @return Size Paper size
     */
    public function getPaperSize(int $page = 0): Size
    {
        if ($page == 0) {
            $page = $this->getCurrentPage();
        }

        $pageFormat = $this->getPageFormat($page);

        // calculate the page size in millimeters
        $w = 210.0;
        $h = 297.0;

        if (key_exists($pageFormat->getPageSize(), TCPDF_STATIC::$page_formats)) {
            $si = TCPDF_STATIC::$page_formats[$pageFormat->getPageSize()];
            $w = $si[0]  * 25.4 / 72.0;
            $h = $si[1]  * 25.4 / 72.0;
        }

        if ($pageFormat->getPageOrientation() == 'P') {
            $size = new Size(round($w, 2), round($h, 2));
        } else {
            $size = new Size(round($h, 2), round($w, 2));
        }

        return $size;
    }

    /**
     * Returns the printable width on the page,
     * i.e. the width of the paper minus the left and right margins
     * @param int $page Page number
     * @return float
     */
    public function getPrintableWidth(int $page = 0): float
    {
        return $this->getPageBounds($page)->getWidth();
    }

    /**
     * Returns the printable height on the page,
     * i.e. the height of the paper minus the top and bottom margins
     * @param int $page Page number
     * @return float
     */
    public function getPrintableHeight(int $page = 0): float
    {
        return $this->getPageBounds($page)->getHeight();
    }

    /**
     * Sets the pageFormat that will be used from a given page on
     * @param int $fromPage
     * @param PageFormat $pageFormat
     * @return self
     */
    public function setPageFormat(int $fromPage, PageFormat $pageFormat): self
    {
        $this->pageFormats[$fromPage] = $pageFormat;
        return $this;
    }

    /**
     * Returns the pageFormat for a page
     * @param int $page
     * @return PageFormat
     */
    protected function getPageFormat(int $page): PageFormat
    {
        while (!key_exists($page, $this->pageFormats)) {
            $page--;
        }

        if (isset($this->pageFormats[$page])) {
            return $this->pageFormats[$page];
        }
        return $this->pageFormats[0];
    }


    /**
     * Returns the current page
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Returns the number of all pages
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Returns if the pages are counted
     * @return bool
     */
    public function isPagesCounted(): bool
    {
        return $this->pagesCounted;
    }

    /**
     * Sets the flag that the pages are counted
     * @param bool $pagesCounted
     * @return self
     */
    public function setPagesCounted(bool $pagesCounted): self
    {
        $this->pagesCounted = $pagesCounted;
        return $this;
    }

    /**
     * Adds a page to the PDF-document
     * @return void
     */
    public function addPage(): void
    {
        $this->currentPage++;

        if (!$this->pagesCounted) {
            $this->totalPages++;
        }

        $pageFormat = $this->getPageFormat($this->currentPage);
        $this->pdf->AddPage($pageFormat->getPageOrientation(), $pageFormat->getPageSize());
    }

    /**
     * Creates a new PDF document
     * @return void
     */
    public function createNewPDF(): void
    {
        if (isset($this->pdf)) {
            $this->pdf->Close();
        }
        $this->pdf = new TCPDF();
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->setAutoPageBreak(false);
        $this->pdf->setMargins(0.0, 0.0, 0.0);
        $this->pdf->setCellPadding(0.0);
        $this->pdf->setPageUnit('mm');
        $this->currentPage = 0;
        if (!$this->pagesCounted) {
            $this->totalPages = 0;
        }

        $this->addPage();
    }

    /**
     * Replaces variables for the current page number or the total number of pages
     * in the text.
     * @param string $text
     * @return string
     */
    public function replacePageVars(string $text): string
    {
        if (str_contains($text, "[VAR_PAGE]")) {
            $text = str_replace("[VAR_PAGE]", $this->currentPage, $text);
        }
        if (str_contains($text, "[VAR_TOTAL_PAGES]")) {
            $text = str_replace("[VAR_TOTAL_PAGES]", $this->totalPages, $text);
        }
        return $text;
    }


    /**
     * Produces the output, that can be a PDF-file or a preview of
     * the generated PDF
     * @param string $fileName
     * @param string $action
     * @return void
     */
    public function output(string $fileName, string $action): void
    {
        $this->pdf->Output($fileName, $action);
    }

    /**
     * Adds a line to the PDF document
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @param float $width
     * @param string $dash
     * @param string $color
     * @return void
     */
    public function addLine(float $x1, float $y1, float $x2, float $y2, float $width, string $dash, string $color): void
    {
        $style = array('width' => $width, 'cap' => 'butt', 'join' => 'miter', 'dash' => $dash, 'color' => $this->getColorArray($color));
        $this->pdf->Line($x1, $y1, $x2, $y2, $style);
    }

    /**
     * Adds a rectangle to the PDF document
     * @param Rect $rect
     * @param string $fillColor
     * @return void
     */
    public function addRect(Rect $rect, string $fillColor = "#C0C0C0"): void
    {
        $style = 'F';
        $borderStyle = array();
        $this->pdf->Rect($rect->left, $rect->top, $rect->getWidth(), $rect->getHeight(), $style, $borderStyle, $this->getColorArray($fillColor));
    }


    /**
     * Returns the height of a font
     * @param TextStyle $textStyle
     * @param string $hAlignment
     * @param string $vAlignment
     * @return float
     */
    public function getFontHeight(TextStyle $textStyle, string $hAlignment = 'L', string $vAlignment = 'T'): float
    {
        $testText = 'Qq';

        $this->pdf->startTransaction();
        $style = $this->getStyle($textStyle);
        $hAlign = $hAlignment;
        $vAlign = $vAlignment;

        $this->pdf->setFont($textStyle->getFontFamily(), $style, $textStyle->getSize());
        $start_y = $this->pdf->GetY();
        $this->pdf->MultiCell(20, 0, $testText, 0, $hAlign, false, 1, null, null, true, 0, false, false, 0, $vAlign);
        $end_y = $this->pdf->GetY();
        $height = $end_y - $start_y;
        $this->pdf = $this->pdf->rollbackTransaction();

        return $height;
    }

    /**
     * Calculates the size of a text
     * @param TextStyle $textStyle
     * @param string $textToPrint
     * @param string $hAlignment
     * @param string $vAlignment
     * @param float $maxWidth
     * @return Size
     */
    public function calcTextSize(TextStyle $textStyle, string $textToPrint, string $hAlignment = 'L', string $vAlignment = 'T', float $maxWidth = 0.0): Size
    {
        $size = new Size();

        $style = $this->getStyle($textStyle);
        $hAlign = $hAlignment;
        $vAlign = $vAlignment;

        $this->pdf->startTransaction();
        $this->pdf->setFont($textStyle->getFontFamily(), $style, $textStyle->getSize());
        $this->pdf->setXY(0.0, 0.0);
        $start_y = $this->pdf->GetY();
        $start_page = $this->pdf->getPage();

        if ($maxWidth != 0.0) {
            $this->pdf->MultiCell($maxWidth, 0.0, $textToPrint, 0, $hAlign, false, 1, null, null, true, 0, false, false, 0, $vAlign);
            $size->width = $maxWidth;
        } else {
            $ls = explode("\n", $textToPrint);

            $max = 0.0;
            foreach ($ls as $l) {
                $w = $this->pdf->GetStringWidth($l, $textStyle->getFontFamily(), $style, $textStyle->getSize());
                if ($w > $max) {
                    $max = $w;
                }
            }

            // fix rounding problems
            $size->width = $max * 1.01;

            $this->pdf->MultiCell($this->getPrintableWidth(), 0, $textToPrint, 0, $hAlign, false, 1, null, null, true, 0, false, false, 0, $vAlign);
        }

        $end_y = $this->pdf->GetY();
        $end_page = $this->pdf->getPage();
        $size->height = $end_y - $start_y + ($end_page - $start_page) * $this->getPrintableHeight();
        $this->pdf = $this->pdf->rollbackTransaction();

        return $size;
    }

    /**
     * Trims text if it does not fit
     * @param string $text
     * @param TextStyle $textStyle
     * @param bool $wrapText
     * @param float $width
     * @param string $hAlignment
     * @param string $vAlignment
     * @param float $height
     * @return string
     */
    public function trimText(string $text, TextStyle $textStyle, bool $wrapText, float $width, string $hAlignment = 'L', string $vAlignment = 'T', float $height = 0.0): string
    {
        $style = $this->getStyle($textStyle);

        $start_len = strlen($text);
        if ($start_len == 0 || !$wrapText) {
            return $text;
        }

        if ($height < 0.0) {
            return "";
        } else if ($height == 0.0) {

            $text_width = $this->pdf->GetStringWidth($text, $textStyle->getFontFamily(), $style, $textStyle->getSize());

            if (round($text_width, 2) > round($width, 2)) {

                $len = strlen($text);
                $factor = 0.5;
                $correction = 0.5;
                $lastText = $text;

                do {
                    $correction /= 2.0;

                    $checkText = substr($text, 0, (int)($len * $factor));
                    $text_width = $this->pdf->GetStringWidth($checkText, $textStyle->getFontFamily(), $style, $textStyle->getSize());

                    if (round($text_width, 2) <= round($width, 2)) {
                        if (strlen($checkText) == strlen($lastText)) {
                            $text = $checkText;
                            break;
                        }

                        $lastText = $checkText;
                        $factor = $factor + $correction;
                    } else {
                        $factor = $factor - $correction;
                    }
                } while (true);
            }
        } else {

            $text_height = $this->calcTextSize($textStyle, $text, $hAlignment, $vAlignment, $width);
            if (round($text_height->height, 2) > round($height, 2)) {
                $len = strlen($text);
                $factor = 0.5;
                $correction = 0.5;
                $lastText = $text;
                do {
                    $correction /= 2.0;

                    $checkText = substr($text, 0, (int)($len * $factor));
                    $text_height = $this->calcTextSize($textStyle, $checkText, $hAlignment, $vAlignment, $width);

                    if (round($text_height->height, 2) <= round($height, 2)) {
                        if (strlen($checkText) == strlen($lastText)) {
                            $text = $checkText;
                            break;
                        }

                        $lastText = $checkText;
                        $factor = $factor + $correction;
                    } else {
                        $factor = $factor - $correction;
                    }
                } while (true);
            }
        }

        // If trimmed find the last full word
        if ((strlen($text) != $start_len)) {

            $saveText = $text;

            $search = " .:;,=|�+-/*@#[]{}<>()$\\%&?!\r\n\t";
            while (strlen($text) > 0) {
                $lastChar = substr($text, -1);

                if (strpbrk($lastChar, $search)) {
                    $text = rtrim($text);
                    break;
                }
                $text = substr($text, 0, -1);
            }
            if (strlen($text) == 0) {
                $text = $saveText;
            }
        }

        return $text;
    }


    /**
     * Adds text to the PDF document
     * @param string $text
     * @param TextStyle $textStyle
     * @param Rect $textLayout
     * @param string $hAlignment
     * @param string $vAlignment
     * @param string $textColor
     * @return float
     */
    public function addTextBlock(string $text, TextStyle $textStyle, Rect $textLayout, string $hAlignment = 'L', string $vAlignment = 'T', string $textColor = "#000000"): float
    {
        $style = $this->getStyle($textStyle);
        $this->pdf->setFont($textStyle->getFontFamily(), $style, $textStyle->getSize());
        $this->pdf->setTextColorArray($this->getColorArray($textColor));

        $start_y = $textLayout->top;
        $this->pdf->MultiCell($textLayout->getWidth(), $textLayout->getHeight(), $text, 0, $hAlignment, false, 1, $textLayout->left, $textLayout->top, true, 0, false, false, 0, $vAlignment);
        $end_y = $this->pdf->GetY();

        return $end_y - $start_y;
    }

    /**
     * Adds an image to the PDF document
     * @param string $fileName
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     * @return void
     */
    public function addImage(string $fileName, float $x, float $y, float $w, float $h): void
    {
        $this->pdf->Image($fileName, $x, $y, $w, $h, resize: true);
    }

    /**
     * Adds a barcode to the PDF document
     * @param string $code
     * @param string $barcodeType
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     * @return void
     */
    public function addBarCode(string $code, string $barcodeType, float $x, float $y, float $w, float $h): void
    {
        $style = array();
        $this->pdf->write2DBarcode($code, $barcodeType, $x, $y, $w, $h, $style);
    }

    /**
     * Returns the style for a font based on the textStyle from the ReportLib
     * @param TextStyle $textStyle
     * @return string
     */
    protected function getStyle(TextStyle $textStyle): string
    {
        $style = '';
        if ($textStyle->isBold()) {
            $style .= 'B';
        }
        if ($textStyle->isUnderline()) {
            $style .= 'U';
        }
        if ($textStyle->isItalic()) {
            $style .= 'I';
        }
        return $style;
    }


    /**
     * Returns an array of RGB for a color from the ReportLib
     * @param string $color
     * @return array|int[]
     */
    protected function getColorArray(string $color): array
    {
        // 6 hex chars would be ok
        if (strlen($color) == 6) {
            $color = '#' . $color;
        }

        // Are there only hex chars and a length of 7 chars
        if (strlen($color) != 7 || trim($color, '0..9A..Fa..f#') != '') {
            return array(0, 0, 0);
        }

        // 6-digit RGB hexadecimal representation '#RRGGBB'
        $r = max(0, min(255, hexdec(substr($color, 1, 2))));
        $g = max(0, min(255, hexdec(substr($color, 3, 2))));
        $b = max(0, min(255, hexdec(substr($color, 5, 2))));

        return array($r, $g, $b);
    }

    /**
     * Returns the Web-Color based on a color name
     * @param string $colorName
     * @return string
     */
    public function getColorByName(string $colorName): string
    {
        if (key_exists($colorName, TCPDF_COLORS::$webcolor)) {
            return "#" . TCPDF_COLORS::$webcolor[$colorName];
        }
        return "#000000";
    }
}
