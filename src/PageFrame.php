<?php
/*
 * //============================================================+
 * // File name     : PageFrame.php
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
 * @class PageFrame
 * Container frame for content that should be printed on several pages (e.g. header/footer)
 * The frame can be printed on all pages, on odd or on even pages, or an all but the first page
 * @brief Container frame for header- and footer parts
 * @author Michael Hodel - info@adiuvaris.ch
 */
class PageFrame extends ContainerFrame
{
    /**
     * Prints the frame content on all pages
     */
    const C_OnAllPages = 0;

    /**
     * Prints the frame content on odd pages
     */
    const C_OnOddPages = -1;

    /**
     * Prints the frame content on even pages
     */
    const C_OnEvenPages = -2;

    /**
     * Prints the frame content on all pages but not on the first
     */
    const C_OnAllButFirstPage = -3;

    /**
     * On which pages the frame will be printed
     * @var int
     */
    protected int $onPageNr;

    /**
     * Class constructor
     * @param int $onPageNr Definition on which page this frame will be printed
     * @param bool $useFullWidth Flag if the frame may use the full width
     */
    public function __construct(int $onPageNr = self::C_OnAllPages, bool $useFullWidth = true)
    {
        parent::__construct();

        $this->useFullWidth = $useFullWidth;
        $this->onPageNr = $onPageNr;
    }

    /**
     * @return int
     */
    public function getOnPageNr(): int
    {
        return $this->onPageNr;
    }

    /**
     * @param int $onPageNr
     */
    public function setOnPageNr(int $onPageNr): void
    {
        $this->onPageNr = $onPageNr;
    }

    /**
     * Calculates the size of the frame for the given rectangle
     * @param Renderer $r Class that can add the content to the report
     * @param Rect $forRect Rect in which the frame has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeState = new SizeState();
        $rect = new Rect(rect: $forRect);

        if ($this->getFrameCount() == 0) {
            $sizeState->fits = true;
        } else {

            if (!$this->printOnPage($r)) {
                return $sizeState;
            }

            foreach ($this->frames as $frame) {

                /** @var ReportFrame $frame */
                $frame->calcSize($r, $rect);

                $sizeState->requiredSize->height = max($sizeState->requiredSize->height, $frame->getSize()->height);
                $sizeState->requiredSize->width = max($sizeState->requiredSize->width, $frame->getSize()->width);
                if ($frame->continued) {
                    $sizeState->continued = true;
                }
                if ($frame->fits) {
                    $sizeState->fits = true;
                }
            }
        }
        return $sizeState;
    }

    /**
     * Prints the content of the frame into the calculated rectangle
     * @param Renderer $r Class that can add the content to the report
     * @param Rect $inRect Rect into which the frame will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        $rect = new Rect(rect: $inRect);

        if (!$this->printOnPage($r)) {
            return;
        }

        if (!$this->useFullWidth) {
            $rect->right = $rect->left + $this->requiredSize->width;
        }

        foreach ($this->frames as $frame) {

            /** @var ReportFrame $frame */
            $frame->print($r, $rect);
            if ($frame->continued) {
                $this->continued = true;
            }
        }
    }

    /**
     * Checks if the frame has to be printed on the current page
     * @param Renderer $r
     * @return bool
     */
    public function printOnPage(Renderer $r): bool
    {
        $page = $r->getCurrentPage();

        if ($this->onPageNr > 0) {
            if ($page != $this->onPageNr) {
                return false;
            }
        } else if ($this->onPageNr == self::C_OnOddPages) {
            if (($page % 2) == 0) {
                return false;
            }
        } else if ($this->onPageNr == self::C_OnEvenPages) {
            if (($page % 2) == 1) {
                return false;
            }
        } else if ($this->onPageNr == self::C_OnAllButFirstPage) {
            if ($page == 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Will be called when the printing begins
     * For page frames there is nothing to do here.
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r): void {}
}
