<?php
/*
 * //============================================================+
 * // File name     : LineFrame.php
 * // Version       : 1.0.0
 * // Last Update   : 16.12.22, 13:54
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

include_once "ReportFrame.php";

/**
 * @class LineFrame
 * Class for a frame the prints a line. It is a simple frame with no sub-frames in it.
 * A line will be printed with a given width or height.
 * @brief Class representing a line in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class LineFrame extends ReportFrame
{
    /**
     * Pen that will be used to print the lin
     * @var Pen
     */
    protected Pen $pen;

    /**
     * Length of the line
     * @var float
     */
    protected float $length;

    /**
     * Direction of the line (horizontal or vertical)
     * @var string
     */
    protected string $direction;

    /**
     * Calculated start position
     * @var float
     */
    protected float $x1;

    /**
     * Calculated end position
     * @var float
     */
    protected float $x2;

    /**
     * Calculated start position
     * @var float
     */
    protected float $y1;

    /**
     * Calculated end position
     * @var float
     */
    protected float $y2;

    /**
     * Class constructor
     * @param string $direction Direction of the line 'V' vertical or 'H' for horizontal lines
     * @param float $extent Extent of the pen - default 0.1mm
     * @param string $color Color of the line - default black
     * @param float $length Length of the line
     */
    public function __construct(string $direction, float $extent = DEF_LINE_EXTEND, string $color = DEF_LINE_COLOR, float $length = 0.0)
    {
        parent::__construct();

        $this->pen = new Pen($extent, $color);
        $this->direction = $direction;

        $this->length = $length;

        $this->x1 = 0.0;
        $this->x2 = 0.0;
        $this->y1 = 0.0;
        $this->y2 = 0.0;
    }

    /**
     * @return Pen
     */
    public function getPen(): Pen
    {
        return $this->pen;
    }

    /**
     * @param Pen $pen
     */
    public function setPen(Pen $pen): void
    {
        $this->pen = $pen;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @param float $length
     */
    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }

    /**
     * Calculates the size of the line for the given rectangle
     * @param Renderer $r Class that can add the line to the report
     * @param Rect $forRect Rect in which the line has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeStates = new SizeState();
        $sizeStates->fits = true;
        $sizeStates->continued = false;
        $sizeStates->requiredSize = $this->getSize();

        $this->setLinePoints($forRect);

        if ($this->direction == 'H') {
            if ($this->y1 != $this->y2) {
                $sizeStates->fits = false;
            }
        } else if ($this->direction == 'V') {
            if ($this->x1 != $this->x2) {
                $sizeStates->fits = false;
            }
        }
        return $sizeStates;
    }

    /**
     * Calculates the start and end point for the line in the given rectangle.
     * The calculation takes the extent of the pen in account and also the
     * horizontal and vertical alignment and of course the direction of the line.
     * @param Rect $forRect
     * @return void
     */
    protected function setLinePoints(Rect $forRect) : void
    {
        $penWidth = $this->pen->getExtent();
        $halfWidth = $penWidth / 2.0;
        if ($this->direction == 'H') {
            switch ($this->vAlignment) {
                case 'T':
                    $this->y1 = $forRect->top + $halfWidth;
                    break;
                case 'M':
                    $this->y1 = ($forRect->top + $forRect->bottom) / 2.0;
                    break;
                case 'B':
                    $this->y1 = $forRect->bottom - $halfWidth;
                    break;
            }
            $this->y2 = $this->y1;

            if ($this->length == 0) {
                $this->x1 = $forRect->left;
                $this->x2 = $forRect->right;
            } else {
                switch ($this->hAlignment) {
                    case 'L':
                    case 'J':
                        $this->x1 = $forRect->left;
                        $this->x2 = $this->x1 + $this->length;
                        break;
                    case 'C':
                        $this->x1 = $forRect->left + ($forRect->getWidth() - $this->length) / 2.0;
                        $this->x2 = $this->x1 + $this->length;
                        break;
                    case 'R':
                        $this->x2 = $forRect->right;
                        $this->x1 = $this->x2 - $this->length;
                        break;
                }
            }
        } else {

            switch ($this->hAlignment) {
                case 'L':
                case 'J':
                    $this->x1 = $forRect->left + $halfWidth;
                    break;
                case 'C':
                    $this->x1 = ($forRect->left + $forRect->right) / 2;
                    break;
                case 'R':
                    $this->x1 = $forRect->right - $halfWidth;
                    break;
            }
            $this->x2 = $this->x1;

            if ($this->length == 0) {
                $this->y1 = $forRect->top;
                $this->y2 = $forRect->bottom;
            } else {
                switch ($this->vAlignment) {
                    case 'T':
                        $this->y1 = $forRect->top;
                        $this->y2 = $this->y1 + $this->length;
                        break;
                    case 'M':
                        $this->y1 = $forRect->top + ($forRect->getHeight() - $this->length) / 2;
                        $this->y2 = $this->y1 + $this->length;
                        break;
                    case 'B':
                        $this->y2 = $forRect->bottom;
                        $this->y1 = $this->y2 - $this->length;
                        break;
                }
            }
        }

        $this->x1 = max($this->x1, $forRect->left);
        $this->x2 = min($this->x2, $forRect->right);
        $this->y1 = max($this->y1, $forRect->top);
        $this->y2 = min($this->y2, $forRect->bottom);
    }


    /**
     * Adjusts the rectangle for the given situation in the report
     * @param Rect $originalRect
     * @param Rect $newRect
     * @return SizeState
     */
    protected function rectChanged(Rect $originalRect, Rect $newRect) : SizeState
    {
        $this->setLinePoints($newRect);
        return parent::rectChanged($originalRect, $newRect);
    }

    /**
     * Prints the line with the calculated points
     * @param Renderer $r Class that can add the line to the report
     * @param Rect $inRect Rect into which the line will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        $width = $this->pen->getExtent();
        $color = $this->pen->getColor();
        $dash = $this->pen->getDash();
        $r->addLine($this->x1, $this->y1, $this->x2, $this->y2, $width, $dash , $color);
    }


    /**
     * Calculates the required size of the line.
     * That is the length in one direction and the extent of the pen
     * in the other.
     * @return Size
     */
    public function getSize() : Size
    {
        $height = $this->y2 - $this->y1;
        $width = $this->x2 - $this->x1;

        $penWidth = $this->pen->getExtent();

        switch ($this->direction) {
            case 'H':
                $height = $penWidth;
                break;

            case 'V':
                $width = $penWidth;
                break;
        }

        return new Size($width, $height);
    }

    /**
     * Will be called when the printing begins
     * For line frames there is nothing to do here.
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r) : void
    {
    }


}