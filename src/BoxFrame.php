<?php
/*
 * //============================================================+
 * // File name     : BoxFrame.php
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

include_once "ContainerFrame.php";


/**
 * @class BoxFrame
 * Class representing a box with a fix width or height in a report.
 * A box frame can have a border around the whole frame and a colored background.
 * It is a special container frame, because it can only contain one frame.
 * But that frame can be any kind of frame, i.e. it can ba another container frame,
 * so a box can be filled with any content.
 * @brief Class representing a box in a frame
 * @author Michael Hodel - info@adiuvaris.ch
 */
class BoxFrame extends ContainerFrame
{
    /**
     * Width of the box
     * @var float
     */
    protected float $width;

    /**
     * Height of the box
     * @var float
     */
    protected float $height;

    /**
     * Calculated width if the width is given in percent
     * @var float
     */
    protected float $widthToUse;

    /**
     * Calculated height if the height is given in percent
     * @var float
     */
    protected float $heightToUse;

    /**
     * Flag if the width was given in percent of the surrounding frame
     * @var bool
     */
    protected bool $widthInPercent;

    /**
     * Flag if the height was given in percent
     * @var bool
     */
    protected bool $heightInPercent;

    /**
     * The border
     * @var Border
     */
    protected Border $border;

    /**
     * Top padding
     * @var float
     */
    protected float $paddingTop;

    /**
     * Right padding
     * @var float
     */
    protected float $paddingRight;

    /**
     * Bottom padding
     * @var float
     */
    protected float $paddingBottom;

    /**
     * Left padding
     * @var float
     */
    protected float $paddingLeft;

    /**
     * Background color for the whole box
     * @var string
     */
    protected string $background;

    /**
     * Rect used for the border
     * @var Rect
     */
    protected Rect $borderRect;

    /**
     * Rect with removed passing sizes
     * @var Rect
     */
    protected Rect $paddingRect;

    /**
     * Rect with the calculated size of the content
     * @var Rect
     */
    protected Rect $contentRect;

    /**
     * Class constructor
     * The default values of the params will create an empty box with no border and white background
     * @param float|string $width Width of the box in mm or % - float->mm, string->"%"
     * @param float|string $height Height of the box in mm or % - float->mm, string->"%"
     * @param float $borderExtent Extent for the borderline
     * @param string $borderColor Color for the borderline
     * @param string $backgroundColor Background color for the box
     */
    public function __construct(mixed $width = 0.0, mixed $height = 0.0, float $borderExtent = DEF_BOX_BORDER_EXTENT, string $borderColor = DEF_BOX_BORDER_COLOR, string $backgroundColor = DEF_BOX_BACKGROUND_COLOR)
    {
        parent::__construct();

        $this->widthToUse = 0.0;
        $this->heightToUse = 0.0;
        $this->widthInPercent = false;
        $this->width = floatval($width);
        if (is_string($width)) {
            $this->widthInPercent = true;
            if ($this->width == 100.0) {
                $this->setUseFullWidth(true);
            }
        }
        $this->heightInPercent = false;
        $this->height = floatval($height);
        if (is_string($height)) {
            $this->heightInPercent = true;
        }

        $this->paddingLeft = DEF_BOX_PADDING_LEFT;
        $this->paddingTop = DEF_BOX_PADDING_TOP;
        $this->paddingRight = DEF_BOX_PADDING_RIGHT;
        $this->paddingBottom = DEF_BOX_PADDING_BOTTOM;

        $this->border = new Border();
        $this->setBorderPen(new Pen($borderExtent, $borderColor));

        $this->background = $backgroundColor;

        $this->borderRect = new Rect();
        $this->paddingRect = new Rect();
        $this->contentRect = new Rect();
    }

    /**
     * Add the frame with the content of the box to the container.
     * The new frame will replace an already added frame,
     * because a box can contain only one frame.
     * @param ReportFrame $frame
     * @return int Number of frames (in this case always 1)
     */
    public function addFrame(ReportFrame $frame): int
    {
        if ($this->getFrameCount() > 0) {
            $this->clearFrames();
        }
        $this->frames[] = $frame;
        return count($this->frames);
    }

    /**
     * Defines a pen for the border. All four sides
     * of the border will use the same pen.
     * @param Pen $val
     * @return self
     */
    public function setBorderPen(Pen $val): self
    {
        $this->border->setPen($val);
        return $this;
    }

    /**
     * Returns the border object, so it is possible
     * to set any line of the border individually
     * @return Border
     */
    public function getBorder(): Border
    {
        return $this->border;
    }

    /**
     * Sets the padding on all sides to the same value
     * @param float $val
     * @return self
     */
    public function setPadding(float $val): self
    {
        $this->paddingTop = $val;
        $this->paddingRight = $val;
        $this->paddingBottom = $val;
        $this->paddingLeft = $val;
        return $this;
    }

    /**
     * @param float|string $width Width of the box in mm or % - float->mm, string->"%"
     * @return self
     */
    public function setWidth(mixed $width): self
    {
        $this->width = floatval($width);
        if (is_string($width)) {
            $this->widthInPercent = true;
        }
        return $this;
    }

    /**
     * @param float|string $height Height of the box in mm or % - float->mm, string->"%"
     * @return self
     */
    public function setHeight(mixed $height): self
    {
        $this->heightInPercent = false;
        $this->height = floatval($height);
        if (is_string($height)) {
            $this->heightInPercent = true;
        }
        return $this;
    }

    /**
     * @return float
     */
    public function getPaddingTop(): float
    {
        return $this->paddingTop;
    }

    /**
     * @param float $paddingTop
     * @return self
     */
    public function setPaddingTop(float $paddingTop): self
    {
        $this->paddingTop = $paddingTop;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaddingRight(): float
    {
        return $this->paddingRight;
    }

    /**
     * @param float $paddingRight
     * @return self
     */
    public function setPaddingRight(float $paddingRight): self
    {
        $this->paddingRight = $paddingRight;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaddingBottom(): float
    {
        return $this->paddingBottom;
    }

    /**
     * @param float $paddingBottom
     * @return self
     */
    public function setPaddingBottom(float $paddingBottom): self
    {
        $this->paddingBottom = $paddingBottom;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaddingLeft(): float
    {
        return $this->paddingLeft;
    }

    /**
     * @param float $paddingLeft
     * @return self
     */
    public function setPaddingLeft(float $paddingLeft): self
    {
        $this->paddingLeft = $paddingLeft;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackground(): string
    {
        return $this->background;
    }

    /**
     * @param string $background
     * @return self
     */
    public function setBackground(string $background): self
    {
        $this->background = $background;
        return $this;
    }

    /**
     * Calculates the size of the box for the given rectangle
     * @param Renderer $r
     * @param Rect $forRect Rect in which the box has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeState = new SizeState();
        $rect = new Rect(rect: $forRect);

        $sizeState->fits = true;
        $sizeState->continued = false;

        $contentSize = new Size();
        if ($this->getCurrentFrame() != null) {
            $this->getCurrentFrame()->calcSize($r, $this->getMaxContentRect($rect));
            $contentSize = $this->getCurrentFrame()->getSize();

            $sizeState->fits = $this->getCurrentFrame()->fits;
            $sizeState->continued = $this->getCurrentFrame()->continued;
        }

        $this->borderRect = $this->getBorderRect($rect, $contentSize);
        $this->paddingRect = $this->border->getInnerRect($this->borderRect);
        $this->contentRect = $this->paddingRect->getRectWithMargins($this->paddingTop, $this->paddingRight, $this->paddingBottom, $this->paddingLeft);

        $sizeState->requiredSize = $this->borderRect->getSize();

        if ($this->borderRect->getHeight() > $forRect->getHeight()) {
            $sizeState->fits = false;
            $sizeState->continued = true;
        }
        return $sizeState;
    }


    /**
     * Returns the max. rectangle size for the contents of the box
     * @param Rect $fromRect
     * @return Rect
     */
    protected function getMaxContentRect(Rect $fromRect): Rect
    {
        $rect = new Rect(rect: $fromRect);

        $rect->left += $this->border->getLeftWidth() + $this->paddingLeft;
        $rect->top += $this->border->getTopWidth() + $this->paddingTop;
        if ($this->width > 0) {
            if ($this->widthInPercent) {
                if (is_null($this->parentFrame)) {
                    $frameWidth = $rect->getWidth();
                } else {
                    $frameWidth = $this->parentFrame->getSizingBounds()->getWidth();
                }

                $contentWidth = ($frameWidth * $this->width / 100.0)
                    - $this->marginLeft - $this->marginRight
                    - $this->border->getLeftWidth() - $this->border->getRightWidth()
                    - $this->paddingLeft - $this->paddingRight;
                $rect->right = $rect->left + $contentWidth;
            } else {
                $contentWidth = $this->width
                    - $this->marginLeft - $this->marginRight
                    - $this->border->getLeftWidth() - $this->border->getRightWidth()
                    - $this->paddingLeft - $this->paddingRight;

                $rect->right = $rect->left + $contentWidth;
            }
        } else {
            $rect->right -= $this->border->getRightWidth() + $this->paddingRight;
        }

        if ($this->height > 0) {
            if ($this->widthInPercent) {
                if (is_null($this->parentFrame)) {
                    $frameHeight = $rect->getHeight();
                } else {
                    $frameHeight = $this->parentFrame->getSizingBounds()->getHeight();
                }

                $contentHeight = ($frameHeight * $this->height / 100.0)
                    - $this->marginTop - $this->marginBottom
                    - $this->border->getTopWidth() - $this->border->getBottomWidth()
                    - $this->paddingTop - $this->paddingBottom;

                $rect->bottom = $rect->top + $contentHeight;
            } else {
                $contentHeight = $this->height
                    - $this->marginTop - $this->marginBottom
                    - $this->border->getTopWidth() - $this->border->getBottomWidth()
                    - $this->paddingTop - $this->paddingBottom;

                $rect->bottom = $rect->top + $contentHeight;
            }
        } else {
            $rect->bottom -= $this->border->getBottomWidth() + $this->paddingBottom;
        }
        return $rect;
    }

    /**
     * Adjusts the rectangle for the given situation in the report
     * @param Rect $originalRect
     * @param Rect $newRect
     * @return SizeState
     */
    protected function rectChanged(Rect $originalRect, Rect $newRect): SizeState
    {
        $contentSize = $this->getSize();

        $this->borderRect = $this->getBorderRect($newRect, $contentSize);
        $this->paddingRect = $this->border->getInnerRect($this->borderRect);
        $this->contentRect = $this->paddingRect->getRectWithMargins($this->paddingTop, $this->paddingRight, $this->paddingBottom, $this->paddingLeft);

        return parent::rectChanged($originalRect, $newRect);
    }

    /**
     * Returns true if the width has to be calculated by the contents of the box
     * @return bool
     */
    protected function sizeToContentsWidth(): bool
    {
        return ($this->width == 0.0);
    }

    /**
     * Returns true if the height has to be calculated by the contents of the box
     * @return bool
     */
    protected function sizeToContentsHeight(): bool
    {
        return ($this->height == 0.0);
    }

    /**
     * Returns the rectangle for the border
     * @param Rect $rect
     * @param Size $contentSize
     * @return Rect
     */
    protected function getBorderRect(Rect $rect, Size $contentSize): Rect
    {
        $borderSize = $rect->getSize();
        if ($this->sizeToContentsWidth()) {
            $borderSize->width = $contentSize->width + $this->paddingLeft + $this->paddingRight + $this->border->getLeftWidth() + $this->border->getRightWidth();
        } else {
            if ($this->widthToUse == 0.0) {
                $this->widthToUse = $this->width;
                if ($this->widthInPercent) {
                    if (is_null($this->parentFrame)) {
                        $frameWidth = $rect->getWidth();
                    } else {
                        $frameWidth = $this->parentFrame->getSizingBounds()->getWidth();
                    }

                    $this->widthToUse = $frameWidth * ($this->width / 100.0);
                }
            }
            $borderSize->width = $this->widthToUse - $this->marginLeft - $this->marginRight;
        }

        if ($this->sizeToContentsHeight()) {
            $borderSize->height = $contentSize->height + $this->paddingTop + $this->paddingBottom + $this->border->getTopWidth() + $this->border->getBottomWidth();
        } else {
            if ($this->heightToUse == 0.0) {
                $this->heightToUse = $this->height;
                if ($this->heightInPercent) {
                    if (is_null($this->parentFrame)) {
                        $frameHeight = $rect->getHeight();
                    } else {
                        $frameHeight = $this->parentFrame->getSizingBounds()->getHeight();
                    }
                    $this->heightToUse = $frameHeight * ($this->height / 100.0);
                }
            }
            $borderSize->height = $this->heightToUse - $this->marginTop - $this->marginBottom;
        }

        $this->borderRect = $rect->getRectWithSizeAndAlign($borderSize, $this->hAlignment, $this->vAlignment);

        return $this->borderRect;
    }

    /**
     * Prints the box and its content into the calculated rectangle
     * @param Renderer $r Class that can add the content to the report
     * @param Rect $inRect Rect into which the box will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        $this->border->drawBorder($r, $this->borderRect->getRectWithSizeAndAlign());

        if ($this->background != "#FFFFFF") {
            $r->addRect($this->paddingRect->getRectWithSizeAndAlign(), $this->background);
        }
        if ($this->getCurrentFrame() != null) {
            $this->getCurrentFrame()->print($r, $this->contentRect);
        }
    }
}
