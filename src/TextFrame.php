<?php
/*
 * //============================================================+
 * // File name     : TextFrame.php
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


include_once "ReportFrame.php";


/**
 * @class TextFrame
 * Class for a frame containing some text. It is a simple frame with no sub-frames in it.
 * A block of text will be printed in a rectangle with a calculated width and height.
 * @brief Class representing some text in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class TextFrame extends ReportFrame
{
    /**
     * @var TextStyle
     */
    protected TextStyle $textStyle;

    /**
     * @var string
     */
    protected string $text;
    /**
     * @var string
     */
    protected string $textToPrint;

    /**
     * @var float
     */
    protected float $minimumWidth;
    /**
     * @var bool
     */
    protected bool $wrapText;

    /**
     * @var bool
     */
    protected bool $textColorSet;

    /**
     * @var Rect
     */
    protected Rect $textLayout;

    /**
     * @var string
     */
    protected string $textColor;

    /**
     * @var int
     */
    protected int $charsFitted;
    /**
     * @var int
     */
    protected int $charIndex;

    /**
     * Class constructor
     * @param string $text Text to print to the report
     * @param TextStyle $textStyle The text style to print the text
     */
    public function __construct(string $text, TextStyle $textStyle)
    {
        parent::__construct();

        $this->text = $text;
        $this->textStyle = $textStyle;
        $this->minimumWidth = 1.0;

        $this->textColorSet = false;
        $this->wrapText = true;
        $this->charsFitted = 0;
        $this->charIndex = 0;
        $this->textColor = DEF_TEXT_COLOR;
        $this->hAlignment = DEF_FRAME_H_ALIGNMENT;
        $this->vAlignment = DEF_FRAME_V_ALIGNMENT;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }


    /**
     * @return TextStyle
     */
    public function getTextStyle(): TextStyle
    {
        return $this->textStyle;
    }

    /**
     * @param TextStyle $textStyle
     */
    public function setTextStyle(TextStyle $textStyle): void
    {
        $this->textStyle = $textStyle;
    }

    /**
     * @return float
     */
    public function getMinimumWidth(): float
    {
        return $this->minimumWidth;
    }

    /**
     * @param float $minimumWidth
     */
    public function setMinimumWidth(float $minimumWidth): void
    {
        $this->minimumWidth = $minimumWidth;
    }

    /**
     * @return bool
     */
    public function getWrapText(): bool
    {
        return $this->wrapText;
    }

    /**
     * @param bool $wrapText
     */
    public function setWrapText(bool $wrapText): void
    {
        $this->wrapText = $wrapText;
    }

    /**
     * Returns the text color
     * @return string
     */
    public function getTextColor(): string
    {
        if ($this->textColorSet) {
            return $this->textColor;
        } else {
            return $this->textStyle->getTextColor();
        }
    }

    /**
     * Overwrites the text color in the text style
     * @param string $textColor
     */
    public function setTextColor(string $textColor): void
    {
        $this->textColor = $textColor;
        $this->textColorSet = true;
    }

    /**
     * Returns the part of the text, that has to be printed as next
     * @param Renderer $r
     * @return string
     */
    protected function getTextToPrint(Renderer $r): string
    {
        $text = substr($this->text, $this->charIndex);
        $text = $r->replacePageVars($text);
        if ($this->charIndex > 0) {
            $text = ltrim($text);
        }
        return $text;
    }

    /**
     * Returns the origin of the text depending on the alignments
     * @return int
     */
    protected function getOrigin(): int
    {
        $origin = 0;
        if (($this->hAlignment == 'C') || ($this->vAlignment == 'M')) {
            $origin = -1;
        } else {
            if ($this->hAlignment == 'R') {
                $origin |= 1;
            }
            if ($this->vAlignment == 'B') {
                $origin |= 2;
            }
        }
        return $origin;
    }

    /**
     * Returns the starting point for the text
     * @param Rect $rect
     * @param int $corner
     * @return array
     */
    protected function getPoint(Rect $rect, int $corner): array
    {
        if (($corner & 1) == 0) {
            $x = $rect->left;
        } else {
            $x = $rect->right;
        }
        if (($corner & 2) == 0) {
            $y = $rect->top;
        } else {
            $y = $rect->bottom;
        }

        $point = array();
        $point[0] = $x;
        $point[1] = $y;
        return $point;
    }

    /**
     * Adjusts the rectangle for the given situation in the report
     * @param Rect $originalRect
     * @param Rect $newRect
     * @return SizeState
     */
    protected function rectChanged(Rect $originalRect, Rect $newRect): SizeState
    {
        $resize = true;
        $corner = $this->getOrigin();
        if ($corner >= 0) {
            if ($this->getPoint($originalRect, $corner) == $this->getPoint($newRect, $corner)) {
                if ($newRect->sizeFits($this->requiredSize)) {
                    $resize = false;
                }
            }
        }

        if ($resize) {
            $this->resetSize($this->keepTogether);
        }
        return parent::rectChanged($originalRect, $newRect);
    }

    /**
     * Checks if the font height fits in the textLayout rectangle
     * @param Renderer $r
     * @return bool
     */
    protected function checkTextLayout(Renderer $r): bool
    {
        $fontHeight = $r->getFontHeight($this->textStyle, $this->hAlignment, $this->vAlignment);
        $fits = true;

        if (round($this->textLayout->getHeight(), 3) < round($fontHeight, 3) || round($this->textLayout->getWidth(), 3) < round($this->minimumWidth, 3)) {
            $fits = false;
        }
        return $fits;
    }

    /**
     * Calculates the size of the text. If necessary breaks it into parts
     * so that it fits into the rectangle and will be continued on the next page
     * @param Renderer $r
     * @param Rect $rect
     * @return SizeState
     */
    protected function setTextSize(Renderer $r, Rect $rect): SizeState
    {
        $sizeState = new SizeState();
        $sizeState->fits = true;
        $this->charsFitted = strlen($this->textToPrint);

        $bTruncated = false;

        if ($this->hAlignment == 'R' || !$this->wrapText) {
            $requiredSize = $r->calcTextSize($this->textStyle, $this->textToPrint, $this->hAlignment, $this->vAlignment);
        } else {
            if ($this->useFullWidth || $this->getMaxWidth() > 0.0) {
                $requiredSize = $r->calcTextSize($this->textStyle, $this->textToPrint, $this->hAlignment, $this->vAlignment, $rect->getWidth());
            } else {
                $requiredSize = $r->calcTextSize($this->textStyle, $this->textToPrint, $this->hAlignment, $this->vAlignment);
            }
        }

        if (!$rect->widthFits($requiredSize->width)) {

            $text = $this->textToPrint;

            if (!$this->wrapText && str_contains($this->textToPrint, "\n")) {
                $this->textToPrint = "";
                $lines = explode("\n", $text);
                foreach ($lines as $line) {
                    $size = $r->calcTextSize($this->textStyle, $line, $this->hAlignment, $this->vAlignment);

                    // Cut chars form line of text to reduce the width of text to fit into the rect
                    while ($size->width > $rect->getWidth()) {
                        $line = substr($line, 0, -1);
                        $size = $r->calcTextSize($this->textStyle, $line, $this->hAlignment, $this->vAlignment);
                    }

                    if (strlen($this->textToPrint) > 0) {
                        $this->textToPrint = $this->textToPrint . "\n";
                    }
                    $this->textToPrint = $this->textToPrint . $line;
                }

                $this->charsFitted = strlen($this->textToPrint);
                $requiredSize->width = $r->calcTextSize($this->textStyle, $this->textToPrint, $this->hAlignment, $this->vAlignment, $rect->getWidth())->width;
            } else {

                $text = $r->trimText($text, $this->textStyle, $this->wrapText, $rect->getWidth(), $this->hAlignment, $this->vAlignment, $rect->getHeight());
                $this->charsFitted = strlen($text);

                if ($this->wrapText) {
                    $requiredSize = $r->calcTextSize($this->textStyle, $text, $this->hAlignment, $this->vAlignment, $rect->getWidth());
                } else {
                    $requiredSize->width = $r->calcTextSize($this->textStyle, $text, $this->hAlignment, $this->vAlignment, $rect->getWidth())->width;
                    if ($this->charsFitted < strlen($this->textToPrint)) {
                        $bTruncated = true;
                    }
                }
            }
        }

        if (!$rect->heightFits($requiredSize->height)) {

            $text = $this->textToPrint;
            $text = $r->trimText($text, $this->textStyle, $this->wrapText, $rect->getWidth(), $this->hAlignment, $this->vAlignment, $rect->getHeight());
            $this->charsFitted = strlen($text);
            $requiredSize->height = $r->calcTextSize($this->textStyle, $text, $this->hAlignment, $this->vAlignment, $rect->getWidth())->height;

            if ($this->charsFitted < strlen($this->textToPrint) && !$this->wrapText) {
                $bTruncated = true;
            }
        }

        if ($requiredSize->height == 0.0) {
            $requiredSize->height = $r->getFontHeight($this->textStyle, $this->hAlignment, $this->vAlignment);
        }

        if ($this->charsFitted < strlen($this->textToPrint)) {
            if ($this->keepTogether) {
                $sizeState->fits = false;
                $this->charsFitted = 0;
                return $sizeState;
            }

            if (!$bTruncated) {
                $sizeState->continued = true;
            }
        }

        $this->textLayout = $rect->getRectWithSizeAndAlign($requiredSize, $this->hAlignment, $this->vAlignment);
        $sizeState->requiredSize = $this->textLayout->getSize();

        return $sizeState;
    }

    /**
     * Calculates the size of the text for the given rectangle
     * @param Renderer $r Class that can add the text to the report
     * @param Rect $forRect Rect in which the text has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeState = new SizeState();

        $this->textLayout = $forRect->getRectWithSizeAndAlign();
        if ($this->checkTextLayout($r)) {
            $this->textToPrint = $this->getTextToPrint($r);
            $sizeState = $this->setTextSize($r, $forRect);
        } else {
            $sizeState->fits = false;
            $sizeState->continued = true;
        }
        return $sizeState;
    }

    /**
     * Prints the text into the calculated rectangle
     * @param Renderer $r Class that can add the text to the report
     * @param Rect $inRect Rect into which the text will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        if ($this->textStyle->getBackgroundColor() != "#FFFFFF") {
            $backgroundRect = $this->textLayout;
            if ($this->useFullWidth) {
                $backgroundRect->left = $inRect->left;
                $backgroundRect->right = $inRect->getWidth();
            }
            if ($this->useFullHeight) {
                $backgroundRect->top = $inRect->top;
                $backgroundRect->bottom = $inRect->getHeight();
            }

            $r->addRect($backgroundRect, $this->textStyle->getBackgroundColor());
        }

        $r->addTextBlock(substr($this->textToPrint, 0, $this->charsFitted), $this->textStyle, $this->textLayout, $this->hAlignment, $this->vAlignment, $this->getTextColor());

        $this->charIndex += $this->charsFitted;
    }

    /**
     * Will be called when the printing begins
     * Resets the char index
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r): void
    {
        $this->charIndex = 0;
    }
}
