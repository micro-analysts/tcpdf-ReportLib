<?php
/*
 * //============================================================+
 * // File name     : TableColumn.php
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

include_once "Pen.php";
include_once "TextStyle.php";
include_once "TableRow.php";
include_once "Renderer.php";


/**
 * @class TableColumn
 * This class describes a single column in a TableFrame
 * @brief Column in a TableFrame
 * @see TableFrame
 * @author Michael Hodel - info@adiuvaris.ch
 */
class TableColumn
{
    /**
     * Column name
     * @var string
     */
    protected string $columnName;

    /**
     * Text in the header row - column title
     * @var string
     */
    protected string $title;

    /**
     * Real calculated width of the column
     * @var float
     */
    protected float $widthToUse;

    /**
     * Width of the column in mm or % of the table width
     * @var float
     */
    protected float $width;

    /**
     * Flag if the width has been defined in percent of the surrounding frame
     * @var bool
     */
    protected bool $widthInPercent;

    /**
     * Flag if the width should be calculated by the content of the data in the column
     * @var bool
     */
    protected bool $sizeWidthToContents;

    /**
     * Flag if the width should be defined as the content of the header data
     * @var bool
     */
    protected bool $sizeWidthToHeader;

    /**
     * Flag if after the column a line feed should be added
     * @var bool
     */
    protected bool $lineBreak;

    /**
     * Horizontal alignment of the column
     * @var string
     */
    protected string $hAlignment;

    /**
     * Vertical alignment of the column
     * @var string
     */
    protected string $vAlignment;

    /**
     * Left padding
     * @var float
     */
    protected float $paddingLeft;

    /**
     * Right padding
     * @var float
     */
    protected float $paddingRight;

    /**
     * Top padding
     * @var float
     */
    protected float $paddingTop;

    /**
     * Bottom padding
     * @var float
     */
    protected float $paddingBottom;

    /**
     * Pen for the vertical lines between columns
     * @var Pen
     */
    protected Pen $rightPen;

    /**
     * TextStyle for the header
     * @var TextStyle
     */
    protected TextStyle $headerTextStyle;

    /**
     * Textstyle for a detail row
     * @var TextStyle
     */
    protected TextStyle $detailRowTextStyle;

    /**
     * TextStyle for alternate rows (even/odd)
     * @var TextStyle
     */
    protected TextStyle $alternatingRowTextStyle;

    /**
     * TextStyle for subtotals
     * @var TextStyle
     */
    protected TextStyle $subTotalRowTextStyle;

    /**
     * TextStyle for totals
     * @var TextStyle
     */
    protected TextStyle $totalRowTextStyle;

    /**
     * Class constructor
     * @param string $columnName
     * @param string $title
     * @param float|string $width Width in mm or % - float->mm, string->"%"
     */
    public function __construct(string $columnName, string $title, mixed $width)
    {
        $this->columnName = $columnName;
        $this->widthInPercent = false;
        $this->width = floatval($width);
        if (is_string($width)) {
            $this->widthInPercent = true;
        }
        $this->title = $title;
        $this->widthToUse = 0.0;
        $this->lineBreak = false;

        $this->rightPen = new Pen(DEF_COLUMN_LINE_EXTEND);

        $this->sizeWidthToContents = false;
        $this->sizeWidthToHeader = false;

        $this->hAlignment = DEF_FRAME_H_ALIGNMENT;
        $this->vAlignment = DEF_FRAME_V_ALIGNMENT;

        $this->paddingLeft = DEF_COLUMN_PADDING_LEFT;
        $this->paddingRight = DEF_COLUMN_PADDING_RIGHT;
        $this->paddingTop = DEF_COLUMN_PADDING_TOP;
        $this->paddingBottom = DEF_COLUMN_PADDING_BOTTOM;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @param string $columnName
     */
    public function setColumnName(string $columnName): void
    {
        $this->columnName = $columnName;
    }

    /**
     * @return float
     */
    public function getWidthToUse(): float
    {
        return $this->widthToUse;
    }

    /**
     * @param float $widthToUse
     */
    public function setWidthToUse(float $widthToUse): void
    {
        $this->widthToUse = $widthToUse;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @return bool
     */
    public function isSizeWidthToContents(): bool
    {
        return $this->sizeWidthToContents;
    }

    /**
     * @param bool $sizeWidthToContents
     */
    public function setSizeWidthToContents(bool $sizeWidthToContents): void
    {
        $this->sizeWidthToContents = $sizeWidthToContents;
    }

    /**
     * @return bool
     */
    public function isSizeWidthToHeader(): bool
    {
        return $this->sizeWidthToHeader;
    }

    /**
     * @param bool $sizeWidthToHeader
     */
    public function setSizeWidthToHeader(bool $sizeWidthToHeader): void
    {
        $this->sizeWidthToHeader = $sizeWidthToHeader;
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
     * @return TextStyle
     */
    public function getHeaderTextStyle(): TextStyle
    {
        return $this->headerTextStyle;
    }

    /**
     * @param TextStyle $headerTextStyle
     */
    public function setHeaderTextStyle(TextStyle $headerTextStyle): void
    {
        $this->headerTextStyle = $headerTextStyle;
    }

    /**
     * @return TextStyle
     */
    public function getDetailRowTextStyle(): TextStyle
    {
        return $this->detailRowTextStyle;
    }

    /**
     * @param TextStyle $detailRowTextStyle
     */
    public function setDetailRowTextStyle(TextStyle $detailRowTextStyle): void
    {
        $this->detailRowTextStyle = $detailRowTextStyle;
    }

    /**
     * @return TextStyle
     */
    public function getAlternatingRowTextStyle(): TextStyle
    {
        return $this->alternatingRowTextStyle;
    }

    /**
     * @param TextStyle $alternatingRowTextStyle
     */
    public function setAlternatingRowTextStyle(TextStyle $alternatingRowTextStyle): void
    {
        $this->alternatingRowTextStyle = $alternatingRowTextStyle;
    }

    /**
     * @return TextStyle
     */
    public function getSubTotalRowTextStyle(): TextStyle
    {
        return $this->subTotalRowTextStyle;
    }

    /**
     * @param TextStyle $subTotalRowTextStyle
     */
    public function setSubTotalRowTextStyle(TextStyle $subTotalRowTextStyle): void
    {
        $this->subTotalRowTextStyle = $subTotalRowTextStyle;
    }

    /**
     * @return TextStyle
     */
    public function getTotalRowTextStyle(): TextStyle
    {
        return $this->totalRowTextStyle;
    }

    /**
     * @param TextStyle $totalRowTextStyle
     */
    public function setTotalRowTextStyle(TextStyle $totalRowTextStyle): void
    {
        $this->totalRowTextStyle = $totalRowTextStyle;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getHAlignment(): string
    {
        return $this->hAlignment;
    }

    /**
     * @param string $hAlignment
     */
    public function setHAlignment(string $hAlignment): void
    {
        $this->hAlignment = $hAlignment;
    }

    /**
     * @return string
     */
    public function getVAlignment(): string
    {
        return $this->vAlignment;
    }

    /**
     * @param string $vAlignment
     */
    public function setVAlignment(string $vAlignment): void
    {
        $this->vAlignment = $vAlignment;
    }

    /**
     * @return float
     */
    public function getPaddingLeft(): float
    {
        return $this->paddingLeft;
    }

    /**
     * @param float $paddingLeft
     */
    public function setPaddingLeft(float $paddingLeft): void
    {
        $this->paddingLeft = $paddingLeft;
    }

    /**
     * @return float
     */
    public function getPaddingRight(): float
    {
        return $this->paddingRight;
    }

    /**
     * @param float $paddingRight
     */
    public function setPaddingRight(float $paddingRight): void
    {
        $this->paddingRight = $paddingRight;
    }

    /**
     * @return float
     */
    public function getPaddingTop(): float
    {
        return $this->paddingTop;
    }

    /**
     * @param float $paddingTop
     */
    public function setPaddingTop(float $paddingTop): void
    {
        $this->paddingTop = $paddingTop;
    }

    /**
     * @return float
     */
    public function getPaddingBottom(): float
    {
        return $this->paddingBottom;
    }

    /**
     * @param float $paddingBottom
     */
    public function setPaddingBottom(float $paddingBottom): void
    {
        $this->paddingBottom = $paddingBottom;
    }

    /**
     * @return bool
     */
    public function isLineBreak(): bool
    {
        return $this->lineBreak;
    }

    /**
     * @param bool $lineBreak
     */
    public function setLineBreak(bool $lineBreak): void
    {
        $this->lineBreak = $lineBreak;
    }

    /**
     * Calculate the correct width of a column if it was defined in percent
     * @param float $width
     * @return void
     */
    public function calcWidth(float $width): void
    {
        if ($this->widthInPercent) {
            $this->widthToUse = $width * $this->width / 100.0;
        } else {
            $this->widthToUse = $this->width;
        }
    }


    /**
     * Calculates the width of the column
     * @param Renderer $r
     * @param array $tableData
     * @param float $maxHeaderRowHeight
     * @param float $maxDetailRowHeight
     * @return void
     */
    public function sizeColumn(Renderer $r, array $tableData, float $maxHeaderRowHeight, float $maxDetailRowHeight): void
    {
        $headerWidth = 0.0;
        if ($this->sizeWidthToHeader) {

            $text = $this->title;
            $textStyle = $this->getTextStyle(null, true, false);

            $headerSize = $this->sizePaintCell($r, $text, $textStyle, 0.0, 0.0, $this->width, $maxHeaderRowHeight, true);
            $headerWidth = $headerSize->width;
        }

        $contentWidth = 0.0;
        if ($this->sizeWidthToContents) {
            $alternatingRow = false;
            foreach ($tableData as $row) {
                $text = $row->getText($this->columnName);
                $textStyle = $this->getTextStyle($row, false, $alternatingRow);

                $cellSize = $this->sizePaintCell($r, $text, $textStyle, 0.0, 0.0, $this->width, $maxDetailRowHeight, true);

                $contentWidth = max($contentWidth, $cellSize->width);
                $alternatingRow = !$alternatingRow;
            }
        }

        $maxUsedWidth = max($headerWidth, $contentWidth);
        if ($maxUsedWidth > 0 && $maxUsedWidth < $this->width) {
            $this->widthToUse = $maxUsedWidth;
        } else {
            $this->widthToUse = $this->width;
        }
    }

    /**
     * Calculates the size of the column
     * @param Renderer $r
     * @param string $text
     * @param TextStyle $textStyle
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $maxHeight
     * @param bool $sizeOnly
     * @return Size
     */
    public function sizePaintCell(Renderer $r, string $text, TextStyle $textStyle, float $x, float $y, float $width, float $maxHeight, bool $sizeOnly): Size
    {
        $rect = new Rect($x, $y, $x + $width, $y + $maxHeight);
        $innerBounds = $rect->getRectWithMargins($this->paddingTop, $this->paddingRight + $this->rightPen->getExtent(), $this->paddingBottom, $this->paddingLeft);

        if ($sizeOnly) {
            $stringSize = $r->calcTextSize($textStyle, $text, $this->hAlignment, $this->vAlignment, $innerBounds->getWidth());

            $sideMargins = $this->paddingLeft + $this->paddingRight + $this->rightPen->getExtent();
            $topBottomMargins = $this->paddingTop + $this->paddingBottom;

            $stringSize->width += $sideMargins;
            $stringSize->height += $topBottomMargins;

            $stringSize->height = min($stringSize->height, $maxHeight);
        } else {
            if ($textStyle->getBackgroundColor() != "#FFFFFF") {
                $backgroundRect = $rect->getRectWithSizeAndAlign();
                $r->addRect($backgroundRect, $textStyle->getBackgroundColor());
            }

            $stringSize = new Size($innerBounds->getWidth(), $innerBounds->getHeight());

            $textLayout = $innerBounds->getRectWithSizeAndAlign($stringSize, $this->hAlignment, $this->vAlignment);

            $textHeight = $r->addTextBlock($text, $textStyle, $textLayout, $this->hAlignment, $this->vAlignment, $textStyle->getTextColor());
            $textLayout->top += $textHeight;
        }
        return $stringSize;
    }

    /**
     * Returns the text for the column, depending on the row
     * @param bool $headerRow
     * @param TableRow|null $row
     * @return string
     */
    public function getString(bool $headerRow, ?TableRow $row): string
    {
        if ($headerRow) {
            return $this->title;
        }
        if (!is_null($row)) {
            return $row->getText($this->columnName);
        }

        return "";
    }


    /**
     * Returns the TextStyle that has to be used for the crrent column
     * @param TableRow|null $row
     * @param bool $headerRow
     * @param bool $alternatingRow
     * @return TextStyle
     */
    public function getTextStyle(?TableRow $row, bool $headerRow, bool $alternatingRow): TextStyle
    {
        if (!is_null($row) && $row->getRowType() != 'D') {

            if ($row->getRowType() == 'H') {
                $style = $this->headerTextStyle;
            } else if ($row->getRowType() == 'S') {
                $style = $this->subTotalRowTextStyle;
            } else if ($row->getRowType() == 'T') {
                $style = $this->totalRowTextStyle;
            } else {
                $style = $this->detailRowTextStyle;
            }
        } else {

            if ($headerRow) {
                $style = $this->headerTextStyle;
            } else {
                if ($alternatingRow) {
                    $style = $this->alternatingRowTextStyle;
                } else {
                    $style = $this->detailRowTextStyle;
                }
            }
        }
        return $style;
    }

    /**
     * Draws the vertical line between columns
     * @param Renderer $r
     * @param float $x
     * @param float $y
     * @param float $height
     * @return void
     */
    public function drawRightLine(Renderer $r, float $x, float $y, float $height): void
    {
        if ($this->rightPen->getExtent() != 0.0) {
            $x -= $this->rightPen->getExtent();
            $r->addLine($x, $y, $x, $y + $height, $this->rightPen->getExtent(), $this->rightPen->getDash(), $this->rightPen->getColor());
        }
    }
}
