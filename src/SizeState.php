<?php
/*
 * //============================================================+
 * // File name     : SizeState.php
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

include_once "Size.php";

/**
 * @class SizeState
 * This class will be used to save the current state after a frame size
 * has been calculated.
 * It contains a required size for the frame and flags if the frame
 * fits into the given space or if it has to be continued on the next page.
 * @brief Class representing the state after calculating a frame
 * @author Michael Hodel - info@adiuvaris.ch
 */
class SizeState
{
    /**
     * Required size of a frame
     * @var Size
     */
    public Size $requiredSize;

    /**
     * Flag if the frame fits into the given space
     * @var bool
     */
    public bool $fits;

    /**
     * Flag if the frame has to be continued on the next page
     * @var bool
     */
    public bool $continued;

    /**
     * Class constructor
     * Init the members as if it would fit
     */
    public function __construct()
    {
        $this->requiredSize = new Size();
        $this->fits = true;
        $this->continued = false;
    }

}