<?php
/*
 * //============================================================+
 * // File name     : BarcodeFrame.php
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

namespace MicroAnalysts\TcpdfReportLib;

include_once "ReportFrame.php";


/**
 * @class BarcodeFrame
 * Class for a frame containing a barcode. It is a simple frame with no sub-frames in it.
 * A barcode (e.g. "QRCODE") will be printed in a rectangle with a given width and height.
 * @brief Class representing a barcode in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class BarcodeFrame extends ReportFrame
{
    /**
     * Barcode data
     * @var string
     */
    protected string $barcodeText;

    /**
     * Barcode Type
     * @var string
     */
    protected string $barcodeType;

    /**
     * Width of the frame
     * @var float
     */
    protected float  $width;

    /**
     * Height of the frame
     * @var float
     */
    protected float  $height;

    /**
     * Calculated rectangle for the barcode
     * @var Rect
     */
    protected Rect $barcodeRect;

    /**
     * Class constructor
     * @param string $barcodeText The data in the barcode
     * @param string $barcodeType The barcode type - see TCPDF 2d-barcode-types (e.g. 'QRCODE')
     * @param float $maxWidth Width of the barcode
     * @param float $maxHeight Height of the barcode
     */
    public function __construct(string $barcodeText, string $barcodeType, float $maxWidth, float $maxHeight)
    {
        parent::__construct();

        $this->barcodeText = $barcodeText;
        $this->barcodeType = $barcodeType;
        $this->width = $maxWidth;
        $this->height = $maxHeight;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * @return string
     */
    public function getBarcodeText(): string
    {
        return $this->barcodeText;
    }

    /**
     * @param string $barcodeText
     */
    public function setBarcodeText(string $barcodeText): void
    {
        $this->barcodeText = $barcodeText;
    }

    /**
     * @return string
     */
    public function getBarcodeType(): string
    {
        return $this->barcodeType;
    }

    /**
     * @param string $barcodeType
     */
    public function setBarcodeType(string $barcodeType): void
    {
        $this->barcodeType = $barcodeType;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    /**
     * Calculates the rectangle for the barcode
     * @param Rect $rect The max dimensions for the barcode
     * @return Rect Rectangle with real coordinates in the report
     */
    protected function getBarcodeRect(Rect $rect): Rect
    {
        $maxSize = $rect->getSize();
        $scaleW = $maxSize->width / $this->width;
        $scaleH = $maxSize->height / $this->height;
        $scale = min($scaleW, $scaleH);
        $scaleW = $scale;
        $scaleH = $scale;
        $width = $scaleW * $this->width;
        $height = $scaleH * $this->height;
        $bcSize = new Size($width, $height);

        return $rect->getRectWithSizeAndAlign($bcSize, $this->hAlignment, $this->vAlignment);
    }

    /**
     * Adjusts the rectangle for the given situation in the report
     * @param Rect $originalRect
     * @param Rect $newRect
     * @return SizeState
     */
    protected function rectChanged(Rect $originalRect, Rect $newRect): SizeState
    {
        $this->barcodeRect = $this->getBarcodeRect($newRect);

        $sizeState = new SizeState();
        $sizeState->requiredSize = $this->barcodeRect->getSize();
        $sizeState->fits = $newRect->sizeFits($sizeState->requiredSize);
        $sizeState->continued = false;
        return $sizeState;
    }

    /**
     * Calculates the size of the barcode for the given rectangle
     * @param Renderer $r Class that can add the barcode to the report
     * @param Rect $forRect Rect in which the barcode has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeState = new SizeState();
        $this->barcodeRect = $this->getBarcodeRect($forRect);
        $sizeState->requiredSize = $this->barcodeRect->getSize();
        $sizeState->fits = $forRect->sizeFits($sizeState->requiredSize);
        $sizeState->continued = false;
        return $sizeState;
    }

    /**
     * Prints the barcode into the calculated rectangle
     * @param Renderer $r Class that can add the barcode to the report
     * @param Rect $inRect Rect into which the barcode will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        if (strlen($this->barcodeText) > 0) {
            $r->addBarCode($this->barcodeText, $this->barcodeType, $this->barcodeRect->left, $this->barcodeRect->top, $this->barcodeRect->getWidth(), $this->barcodeRect->getHeight());
        }
    }

    /**
     * Will be called when the printing begins
     * For barcode frames there is nothing to do here.
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r): void {}
}
