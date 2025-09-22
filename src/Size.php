<?php
/*
 * //============================================================+
 * // File name     : Size.php
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

/**
 * @class Size
 * Instances of this class represent a two-dim size of a rectangle
 * A size has a width and a height.
 * @brief Simple class representing the size of a rectangle
 * @author Michael Hodel - info@adiuvaris.ch
 */
class Size
{
    /**
     * Width
     * @var float
     */
    public float $width;

    /**
     * Height
     * @var float
     */
    public float $height;

    /**
     * Class constructor
     * @param float $width
     * @param float $height
     * @param Size|null $size
     */
    public function __construct(float $width = 0.0, float $height = 0.0, Size $size = null)
    {
        if (!is_null($size)) {
            $this->setSize($size->width, $size->height);
        } else {
            $this->setSize($width, $height);
        }
    }

    /**
     * @param float $width
     * @param float $height
     * @return void
     */
    public function setSize(float $width = 0.0, float $height = 0.0): void
    {
        $this->width = $width;
        $this->height = $height;
    }
}
