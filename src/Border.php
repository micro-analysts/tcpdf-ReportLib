<?php
/*
 * //============================================================+
 * // File name     : Border.php
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

include_once "Size.php";
include_once "Rect.php";
include_once "Pen.php";
include_once "Renderer.php";


/**
 * @class Border
 * Class representing a border around a rectangle where the four lines
 * can be defined separately. Each side has its own pen to draw the line.
 * @brief Class to print a rectangle
 * @author Michael Hodel - info@adiuvaris.ch
 */
class Border
{
    const TOP = 1;
    const LEFT = 2;
    const RIGHT = 3;
    const BOTTOM = 4;

    /**
     * Pen for the top line
     * @var Pen
     */
    protected Pen $topPen;

    /**
     * Pen for the left line
     * @var Pen
     */
    protected Pen $leftPen;

    /**
     * Pen for the right line
     * @var Pen
     */
    protected Pen $rightPen;

    /**
     * Pen for the bottom line
     * @var Pen
     */
    protected Pen $bottomPen;

    /**
     * Class constructor
     * Initialize the four pens for the four lines around the border rectangle
     * A default pen is a solid black line with no extent, i.e. nothing will be drawn.
     */
    public function __construct()
    {
        $this->topPen = new Pen();
        $this->leftPen = new Pen();
        $this->rightPen = new Pen();
        $this->bottomPen = new Pen();
    }

    /**
     * Draws a border around a given rectangle
     * @param Renderer $r Renderer used to draw the lines
     * @param Rect $rect Rectangle dimensions
     * @return void
     * @see Renderer, Rect
     */
    public function drawBorder(Renderer $r, Rect $rect): void
    {
        $this->drawLine($r, $this->topPen, $rect, Border::TOP);
        $this->drawLine($r, $this->rightPen, $rect, Border::RIGHT);
        $this->drawLine($r, $this->bottomPen, $rect, Border::BOTTOM);
        $this->drawLine($r, $this->leftPen, $rect, Border::LEFT);
    }

    /**
     * Sets the same pen for all four lines of the border.
     * The pen will be cloned so the pen of each edge can be changed individually.
     * Getter and setter for the pens are available.
     * @param Pen $pen Pen to use to draw the border
     * @return void
     * @see Pen
     */
    public function setPen(Pen $pen): void
    {
        $this->topPen = clone $pen;
        $this->leftPen = clone $pen;
        $this->rightPen = clone $pen;
        $this->bottomPen = clone $pen;
    }

    /**
     * Adds the size of the borderlines to the given size
     * @param Size $toSize Size to which the border will be added
     * @return Size The resulting size after the border would have been painted
     */
    public function addBorderSize(Size $toSize): Size
    {
        $size = new Size(size: $toSize);
        $size->height += $this->topPen->getExtent();
        $size->width += $this->rightPen->getExtent();
        $size->height += $this->bottomPen->getExtent();
        $size->width += $this->leftPen->getExtent();
        return $size;
    }

    /**
     * Returns a rectangle inside the borderlines after the borderlines
     * have been painted into the given rect ($forRect)
     * @param Rect $forRect The rectangle in which the border will be painted
     * @return Rect The resulting rect after the border would have been painted
     */
    public function getInnerRect(Rect $forRect): Rect
    {
        $rect = new Rect(rect: $forRect);

        $rect->top += $this->topPen->getExtent();
        $rect->right -= $this->rightPen->getExtent();
        $rect->bottom -= $this->bottomPen->getExtent();
        $rect->left += $this->leftPen->getExtent();

        return $rect;
    }

    /**
     * @return Pen
     */
    public function getTopPen(): Pen
    {
        return $this->topPen;
    }

    /**
     * @param Pen $topPen
     */
    public function setTopPen(Pen $topPen): void
    {
        $this->topPen = $topPen;
    }

    /**
     * @return Pen
     */
    public function getLeftPen(): Pen
    {
        return $this->leftPen;
    }

    /**
     * @param Pen $leftPen
     */
    public function setLeftPen(Pen $leftPen): void
    {
        $this->leftPen = $leftPen;
    }

    /**
     * @return Pen
     */
    public function getRightPen(): Pen
    {
        return $this->rightPen;
    }

    /**
     * @param Pen $rightPen
     */
    public function setRightPen(Pen $rightPen): void
    {
        $this->rightPen = $rightPen;
    }

    /**
     * @return Pen
     */
    public function getBottomPen(): Pen
    {
        return $this->bottomPen;
    }

    /**
     * @param Pen $bottomPen
     */
    public function setBottomPen(Pen $bottomPen): void
    {
        $this->bottomPen = $bottomPen;
    }


    /**
     * @return float
     */
    public function getLeftWidth(): float
    {
        return $this->leftPen->getExtent();
    }

    /**
     * @return float
     */
    public function getTopWidth(): float
    {
        return $this->topPen->getExtent();
    }

    /**
     * @return float
     */
    public function getRightWidth(): float
    {
        return $this->rightPen->getExtent();
    }

    /**
     * @return float
     */
    public function getBottomWidth(): float
    {
        return $this->bottomPen->getExtent();
    }

    /**
     * Draws the line with the renderer.
     * @param Renderer $r Renderer used to paint
     * @param Pen $pen Pen to be used to draw the lines
     * @param Rect $rect Rectangle dimensions
     * @param int $edge The edge that will be painted
     * <ul>
     *   <li>TOP (1): top line.</li>
     *   <li>LEFT (2): left line.</li>
     *   <li>RIGHT (3): right line.</li>
     *   <li>BOTTOM (4): bottom line.</li>
     * </ul>
     * @return void
     */
    protected function drawLine(Renderer $r, Pen $pen, Rect $rect, int $edge): void
    {
        $width = $pen->getExtent();
        $color = $pen->getColor();
        $dash = $pen->getDash();
        $x1 = 0.0;
        $y1 = 0.0;
        $x2 = 0.0;
        $y2 = 0.0;
        if ($pen->getExtent() != 0.0) {
            switch ($edge) {
                case Border::TOP:
                    $x1 = $rect->left;
                    $x2 = $rect->right;
                    $y1 = $y2 = $rect->top + $width / 2.0;
                    break;
                case Border::RIGHT:
                    $x1 = $x2 = $rect->right - $width / 2.0;
                    $y1 = $rect->top;
                    $y2 = $rect->bottom;
                    break;
                case Border::BOTTOM:
                    $x1 = $rect->left;
                    $x2 = $rect->right;
                    $y1 = $y2 = $rect->bottom - $width / 2.0;
                    break;
                case Border::LEFT:
                    $x1 = $x2 = $rect->left + $width / 2.0;
                    $y1 = $rect->top;
                    $y2 = $rect->bottom;
                    break;
            }
            $r->addLine($x1, $y1, $x2, $y2, $width, $dash, $color);
        }
    }
}
