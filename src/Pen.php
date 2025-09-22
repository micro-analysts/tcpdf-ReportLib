<?php
/*
 * //============================================================+
 * // File name     : Pen.php
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

/**
 * @class Pen
 * Class for the definition of a pen to draw lines into a report.
 * A pen is defined by an extent, a color and a style (solid, dash etc.)
 * @brief PHP class representing a pen to draw lines into a report.
 * @author Michael Hodel - info@adiuvaris.ch
 */
class Pen
{
    protected float $extent;
    protected string $dash;
    protected string $color;

    /**
     * Class constructor
     * Initialize a Pen with its color, its width and the form.
     * @param float $extent The thickness of the line
     * @param string $color The color in HTML-Format
     * @param string $lineStyle The form of the line
     * <ul>
     *   <li>'solid' : solid line</li>
     *   <li>'dash' : dashed line</li>
     *   <li>'dot' : dotted line</li>
     *   <li>'dashdot' : dashes and dots</li>
     * </ul>
     */
    public function __construct(float $extent = DEF_LINE_EXTEND, string $color = DEF_LINE_COLOR, string $lineStyle = 'solid')
    {
        $this->extent = $extent;
        $this->color = $color;

        $lineStyle = strtolower($lineStyle);
        $this->dash = '0';
        if ($lineStyle == 'dash') {
            $this->dash = '5,2';
        } else if ($lineStyle == 'dashdot') {
            $this->dash = '5,2,1,2';
        } else if ($lineStyle == 'dot') {
            $this->dash = '1,1';
        }
    }

    /**
     * @return float
     */
    public function getExtent(): float
    {
        return $this->extent;
    }

    /**
     * @param float $extent
     */
    public function setExtent(float $extent): void
    {
        $this->extent = $extent;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * Sets the style of the line with a string that defines
     * how the line will be drawn.
     *
     * A string with series of length values,
     * which are the lengths of the on and off dashes.
     *
     * For example:
     * <ul>
     *   <li>"2" represents 2 on, 2 off, 2 on, 2 off, ...</li>
     *   <li>"2,1" is 2 on, 1 off, 2 on, 1 off, ...</li>
     *   <li>"0" is a solid line</li>
     * </ul>
     * @param string $dash
     * @see TCPDF
     */
    public function setDash(string $dash): void
    {
        $this->dash = $dash;
    }

    /**
     * @return string
     */
    public function getDash(): string
    {
        return $this->dash;
    }
}
