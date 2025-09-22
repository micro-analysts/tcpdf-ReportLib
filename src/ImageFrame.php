<?php
/*
 * //============================================================+
 * // File name     : ImageFrame.php
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

use Exception;

include_once "ReportFrame.php";


/**
 * @class ImageFrame
 * Class for a frame containing an image. It is a simple frame with no sub-frames in it.
 * An image (jpeg, png) will be printed in a rectangle with a given width and height.
 * @brief Class representing an image in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class ImageFrame extends ReportFrame
{
    /**
     * Image file
     * @var string
     */
    protected string $fileName;

    /**
     * Flag if the image may be stretched or not
     * @var bool
     */
    protected bool $preserveAspectRatio;

    /**
     * Calculated rectangle of the image
     * @var Rect
     */
    protected Rect $imageRect;

    /**
     * Real width of the image
     * @var float
     */
    protected float $width;

    /**
     * Real height of the image
     * @var float
     */
    protected float $height;

    /**
     * Class constructor
     * @param string $fileName Image file
     * @param float $maxWidth max width of the image in the printed report
     * @param float $maxHeight max height of the image in the printed report
     * @param bool $preserveAspectRatio Flag if the image may be stretched or not
     */
    public function __construct(string $fileName, float $maxWidth = 0.0, float $maxHeight = 0.0, bool $preserveAspectRatio = true)
    {
        parent::__construct();

        $this->fileName = $fileName;
        $this->preserveAspectRatio = $preserveAspectRatio;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return bool
     */
    public function isPreserveAspectRatio(): bool
    {
        return $this->preserveAspectRatio;
    }

    /**
     * @param bool $preserveAspectRatio
     */
    public function setPreserveAspectRatio(bool $preserveAspectRatio): void
    {
        $this->preserveAspectRatio = $preserveAspectRatio;
    }


    /**
     * Calculates the rectangle for the image so that it fits into the given rectangle
     * with the correct aspect ratio
     * @param Rect $rect
     * @return Rect
     */
    protected function getImageRect(Rect $rect): Rect
    {
        $maxSize = $rect->getSize();
        $scaleW = $maxSize->width / $this->width;
        $scaleH = $maxSize->height / $this->height;
        if ($this->preserveAspectRatio) {
            $scale = min($scaleW, $scaleH);
            $scaleW = $scale;
            $scaleH = $scale;
        }
        $width = $scaleW * $this->width;
        $height = $scaleH * $this->height;
        $imgSize = new Size($width, $height);

        return $rect->getRectWithSizeAndAlign($imgSize, $this->hAlignment, $this->vAlignment);
    }

    /**
     * Adjusts the rectangle for the given situation in the report
     * @param Rect $originalRect
     * @param Rect $newRect
     * @return SizeState
     */
    protected function rectChanged(Rect $originalRect, Rect $newRect): SizeState
    {
        $this->imageRect = $this->getImageRect($newRect);

        $sizeState = new SizeState();
        $sizeState->requiredSize = $this->imageRect->getSize();
        $sizeState->fits = $newRect->sizeFits($sizeState->requiredSize);
        $sizeState->continued = false;
        return $sizeState;
    }

    /**
     * Calculates the size of the image for the given rectangle
     * @param Renderer $r Class that can add the image to the report
     * @param Rect $forRect Rect in which the image has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeState = new SizeState();
        $this->imageRect = $this->getImageRect($forRect);
        $sizeState->requiredSize = $this->imageRect->getSize();
        $sizeState->fits = $forRect->sizeFits($sizeState->requiredSize);
        $sizeState->continued = false;
        return $sizeState;
    }

    /**
     * Prints the image into the calculated rectangle
     * @param Renderer $r Class that can add the image to the report
     * @param Rect $inRect Rect into which the image will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        if (strlen($this->fileName) > 0) {
            $r->addImage($this->fileName, $this->imageRect->left, $this->imageRect->top, $this->imageRect->getWidth(), $this->imageRect->getHeight());
        }
    }

    /**
     * Will be called when the printing begins
     * For image frames there is nothing to do here.
     * @param Renderer $r
     * @return void
     * @throws Exception
     */
    protected function doBeginPrint(Renderer $r): void
    {
        // Determine the real size of the image
        if (file_exists($this->fileName)) {
            $size = getimagesize($this->fileName);
            if ($size) {
                $this->width = $size[0];
                $this->height = $size[1];
            } else {
                throw new Exception("Image file seems not to be a valid image");
            }
        } else {
            throw new Exception("Image file does not exist.");
        }
    }
}
