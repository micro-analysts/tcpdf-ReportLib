<?php
/*
 * //============================================================+
 * // File name     : Rect.php
 * // Version       : 1.0.0
 * // Last Update   : 23.12.22, 06:39
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

/**
 * @class Rect
 * Class representing a rectangle by its coordinates of the top left
 * and bottom right corners.
  * @brief PHP class representing a rectangle
 * @author Michael Hodel - info@adiuvaris.ch
 */
class Rect
{
    /**
     * Small constant in that range coordinates are compared as the same
     */
    const C_EPS = 0.001;

    /**
     * Top left x coordinate
     * @var float
     */
    public float $left;

    /**
     * Top left y coordinate
     * @var float
     */
    public float $top;

    /**
     * Bottom right x coordinate
     * @var float
     */
    public float $right;

    /**
     * Bottom right y coordinate
     * @var float
     */
    public float $bottom;

    /**
     * Class constructor
     * @param float $left
     * @param float $top
     * @param float $right
     * @param float $bottom
     * @param Rect|null $rect
     */
    public function __construct(float $left = 0.0, float $top = 0.0, float $right = 0.0, float $bottom = 0.0, Rect $rect = null)
    {
        if (!is_null($rect)) {
            $this->top = $rect->top;
            $this->left = $rect->left;
            $this->right = $rect->right;
            $this->bottom = $rect->bottom;
        } else {
            $this->top = $top;
            $this->left = $left;
            $this->right = $right;
            $this->bottom = $bottom;
        }
    }

    /**
     * Return the width of the rectangle
     * @return float
     */
    public function getWidth() : float
    {
        return $this->right - $this->left;
    }

    /**
     * Return the height of the rectangle
     * @return float
     */
    public function getHeight() : float
    {
        return $this->bottom - $this->top;
    }

    /**
     * Return the size of the rectangle
     * @return Size
     */
    public function getSize() : Size
    {
        return new Size($this->getWidth(), $this->getHeight());
    }

    /**
     * Checks if the size is 0 in both directions
     * @return bool
     */
    public function isEmpty() : bool
    {
        return (($this->getWidth() <= Rect::C_EPS) && ($this->getHeight() <= Rect::C_EPS));
    }

    /**
     * Returns a rectangle with added margins.
     * @param float $marginTop
     * @param float $marginRight
     * @param float $marginBottom
     * @param float $marginLeft
     * @return Rect
     */
    public function getRectWithMargins(float $marginTop, float $marginRight, float $marginBottom, float $marginLeft) : Rect
    {
        $r = new Rect(rect:$this);
        $r->left += $marginLeft;
        $r->top += $marginTop;
        $r->right -= $marginRight;
        $r->bottom -= $marginBottom;

        return $r;
    }

    /**
     * Returns a zero based rectangle for this rectangle regarding the given alignments.
     * @param Size|null $size
     * @param string $hAlignment
     * @param string $vAlignment
     * @return Rect
     */
    public function getRectWithSizeAndAlign(Size $size = null, string $hAlignment = 'L', string $vAlignment = 'T') : Rect
    {
        if (!is_null($size)) {
            $width = $size->width;
            $height = $size->height;
            $upperLeftX = 0.0;
            $upperLeftY = 0.0;
            switch ($hAlignment) {
                case 'J':
                case 'L':
                    $upperLeftX = $this->left;
                    break;
                case 'R':
                    $upperLeftX = $this->right - $width;
                    break;
                case 'C':
                    $upperLeftX = $this->left + ($this->getWidth() - $width) / 2;
                    break;
            }

            switch ($vAlignment) {
                case 'T':
                    $upperLeftY = $this->top;
                    break;
                case 'B':
                    $upperLeftY = $this->bottom - $height;
                    break;
                case 'M':
                    $upperLeftY = $this->top + ($this->getHeight() - $height) / 2;
                    break;
            }
            $rect = new Rect($upperLeftX, $upperLeftY, $upperLeftX + $width, $upperLeftY + $height);
        } else {
            $size = $this->getSize();
            $rect = new Rect($this->left, $this->top, $this->left + $size->width, $this->top + $size->height );
        }
        return $this->check($rect);
    }

    /**
     * Checks if the given rectangle fits in this rectangle
     * @param Rect $checkRect
     * @return Rect
     */
    public function check(Rect $checkRect) : Rect
    {
        $rect = new Rect(rect:$checkRect);

        if ($rect->right > $this->right) {
            $rect->right -= ($rect->right - $this->right);
        }

        if ($rect->bottom > $this->bottom) {
            $rect->bottom -= ($rect->bottom - $this->bottom);
        }
        return $rect;
    }

    /**
     * Returns true if the given size fits in the rectangle in both directions
     * @param Size $size
     * @return bool
     */
    public function sizeFits(Size $size) : bool
    {
        $h = $size->height - $this->getHeight();
        $w = $size->width - $this->getWidth();
        return !($h > Rect::C_EPS || $w > Rect::C_EPS);
    }

    /**
     * Returns true if the given size fits in the rectangle width
     * @param float $width
     * @return bool
     */
    public function widthFits(float $width) : bool
    {
        $w = $width - $this->getWidth();
        return !($w > Rect::C_EPS);
    }

    /**
     * Returns true if the given size fits in the rectangle height
     * @param float $height
     * @return bool
     */
    public function heightFits(float $height) : bool
    {
        $h = $height - $this->getHeight();
        return !($h > Rect::C_EPS);
    }

    /**
     * Checks if the given rectangle is equal to the rectangle
     * @param Rect $rect
     * @return bool
     */
    public function isEqualTo (Rect $rect) : bool
    {
        return ($this->coordsEqual($this->left, $rect->left) && $this->coordsEqual($this->top, $rect->top) &&
                $this->coordsEqual($this->right, $rect->right) && $this->coordsEqual($this->bottom, $rect->bottom));
    }

    /**
     * Checks the lower right corner against the given points
     * @param float $a
     * @param float $b
     * @return bool
     */
    protected function coordsEqual(float $a, float $b) : bool
    {
        if ($a > $b) {
            return (($a - $b) < Rect::C_EPS);
        } else {
            return (($b - $a) < Rect::C_EPS);
        }
    }

}

