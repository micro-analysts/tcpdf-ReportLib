<?php
/*
 * //============================================================+
 * // File name     : BreakFrame.php
 * // Version       : 1.0.0
 * // Last Update   : 18.12.22, 09:13
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

include_once "Rect.php";
include_once "SizeState.php";
include_once "Renderer.php";


/**
 * @class BreakFrame
 * Class for a forced page break in a report. It is a simple frame with no sub-frames in it.
 * After this frame a new page will be started in the report.
 * It is possible to change the page-format, orientation and margins from the next page on.
 * @brief Class representing a page break in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class BreakFrame extends ReportFrame
{
    /**
     * The page on which the break has been called far
     * @var int
     */
    protected int $pageNumber;

    /**
     * Flag if the next page number has been evaluated
     * @var bool
     */
    protected bool $firstTimeCalled;

    /**
     * Page info from the next page on
     * @var PageFormat
     */
    protected PageFormat $pageFormat;

    /**
     * Class constructor
     * @param PageFormat $pageFormat Page format information for the next section
     */
    public function __construct(PageFormat $pageFormat = new PageFormat())
    {
        parent::__construct();
        $this->pageFormat = $pageFormat;
    }

    /**
     * @return PageFormat
     */
    public function getPageFormat(): PageFormat
    {
        return $this->pageFormat;
    }

    /**
     * @param PageFormat $pageFormat
     */
    public function setPageFormat(PageFormat $pageFormat): void
    {
        $this->pageFormat = $pageFormat;
    }

    /**
     * Calculates the size of the break frame for the given rectangle
     * This frame uses the whole space on the page on that it was inserted
     * and no space on the next page. But it has to set the format
     * that is valid from the next page on.
     * @param Renderer $r
     * @param Rect $forRect
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeState = new SizeState();
        $sizeState->fits = true;
        $page = $r->getCurrentPage();

        if ($this->firstTimeCalled) {
            $this->firstTimeCalled = false;

            // Set the page format information for the next section
            // and save the page number where the break was initiated
            $this->pageNumber = $page;
            $r->setPageFormat($page + 1, $this->pageFormat);

            $sizeState->continued = true;
            $sizeState->requiredSize = $forRect->getSize();

        } else {
            if ($page == $this->pageNumber) {

                // On the page where the break was initiated the whole space is filled (empty space)
                // to create a new page
                $sizeState->continued = true;
                $sizeState->requiredSize = $forRect->getSize();
            } else {

                // On the next page no space is needed
                $sizeState->continued = false;
                $sizeState->requiredSize->setSize(0, 0);
            }
        }
        return $sizeState;
    }

    /**
     * This frame type has not to print anything
     * @param Renderer $r
     * @param Rect $inRect
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
    }

    /**
     * Will be called when the printing begins
     * Resets the firstTime flag
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r) : void
    {
        $this->firstTimeCalled = true;
    }

}