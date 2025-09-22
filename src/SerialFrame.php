<?php
/*
 * //============================================================+
 * // File name     : SerialFrame.php
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
 * @class SerialFrame
 * This is a frame container for a series of frames that will be printed
 * one after the other. The direction can be horizontal or vertical.
 * @brief Frame container for a series of frames.
 * @author Michael Hodel - info@adiuvaris.ch
 */
class SerialFrame extends ContainerFrame
{
    /**
     * Direction 'H' or 'V'
     * @var string
     */
    protected string $direction;

    /**
     * Class constructor
     * @param string $direction
     */
    public function __construct(string $direction = 'V')
    {
        parent::__construct();
        $this->direction = $direction;
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
     * @return $this
     */
    public function setDirection(string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }


    /**
     * Adjusts the coordinates of the rectangle and the required size after a
     * frame in the container has been printed.
     * @param Size $size
     * @param Rect $rect
     * @param Size $requiredSize
     * @return void
     */
    protected function advancePointers(Size $size, Rect $rect, Size $requiredSize): void
    {
        switch ($this->direction) {
            case 'V':
                $rect->top += $size->height;
                $requiredSize->height += $size->height;
                $requiredSize->width = max($requiredSize->width, $size->width);
                break;

            case 'H':
                $rect->left += $size->width;
                $requiredSize->width += $size->width;
                $requiredSize->height = max($requiredSize->height, $size->height);
                break;
        }
    }


    /**
     * Calculate the size and prints the content of all frames in the container
     * @param Renderer $r
     * @param Rect $inRect
     * @param bool $sizeOnly
     * @param bool $advanceSectionIndex
     * @return SizeState
     */
    protected function sizePrintFrames(Renderer $r, Rect $inRect, bool $sizeOnly, bool $advanceSectionIndex): SizeState
    {
        $rect = new Rect(rect: $inRect);
        $sizeStates = new SizeState();
        $sizeStates->fits = false;

        $savedFrameIndex = $this->currentFrameIndex;

        $saveIdx = -1;
        while ($this->currentFrameIndex < $this->getFrameCount()) {
            $delta = 0.0;

            if ($this->direction == 'H' && $this->getCurrentFrame()->hAlignment == 'R') {

                $currIdx = $this->currentFrameIndex;
                $this->currentFrameIndex++;
                while ($this->currentFrameIndex < $this->getFrameCount()) {
                    $this->getCurrentFrame()->calcSize($r, $rect);
                    $delta += $this->getCurrentFrame()->getSize()->width;
                    $this->currentFrameIndex++;
                }

                $this->currentFrameIndex = $currIdx;
                $rect->right -= $delta;
            }

            $this->getCurrentFrame()->calcSize($r, $rect);
            if ($this->getCurrentFrame()->fits) {
                $sizeStates->fits = true;

                if (!$sizeOnly) {
                    $this->getCurrentFrame()->print($r, $rect);
                }

                $this->advancePointers($this->getCurrentFrame()->getSize(), $rect, $sizeStates->requiredSize);
                if ($this->getCurrentFrame()->continued && ($this->direction != 'H' || $this->currentFrameIndex >= $this->getFrameCount() - 1)) {

                    // If an earlier frame has to be continued, then use that to start with on the next page
                    if ($saveIdx >= 0) {
                        $this->currentFrameIndex = $saveIdx;
                    }
                    break;
                } else {

                    // Save the number of the first horizontal frame that has to be continued on the next page
                    if ($this->getCurrentFrame()->continued && $saveIdx < 0) {
                        $saveIdx = $this->currentFrameIndex;
                    }
                    $this->currentFrameIndex++;
                    $rect->right += $delta;
                }
            } else {
                $this->getCurrentFrame()->resetSize($this->getCurrentFrame()->keepTogether);
                break;
            }
        }

        if ($saveIdx >= 0) {
            $sizeStates->continued = $saveIdx < $this->getFrameCount();
            $this->currentFrameIndex = $saveIdx;
        } else {
            $sizeStates->continued = $this->currentFrameIndex < $this->getFrameCount();
        }

        if ($this->currentFrameIndex == $savedFrameIndex && !$sizeStates->continued) {
            $sizeStates->fits = true;
        }

        if (!$advanceSectionIndex) {
            $this->currentFrameIndex = $savedFrameIndex;
        }

        return $sizeStates;
    }

    /**
     * Calculates the size of the frame for the given rectangle
     * @param Renderer $r
     * @param Rect $forRect Rect in which this frame has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        return $this->sizePrintFrames($r, $forRect, true, false);
    }

    /**
     * Prints the contents of the frame into the calculated rectangle
     * @param Renderer $r
     * @param Rect $inRect Rect into which the contents of this frame will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        $rect = new Rect(rect: $inRect);
        if (!$this->useFullWidth) {
            $rect->right = $rect->left + $this->requiredSize->width;
        }
        if (!$this->useFullHeight) {
            $rect->bottom = $rect->top + $this->requiredSize->height;
        }

        $this->sizePrintFrames($r, $rect, false, true);
    }

    /**
     * Will be called when the printing begins
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r): void
    {
        parent::doBeginPrint($r);
        $this->currentFrameIndex = 0;
    }
}
