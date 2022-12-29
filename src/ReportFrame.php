<?php
/*
 * //============================================================+
 * // File name     : ReportFrame.php
 * // Version       : 1.0.0
 * // Last Update   : 29.12.22, 07:21
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

namespace Adi\ReportLib;

include_once "Size.php";
include_once "Rect.php";
include_once "SizeState.php";
include_once "Renderer.php";


/**
 * @class ReportFrame
 * Abstract base class for frames in a report
 * It contains all the attributes that are valid for all kind of
 * frame types, including alignments and margins.
 * @brief Class representing frame in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
abstract class ReportFrame
{
    /**
     * Parent frame or null
     * @var ?ReportFrame
     */
    protected ?ReportFrame $parentFrame;

    /**
     * Horizontal alignment
     * @var string
     */
    protected string $hAlignment;

    /**
     * Vertical alignment
     * @var string
     */
    protected string $vAlignment;

    /**
     * Flag if the full height of the surrounding frame should be used
     * @var bool
     */
    protected bool $useFullHeight;

    /**
     * Flag if the full width of the surrounding frame should be used
     * @var bool
     */
    protected bool $useFullWidth;

    /**
     * Left margin
     * @var float
     */
    protected float $marginLeft;

    /**
     * Top margin
     * @var float
     */
    protected float $marginTop;

    /**
     * Right margin
     * @var float
     */
    protected float $marginRight;

    /**
     * Bottom margin
     * @var float
     */
    protected float $marginBottom;

    /**
     * Max width of the frame
     * @var float
     */
    protected float $maxWidth;

    /**
     * Max height of the frame
     * @var float
     */
    protected float $maxHeight;

    /**
     * Flag if the frame may be spread over more than one pages
     * @var bool
     */
    protected bool $keepTogether;

    /**
     * Required calculated size
     * @var Size
     */
    protected Size $requiredSize;

    /**
     * Real size of the frame
     * @var Size
     */
    protected Size $size;

    /**
     * Flag if the frame will be continued on the next page
     * @var bool
     */
    protected bool $continued;

    /**
     * Flag if the frame fits into the given space
     * @var bool
     */
    protected bool $fits;

    /**
     * Flag if the frame size has been calculated
     * @var bool
     */
    protected bool $sized;

    /**
     * Flag if the printing phase has been started
     * @var bool
     */
    protected bool $startedPrinting;

    /**
     * Frame into which the frame should fit
     * @var Rect
     */
    protected Rect $sizingBounds;

    /**
     * Class constructor
     * The attributes will be init with
     * left alignment, top alignment, no margins, no size and not has to be kept together.
     */
    public function __construct()
    {
        $this->hAlignment = DEF_FRAME_H_ALIGNMENT;
        $this->vAlignment = DEF_FRAME_V_ALIGNMENT;
        $this->marginLeft = DEF_FRAME_MARGIN_LEFT;
        $this->marginTop = DEF_FRAME_MARGIN_TOP;
        $this->marginRight = DEF_FRAME_MARGIN_RIGHT;
        $this->marginBottom = DEF_FRAME_MARGIN_BOTTOM;
        $this->useFullHeight = false;
        $this->useFullWidth = false;
        $this->keepTogether = false;
        $this->maxWidth = 0.0;
        $this->maxHeight = 0.0;

        $this->continued = false;
        $this->fits = true;
        $this->sized = false;
        $this->startedPrinting = false;

        $this->parentFrame = null;
    }

    /**
     * @param ReportFrame|null $parentFrame
     */
    public function setParentFrame(?ReportFrame $parentFrame): void
    {
        $this->parentFrame = $parentFrame;
    }

    /**
     * @return string
     */
    public function getHAlignment(): string
    {
        return $this->hAlignment;
    }

    /**
     * @param string $hAlignment
     */
    public function setHAlignment(string $hAlignment): void
    {
        $this->hAlignment = $hAlignment;
    }

    /**
     * @return string
     */
    public function getVAlignment(): string
    {
        return $this->vAlignment;
    }

    /**
     * @param string $vAlignment
     */
    public function setVAlignment(string $vAlignment): void
    {
        $this->vAlignment = $vAlignment;
    }

    /**
     * @return bool
     */
    public function isUseFullHeight(): bool
    {
        return $this->useFullHeight;
    }

    /**
     * @param bool $useFullHeight
     */
    public function setUseFullHeight(bool $useFullHeight): void
    {
        $this->useFullHeight = $useFullHeight;
    }

    /**
     * @return bool
     */
    public function isUseFullWidth(): bool
    {
        return $this->useFullWidth;
    }

    /**
     * @param bool $useFullWidth
     */
    public function setUseFullWidth(bool $useFullWidth): void
    {
        $this->useFullWidth = $useFullWidth;
    }

    /**
     * @return float
     */
    public function getMarginLeft(): float
    {
        return $this->marginLeft;
    }

    /**
     * @param float $marginLeft
     */
    public function setMarginLeft(float $marginLeft): void
    {
        $this->marginLeft = $marginLeft;
    }

    /**
     * @return float
     */
    public function getMarginTop(): float
    {
        return $this->marginTop;
    }

    /**
     * @param float $marginTop
     */
    public function setMarginTop(float $marginTop): void
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @return float
     */
    public function getMarginRight(): float
    {
        return $this->marginRight;
    }

    /**
     * @param float $marginRight
     */
    public function setMarginRight(float $marginRight): void
    {
        $this->marginRight = $marginRight;
    }

    /**
     * @return float
     */
    public function getMarginBottom(): float
    {
        return $this->marginBottom;
    }

    /**
     * @param float $marginBottom
     */
    public function setMarginBottom(float $marginBottom): void
    {
        $this->marginBottom = $marginBottom;
    }

    /**
     * @return float
     */
    public function getMaxWidth(): float
    {
        return $this->maxWidth;
    }

    /**
     * @param float $maxWidth
     */
    public function setMaxWidth(float $maxWidth): void
    {
        $this->maxWidth = $maxWidth;
    }

    /**
     * @return float
     */
    public function getMaxHeight(): float
    {
        return $this->maxHeight;
    }

    /**
     * @param float $maxHeight
     */
    public function setMaxHeight(float $maxHeight): void
    {
        $this->maxHeight = $maxHeight;
    }

    /**
     * @return bool
     */
    public function isKeepTogether(): bool
    {
        return $this->keepTogether;
    }

    /**
     * @param bool $keepTogether
     */
    public function setKeepTogether(bool $keepTogether): void
    {
        $this->keepTogether = $keepTogether;
    }

    /**
     * @return bool
     */
    public function isContinued(): bool
    {
        return $this->continued;
    }

    /**
     * @return bool
     */
    public function isFits(): bool
    {
        return $this->fits;
    }

    /**
     * @return Rect
     */
    public function getSizingBounds(): Rect
    {
        if (isset($this->sizingBounds)) {
            return $this->sizingBounds;
        }

        return new Rect();
    }

    /**
     * Sets all four margins to the same given value
     * @param float $val
     * @return void
     */
    public function setMargin(float $val) : void
    {
        $this->marginTop = $val;
        $this->marginRight = $val;
        $this->marginBottom = $val;
        $this->marginLeft = $val;
    }

    /**
     * Checks if the frame creates an endless recursion loop of frames
     * @param array $list
     * @return bool
     */
    public function isEndless(array $list) : bool
    {
        return false;
    }

    /**
     * Returns the size of the frame
     * @return Size
     */
    public function getSize() : Size
    {
        if (isset($this->size)) {
            return $this->size;
        }
        return new Size();
    }

    /**
     * Resets the sized flag
     * @param bool $keepTogether
     * @return void
     */
    public function resetSize(bool $keepTogether) : void
    {
        $this->sized = false;
    }

    /**
     * Resets the frame
     * @return void
     */
    public function reset() : void
    {
        $this->startedPrinting = false;
        $this->sized = false;
        $this->fits = false;
        $this->continued = false;
    }

    /**
     * Initiate the printing phase
     * @param Renderer $r
     * @return void
     */
    public function beginPrint(Renderer $r) : void
    {
        if (!$this->startedPrinting) {
            $this->doBeginPrint($r);
            $this->startedPrinting = true;
        }
    }

    /**
     * Calculates the size of the frame
     * @param Renderer $r
     * @param Rect $rect
     * @return void
     * @throws \Exception
     */
    public function calcSize(Renderer $r, Rect $rect) : void
    {
        $this->beginPrint($r);
        if (!$this->sized) {
            $this->sizingBounds = $this->limitBounds($rect);
            $values = $this->doCalcSize($r, $this->sizingBounds);
            $this->setSize($values->requiredSize, $rect);
            if ($this->keepTogether && $values->continued) {
                $this->fits = false;

                // If the rect is equal to the pageBounds then the frame is too big to be kept together
                if ($r->getPageBounds() == $rect) {

                    // There is no way to print this report.
                    throw new \Exception("Too big keepTogether frame!");
                }
            } else {
                $this->fits = $values->fits;

                // If the frame does not fit, check if there is any space at all
                if (!$this->fits) {
                    if ($rect->isEmpty() || $rect->getWidth() == 0.0) {

                        // There is no way to print this report.
                        throw new \Exception("No space left in frame for another frame!");
                    }
                }
            }
            $this->continued = $values->continued;
            $this->sized = true;
        }
    }

    /**
     * Prints the frame
     * @param Renderer $r
     * @param Rect $rect
     * @return void
     */
    public function print(Renderer $r, Rect $rect) : void
    {
        $printingBounds = $this->limitBounds($rect);
        if ($this->sized && (!$printingBounds->isEqualTo( $this->sizingBounds))) {
            $values = $this->rectChanged($this->sizingBounds, $printingBounds);
            $this->setSize($values->requiredSize, $rect);
            $this->fits = $values->fits;
            $this->continued = $values->continued;
        }

        $this->calcSize($r, $rect);
        if ($this->fits) {
            $this->doPrint($r, $printingBounds);
        }
        $this->resetSize($this->keepTogether);
    }

    /**
     * Calculates the limited rectangle for the given frame size
     * @param Rect $toRect
     * @return Rect
     */
    protected function limitBounds(Rect $toRect) : Rect
    {
        $rect = new Rect(rect:$toRect);
        if (($this->maxWidth > 0.0) && ($rect->getWidth() > $this->maxWidth)) {
            $rect = $rect->getRectWithSizeAndAlign(new Size($this->maxWidth, $rect->getHeight()), $this->hAlignment, $this->vAlignment);
        }
        if (($this->maxHeight > 0.0) && ($rect->getHeight() > $this->maxHeight)) {
            $rect = $rect->getRectWithSizeAndAlign(new Size($rect->getWidth(), $this->maxHeight), $this->hAlignment, $this->vAlignment);
        }

        $marginLeft = 0.0;
        $marginTop = 0.0;
        $marginRight = 0.0;
        $marginBottom = 0.0;

        if ($this->hAlignment == 'L') {
            $marginLeft = $this->marginLeft;
        } else if ($this->hAlignment == 'C' || $this->hAlignment == 'J') {
            $marginLeft = $this->marginLeft;
            $marginRight = $this->marginRight;
        } else if ($this->hAlignment == 'R') {
            $marginRight = $this->marginRight;
        }

        if ($this->vAlignment == 'T') {
            $marginTop = $this->marginTop;
        } else if ($this->vAlignment == 'M') {
            $marginTop = $this->marginTop;
            $marginBottom = $this->marginBottom;
        } else if ($this->vAlignment == 'B') {
            $marginBottom = $this->marginBottom;
        }

        return $rect->getRectWithMargins($marginTop, $marginRight, $marginBottom, $marginLeft);
    }

    /**
     * Sets the size
     * @param Size $requiredSize
     * @param Rect $rect
     * @return void
     */
    public function setSize(Size $requiredSize, Rect $rect) : void
    {
        $this->requiredSize = new Size(size: $requiredSize);
        $this->size = new Size();
        if ($this->useFullWidth) {
            $this->size->width = $rect->getWidth();
        } else {
            $this->size->width = $this->requiredSize->width + $this->marginLeft + $this->marginRight;
        }

        if ($this->useFullHeight) {
            $this->size->height = $rect->getHeight();
        } else {
            $this->size->height = $this->requiredSize->height + $this->marginTop + $this->marginBottom;
        }

        if ($this->maxWidth > 0.0) {
            $this->size->width = min($this->size->width, $this->maxWidth);
        }
        $this->size->width = min($this->size->width, $rect->getWidth());

        if ($this->maxHeight > 0.0) {
            $this->size->height = min($this->size->height, $this->maxHeight);
        }
        $this->size->height = min($this->size->height, $rect->getHeight());
    }

    /**
     * Adjusts the rectangle for the given situation in the report
     * @param Rect $originalRect
     * @param Rect $newRect
     * @return SizeState
     */
    protected function rectChanged(Rect $originalRect, Rect $newRect) : SizeState
    {
        $sizeState = new SizeState();
        $sizeState->fits = $this->fits;
        $sizeState->continued = $this->continued;
        $sizeState->requiredSize = new Size(size:$this->requiredSize);
        return $sizeState;
    }

    /**
     * Calculates the size of the frame for the given rectangle
     * @param Renderer $r
     * @param Rect $forRect Rect in which this frame has to be printed
     * @return SizeState
     */
    protected abstract function doCalcSize(Renderer $r, Rect $forRect) : SizeState;

    /**
     * Prints the contents of the frame into the calculated rectangle
     * @param Renderer $r
     * @param Rect $inRect Rect into which the contents of this frame will be printed
     * @return void
     */
    protected abstract function doPrint(Renderer $r, Rect $inRect) : void;

    /**
     * Will be called when the printing begins
     * @param Renderer $r
     * @return void
     */
    protected abstract function doBeginPrint(Renderer $r) : void;

}