<?php
/*
 * //============================================================+
 * // File name     : FixposFrame.php
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
 * @class FixposFrame
 * This class represents a frame that has a fix position on a page
 * If the position and size of this frame is free on the current page
 * it will be printed on the current page. If there is already some
 * output on that spot, the frame will be printed on the next page.
 * That can be overwritten by defining the overlay parameter to true.
 * @brief Frame with a fix position on a page
 * @author Michael Hodel - info@adiuvaris.ch
 */
class FixposFrame extends ContainerFrame
{
    /**
     * Absolute position from the left of the page
     * @var float
     */
    protected float $offsetLeft;

    /**
     * Absolute position from the tap of the page
     * @var float
     */
    protected float $offsetTop;

    /**
     * Flag if the frame may overlay other frames
     * @var bool
     */
    protected bool $overlay;

    /**
     * Class constructor
     * @param float $offsetLeft Left position
     * @param float $offsetTop Top position
     * @param bool $overlay Flag if the frame may overlay other frames
     */
    public function __construct(float $offsetLeft = 0.0, float $offsetTop = 0.0, bool $overlay = false)
    {
        parent::__construct();

        $this->offsetLeft = $offsetLeft;
        $this->offsetTop = $offsetTop;
        $this->overlay = $overlay;
    }

    /**
     * @return float
     */
    public function getOffsetTop(): float
    {
        return $this->offsetTop;
    }

    /**
     * @param float $offsetTop
     */
    public function setOffsetTop(float $offsetTop): void
    {
        $this->offsetTop = $offsetTop;
    }

    /**
     * @return float
     */
    public function getOffsetLeft(): float
    {
        return $this->offsetLeft;
    }

    /**
     * @param float $offsetLeft
     */
    public function setOffsetLeft(float $offsetLeft): void
    {
        $this->offsetLeft = $offsetLeft;
    }

    /**
     * @return bool
     */
    public function isOverlay(): bool
    {
        return $this->overlay;
    }

    /**
     * @param bool $overlay
     */
    public function setOverlay(bool $overlay): void
    {
        $this->overlay = $overlay;
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

        $oldRect = new Rect(rect: $forRect);
        $rect = new Rect(rect: $forRect);

        if ($this->getFrameCount() == 0) {
            $sizeState->fits = true;
        } else {

            if (!$this->overlay) {

                // The offsets have to be inside the printable area for non overlay FixposFrames
                if ($this->offsetTop < $r->getPageBounds()->left || $this->offsetLeft < $r->getPageBounds()->top) {
                    throw new \Exception("Non overlay FixposFrame is outside the printable area");
                }
                if ($this->offsetTop < $forRect->top || $this->offsetLeft < $forRect->left) {
                    $sizeState->fits = false;
                    return $sizeState;
                }
            }

            $rect->left = $this->offsetLeft;
            $rect->top = $this->offsetTop;
            if ($this->overlay) {
                $rect->right = $r->getPaperSize()->width;
                $rect->bottom = $r->getPaperSize()->height;
            }

            foreach ($this->frames as $frame) {

                /** @var ReportFrame $frame */
                $frame->calcSize($r, $rect);

                $sizeState->requiredSize->height = max($sizeState->requiredSize->height, $frame->getSize()->height);
                $sizeState->requiredSize->width = max($sizeState->requiredSize->width, $frame->getSize()->width);
                if ($frame->continued) {
                    if (!$this->overlay) {
                        $sizeState->continued = true;
                    } else {
                        $sizeState->continued = false;
                    }
                }
                if ($frame->fits) {
                    $sizeState->fits = true;
                }
            }
        }

        if ($this->offsetTop + $sizeState->requiredSize->height - $oldRect->top > $sizeState->requiredSize->height) {
            $sizeState->requiredSize->height = $this->offsetTop + $sizeState->requiredSize->height - $oldRect->top;
        }

        if ($this->offsetLeft + $sizeState->requiredSize->width - $oldRect->left > $sizeState->requiredSize->width) {
            $sizeState->requiredSize->width = $this->offsetLeft + $sizeState->requiredSize->width - $oldRect->left;
        }

        return $sizeState;
    }

    /**
     * Prints the content into the calculated rectangle
     * @param Renderer $r Class that can add the content to the report
     * @param Rect $inRect Rect into which the frame will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        $rect = new Rect(rect: $inRect);

        $rect->left = $this->offsetLeft;
        $rect->top = $this->offsetTop;

        foreach ($this->frames as $frame) {

            /** @var ReportFrame $frame */
            $frame->calcSize($r, $rect);

            $frame->print($r, $rect);
            if ($frame->continued) {
                if (!$this->overlay) {
                    $this->continued = true;
                } else {
                    $this->continued = false;
                }
            }
        }
    }
}
