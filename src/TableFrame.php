<?php
/*
 * //============================================================+
 * // File name     : TableFrame.php
 * // Version       : 1.0.0
 * // Last Update   : 23.12.22, 06:49
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

declare (strict_types = 1);

namespace Adi\ReportLib;

/**
 * @class TableFrame
 * Class for a table in a report. It is a simple frame with no sub-frames in it.
 * The table has a list of columns and data rows.
 * @brief Class representing a table in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class TableFrame extends ReportFrame
{
    const C_HEADER_ROW_INDEX = -1;

    /**
     * Array with the row data for the table
     * @var array
     */
    protected array $tableData;

    /**
     * Array with the definitions of the columns of the table
     * @var array
     */
    protected array $columns;

    /**
     * Width of the table
     * @var float
     */
    protected float $width;

    protected int $rowIndex;
    protected int $dataRowsFit;

    /**
     * Number of rows that have to fit on one page before a page break will be inserted
     * @var int
     */
    protected int $minDataRowsFit;

    /**
     * Flag if the table header will be printed on each page
     * @var bool
     */
    protected bool $repeatHeaderRow;

    /**
     * Flag if the header will be printed at all
     * @var bool
     */
    protected bool $suppressHeaderRow;

    /**
     * Minimal height of the header
     * @var float
     */
    protected float $minHeaderRowHeight;

    /**
     * Minimal height of a detail row
     * @var float
     */
    protected float $minDetailRowHeight;

    /**
     * Max height of the header
     * @var float
     */
    protected float $maxHeaderRowHeight;

    /**
     * Max height of a detail row
     * @var float
     */
    protected float $maxDetailRowHeight;

    /**
     * Additional bottom padding for subtotal rows
     * @var float
     */
    protected float $marginBottomSubtotal;

    /**
     * Text style for header rows
     * @var TextStyle
     */
    protected TextStyle $headerTextStyle;

    /**
     * Text style for detail rows
     * @var TextStyle
     */
    protected TextStyle $detailRowTextStyle;

    /**
     * Text style for subtotal rows
     * @var TextStyle
     */
    protected TextStyle $subTotalRowTextStyle;

    /**
     * Text style for total rows
     * @var TextStyle
     */
    protected TextStyle $totalRowTextStyle;

    /**
     * Text style for alternate rows (even/odd line number)
     * @var TextStyle|null
     */
    protected ?TextStyle $alternatingRowTextStyle;

    /**
     * Border of the table
     * @var Border
     */
    protected Border $border;

    /**
     * Pen for the line below a header row
     * @var Pen
     */
    protected Pen $innerPenHeaderBottom;

    /**
     * Pen for the line above a total row
     * @var Pen
     */
    protected Pen $innerPenTotalTop;

    /**
     * Pen for the line between rows
     * @var Pen
     */
    protected Pen $innerPenRow;

    /**
     * Space between rows
     * @var float
     */
    protected float $interRowSpace;

    /**
     * Flag if vertical lines between columns shall be printed or not
     * @var bool
     */
    protected bool $columnLines;

    /**
     * Number of sub rows in the table
     * @var int
     */
    protected int $numSubRows;

    /**
     * Array with the heights of sub rows
     * @var array
     */
    protected array $subRowHeightList;

    /**
     * Flag if the header size has been calculated
     * @var bool
     */
    protected bool $headerSizeInit;

    /**
     * The height of the table in the current page
     * @var float
     */
    protected float $tableHeightForPage;

    /**
     * Array with height of the rows of the table
     * @var array
     */
    protected array $rowHeights;

    /**
     * Height of the header row
     * @var float
     */
    protected float $headerRowHeight;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->tableHeightForPage = 0.0;
        $this->headerRowHeight = 0.0;
        $this->columnLines = false;
        $this->minHeaderRowHeight = 0;
        $this->minDetailRowHeight = 0;
        $this->maxHeaderRowHeight = 100;
        $this->maxDetailRowHeight = 100;
        $this->marginBottomSubtotal = DEF_COLUMN_MARGIN_BOTTOM_SUBTOTAL;

        $this->interRowSpace = 0;

        $this->headerTextStyle = TextStyles::getTextStyle(TextStyles::TABLE_HEADER);
        $this->detailRowTextStyle = TextStyles::getTextStyle(TextStyles::TABLE_ROW);
        $this->alternatingRowTextStyle = null;
        $this->subTotalRowTextStyle = TextStyles::getTextStyle(TextStyles::TABLE_SUBTOTAL);
        $this->totalRowTextStyle = TextStyles::getTextStyle(TextStyles::TABLE_TOTAL);

        $this->headerSizeInit = false;
        $this->minDataRowsFit = 1;

        $this->border = new Border();
        $this->rowHeights = array();

        $this->repeatHeaderRow = true;
        $this->suppressHeaderRow = false;

        $this->innerPenHeaderBottom = new Pen(DEF_COLUMN_LINE_HEADER_EXTEND);
        $this->innerPenTotalTop = new Pen(DEF_COLUMN_LINE_TOTAL_EXTEND);
        $this->innerPenRow = new Pen(DEF_COLUMN_LINE_EXTEND);

        $this->tableData = array();
        $this->width = 0.0;

        $this->numSubRows = 1;
        $this->subRowHeightList = array();
    }

    /**
     * @return float
     */
    public function getMarginBottomSubtotal(): float
    {
        return $this->marginBottomSubtotal;
    }

    /**
     * @param float $marginBottomSubtotal
     */
    public function setMarginBottomSubtotal(float $marginBottomSubtotal): void
    {
        $this->marginBottomSubtotal = $marginBottomSubtotal;
    }

    /**
     * @return bool
     */
    public function isRepeatHeaderRow(): bool
    {
        return $this->repeatHeaderRow;
    }

    /**
     * @param bool $repeatHeaderRow
     */
    public function setRepeatHeaderRow(bool $repeatHeaderRow): void
    {
        $this->repeatHeaderRow = $repeatHeaderRow;
    }

    /**
     * @return bool
     */
    public function isSuppressHeaderRow(): bool
    {
        return $this->suppressHeaderRow;
    }

    /**
     * @param bool $suppressHeaderRow
     */
    public function setSuppressHeaderRow(bool $suppressHeaderRow): void
    {
        $this->suppressHeaderRow = $suppressHeaderRow;
    }

    /**
     * @return float
     */
    public function getMinHeaderRowHeight(): float
    {
        return $this->minHeaderRowHeight;
    }

    /**
     * @param float $minHeaderRowHeight
     */
    public function setMinHeaderRowHeight(float $minHeaderRowHeight): void
    {
        $this->minHeaderRowHeight = $minHeaderRowHeight;
    }

    /**
     * @return float
     */
    public function getMinDetailRowHeight(): float
    {
        return $this->minDetailRowHeight;
    }

    /**
     * @param float $minDetailRowHeight
     */
    public function setMinDetailRowHeight(float $minDetailRowHeight): void
    {
        $this->minDetailRowHeight = $minDetailRowHeight;
    }

    /**
     * @return float
     */
    public function getMaxHeaderRowHeight(): float
    {
        return $this->maxHeaderRowHeight;
    }

    /**
     * @param float $maxHeaderRowHeight
     */
    public function setMaxHeaderRowHeight(float $maxHeaderRowHeight): void
    {
        $this->maxHeaderRowHeight = $maxHeaderRowHeight;
    }

    /**
     * @return float
     */
    public function getMaxDetailRowHeight(): float
    {
        return $this->maxDetailRowHeight;
    }

    /**
     * @param float $maxDetailRowHeight
     */
    public function setMaxDetailRowHeight(float $maxDetailRowHeight): void
    {
        $this->maxDetailRowHeight = $maxDetailRowHeight;
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
     * @return TextStyle|null
     */
    public function getAlternatingRowTextStyle(): ?TextStyle
    {
        return $this->alternatingRowTextStyle;
    }

    /**
     * @param TextStyle|null $alternatingRowTextStyle
     */
    public function setAlternatingRowTextStyle(?TextStyle $alternatingRowTextStyle): void
    {
        $this->alternatingRowTextStyle = $alternatingRowTextStyle;
    }

    /**
     * @return Border
     */
    public function getBorder(): Border
    {
        return $this->border;
    }

    /**
     * @param Border $border
     */
    public function setBorder(Border $border): void
    {
        $this->border = $border;
    }

    /**
     * @return Pen
     */
    public function getInnerPenHeaderBottom(): Pen
    {
        return $this->innerPenHeaderBottom;
    }

    /**
     * @param Pen $innerPenHeaderBottom
     */
    public function setInnerPenHeaderBottom(Pen $innerPenHeaderBottom): void
    {
        $this->innerPenHeaderBottom = $innerPenHeaderBottom;
    }

    /**
     * @return Pen
     */
    public function getInnerPenTotalTop(): Pen
    {
        return $this->innerPenTotalTop;
    }

    /**
     * @param Pen $innerPenTotalTop
     */
    public function setInnerPenTotalTop(Pen $innerPenTotalTop): void
    {
        $this->innerPenTotalTop = $innerPenTotalTop;
    }

    /**
     * @return Pen
     */
    public function getInnerPenRow(): Pen
    {
        return $this->innerPenRow;
    }

    /**
     * @param Pen $innerPenRow
     */
    public function setInnerPenRow(Pen $innerPenRow): void
    {
        $this->innerPenRow = $innerPenRow;
    }

    /**
     * @return float
     */
    public function getInterRowSpace(): float
    {
        return $this->interRowSpace;
    }

    /**
     * @param float $interRowSpace
     */
    public function setInterRowSpace(float $interRowSpace): void
    {
        $this->interRowSpace = $interRowSpace;
    }

    /**
     * @return bool
     */
    public function isColumnLines(): bool
    {
        return $this->columnLines;
    }

    /**
     * @param bool $columnLines
     */
    public function setColumnLines(bool $columnLines): void
    {
        $this->columnLines = $columnLines;
    }


    /**
     * Resets the calculated sizes
     * @return void
     */
    public function reset(): void
    {
        parent::reset();
        $this->headerSizeInit = false;
    }

    /**
     * Adds a column to the table
     * @param string $fieldName
     * @param string $headerText
     * @param float $maxWidth
     * @param string $hAlignment
     * @param float $marginRight
     * @return TableColumn
     */
    public function addColumn(string $fieldName, string $headerText, mixed $maxWidth, string $hAlignment = DEF_FRAME_H_ALIGNMENT, float $marginRight = 0.0): TableColumn
    {
        $tc = new TableColumn($fieldName, $headerText, $maxWidth);
        $this->initColumn($tc, $hAlignment, $marginRight);
        return $tc;
    }

    /**
     * Add a new row of data
     * @param TableRow $row
     * @return int Number of the row
     */
    public function addDataRow(TableRow $row): int
    {
        $this->tableData[] = $row;
        return count($this->tableData);
    }

    /**
     * Inits the definition of one column
     * @param TableColumn $tc The column
     * @param string $hAlignment The horizontal alignment
     * @param float $paddingRight Additional padding on the right side of the columns
     * @return int Number of the column
     */
    protected function initColumn(TableColumn $tc, string $hAlignment, float $paddingRight): int
    {
        $tc->setHeaderTextStyle($this->headerTextStyle);
        $tc->setDetailRowTextStyle($this->detailRowTextStyle);
        $tc->setSubTotalRowTextStyle($this->subTotalRowTextStyle);
        $tc->setTotalRowTextStyle($this->totalRowTextStyle);
        $alter = $this->detailRowTextStyle;
        if (!is_null($this->alternatingRowTextStyle)) {
            $alter = $this->alternatingRowTextStyle;
        }
        $tc->setAlternatingRowTextStyle($alter);

        $tc->setHAlignment($hAlignment);
        $tc->setPaddingRight($paddingRight + DEF_COLUMN_PADDING_RIGHT);
        $tc->setPaddingLeft(DEF_COLUMN_PADDING_LEFT);

        $this->columns[] = $tc;

        return count($this->columns);
    }


    /**
     * Calculate the size of the header
     * @param Renderer $r
     * @param Rect $rect
     * @return Size
     */
    protected function calcHeaderSize(Renderer $r, Rect $rect): Size
    {
        if (!$this->headerSizeInit) {
            $width = $rect->getWidth();
            $this->resizeColumns($width);

            $this->headerRowHeight = $this->sizePrintRow($r, TableFrame::C_HEADER_ROW_INDEX, $rect->left, $rect->top, $this->maxDetailRowHeight, true, true);

            $this->headerSizeInit = true;
        }

        return new Size($this->width, $this->headerRowHeight);
    }

    /**
     * Returns the rectangle into which the table has to be printed
     * @param Rect $forRect
     * @param Size|null $size
     * @return Rect
     */
    protected function getTableBounds(Rect $forRect, Size $size = null): Rect
    {
        if (is_null($size)) {
            $size = $this->border->addBorderSize($this->getHeaderSize());
            $rect = $forRect->getRectWithSizeAndAlign($size, $this->hAlignment, $this->vAlignment);

            return new Rect($rect->left, $forRect->top, $rect->right, $forRect->bottom);
        }

        $rect = $forRect->getRectWithSizeAndAlign($size, $this->hAlignment, $this->vAlignment);

        return new Rect($rect->left, $rect->top, $rect->right, $rect->bottom);

    }

    /**
     * Returns the calculated size of the header row
     * @return Size
     */
    protected function getHeaderSize(): Size
    {
        return new Size($this->width, $this->headerRowHeight);
    }

    /**
     * Calculates and / or prints a header row of the table
     * @param Renderer $r
     * @param Rect $inRect
     * @param bool $sizeOnly Flag if we are calculating the size or if we do the actual printing
     * @return bool
     */
    protected function sizePrintHeader(Renderer $r, Rect $inRect, bool $sizeOnly): bool
    {
        $headerFits = true;
        if (!$this->suppressHeaderRow && $this->repeatHeaderRow) {
            if ($inRect->sizeFits($this->getHeaderSize())) {
                if (!$sizeOnly) {
                    $this->sizePrintRow($r, TableFrame::C_HEADER_ROW_INDEX, $inRect->left, $inRect->top, $this->headerRowHeight, false, false);
                }
                $inRect->top += $this->headerRowHeight;
            } else {
                $headerFits = false;
            }
        }
        return $headerFits;
    }

    /**
     * Finds the number of table rows that fits in the rectangle
     * @param Renderer $r
     * @param Rect $inRect Rect to check how many rows fits into
     * @return int The number of rows that fits
     */
    protected function findDataRowsFit(Renderer $r, Rect $inRect): int
    {
        static $saveY = -1.0;

        $rowsThatFit = 0;
        $index = $this->rowIndex;
        $this->rowHeights = array();

        while ($index < $this->getTotalRows()) {
            $includeRowLine = $index < $this->getTotalRows() - 1;
            $rowHeight = $this->sizePrintRow($r, $index, $inRect->left, $inRect->top, $this->maxDetailRowHeight, true, $includeRowLine);

            if ($inRect->sizeFits(new Size($this->width, $rowHeight))) {
                $this->rowHeights[] = $rowHeight;
                $inRect->top += $rowHeight;
                $index++;
                $rowsThatFit++;
            } else {
                if (count($this->rowHeights) > 0) {
                    $rowHeight = $this->sizePrintRow($r, $index - 1, $inRect->left, $inRect->top, $this->maxDetailRowHeight, true, false);

                    $inRect->top -= ($this->rowHeights[count($this->rowHeights) - 1]);
                    $inRect->top += $rowHeight;
                    $this->rowHeights[count($this->rowHeights) - 1] = $rowHeight;
                } else {
                    if ($saveY < 40 && $saveY == $inRect->top) {

                        $this->rowHeights[] = $inRect->getHeight();
                        $inRect->top += $inRect->getHeight();
                        $index++;
                        $rowsThatFit++;
                    } else {
                        $saveY = $inRect->top;
                    }
                }
                break;
            }
        }

        if ($this->minDataRowsFit != 0 && $index < $this->getTotalRows()) {
            if ($rowsThatFit < $this->minDataRowsFit) {
                $rowsThatFit = 0;
            } else {
                $rowsLeft = $this->getTotalRows() - $index;
                if ($rowsLeft + $rowsThatFit < (2 * $this->minDataRowsFit)) {
                    $rowsThatFit = 0;
                } else if ($this->minDataRowsFit > $rowsLeft) {
                    $rowsThatFit -= ($this->minDataRowsFit - $rowsLeft);
                }
            }
        }
        return $rowsThatFit;
    }

    /**
     * Returns the number of rows in the table
     * @return int
     */
    protected function getTotalRows(): int
    {
        return count($this->tableData);
    }

    /**
     * Prints the rows ot the table
     * @param Renderer $r
     * @param Rect $inRect
     * @return void
     */
    protected function printRows(Renderer $r, Rect $inRect): void
    {
        for ($rowCount = 0; $rowCount < $this->dataRowsFit; $rowCount++, $this->rowIndex++) {
            $height = $this->rowHeights[$rowCount];
            $this->sizePrintRow($r, $this->rowIndex, $inRect->left, $inRect->top, $height, false, false);
            $inRect->top += $height;
        }
    }

    /**
     * Checks if the table has or needs sub rows
     * Sub-rows can be defined explicitly, or they will be inserted if the
     * absolute width of all columns is bigger then the width of the table
     * @param float $width
     * @return void
     */
    protected function checkForSubRows(float $width) : void
    {
        $colsWidth = 0.0;
        for ($colNumber = 0; $colNumber < count($this->columns); $colNumber++) {

            /** @var TableColumn $column */
            $column = $this->columns[$colNumber];
            $column->calcWidth($width);
            $colsWidth += $column->getWidthToUse();

            $nextColNumber = $colNumber + 1;
            if ($nextColNumber < count($this->columns)) {

                /** @var TableColumn $nextColumn */
                $nextColumn = $this->columns[$nextColNumber];
                $nextColumn->calcWidth($width);
                if ($colsWidth + $nextColumn->getWidthToUse() > $width) {

                    $column->setLineBreak(true);
                    $this->numSubRows++;

                    if ($colsWidth > $this->width) {
                        $this->width = $colsWidth;
                    }
                    $colsWidth = 0.0;
                }
            }
        }
        if ($colsWidth > $this->width) {
            $this->width = $colsWidth;
        }
    }

    /**
     * Calculates the width of each column based on the width of the table
     * @param float $width
     * @return void
     */
    protected function resizeColumns(float $width): void
    {
        $this->checkForSubRows($width);

        if ($this->useFullWidth) {
            $this->adjustColumnsToWidth($width);
        }
    }


    /**
     * Calculates the width of each column based on the width of the table
     * @param float $maxWidth Max width of the table
     * @return void
     */
    protected function adjustColumnsToWidth(float $maxWidth): void
    {
        if (count($this->columns) == 0 || $maxWidth <= 0) {
            return;
        }

        $maxWidth -= $this->marginLeft;
        $maxWidth -= $this->marginRight;

        $dCurWidth = 0.0;
        $firstCol = 0;
        for ($colNumber = 0; $colNumber < count($this->columns); $colNumber++) {

            /** @var TableColumn $column */
            $column = $this->columns[$colNumber];

            $dCurWidth += $column->getWidthToUse();

            if ($column->isLineBreak() && $dCurWidth > 0.0) {

                $dDelta = $maxWidth / $dCurWidth * 10000;
                $delta = (int)$dDelta;
                $dDelta = $delta / 10000.0;

                for ($colNr = $firstCol; $colNr <= $colNumber; $colNr++) {

                    /** @var TableColumn $column */
                    $column = $this->columns[$colNr];

                    $column->setWidth($column->getWidth() * $dDelta);
                    $column->setWidthToUse($column->getWidth());
                }

                $firstCol = $colNumber + 1;
                $dCurWidth = 0.0;
            }
        }

        if ($dCurWidth > 0.0) {
            $dDelta = $maxWidth / $dCurWidth * 10000;
            $delta = (int)$dDelta;
            $dDelta = $delta / 10000.0;

            for ($colNr = $firstCol; $colNr < count($this->columns); $colNr++) {

                /** @var TableColumn $column */
                $column = $this->columns[$colNr];

                $column->setWidthToUse($column->getWidthToUse() * $dDelta);
            }
        }
        $this->width = $maxWidth;
    }

    /**
     * Returns the actual valid height of the current row
     * @param float $height Height to be checked
     * @param bool $isHeader Flag if it is a header row
     * @return float The height to use for the row
     */
    protected function getValidHeight(float $height, bool $isHeader): float
    {
        if ($isHeader) {
            $min = $this->minHeaderRowHeight;
            $max = $this->maxHeaderRowHeight;
        } else {
            $min = $this->minDetailRowHeight;
            $max = $this->maxDetailRowHeight;
        }

        if ($height < $min) {
            return $min;
        } else if ($height > $max) {
            return $max;
        } else {
            return $height;
        }
    }


    /**
     * Calculates and / or prints a row of the table
     * @param Renderer $r Class that can add the text and lines to the report
     * @param int $rowIndex Row number
     * @param float $x Current x pos
     * @param float $y Current y pos
     * @param float $maxHeight Max height of the row
     * @param bool $sizeOnly Flag if we are calculating the size or if we do the actual printing
     * @param bool $showLine Flag if a line has to be printed or not
     * @return float
     */
    protected function sizePrintRow(Renderer $r, int $rowIndex, float $x, float $y, float $maxHeight, bool $sizeOnly, bool $showLine): float
    {
        $isHeader = ($rowIndex == TableFrame::C_HEADER_ROW_INDEX);
        $altRow = (($rowIndex % 2) != 0);
        $rowHeight = 0.0;
        $currRowHeight = 0.0;
        $xPos = $x;
        $yPos = $y;
        $row = null;
        if (!$isHeader) {
            $row = $this->tableData[$rowIndex];
        }

        $curSubRow = 0;

        if (!$sizeOnly && $this->numSubRows > 1) {
            $maxHeight = $this->getValidHeight($this->getSubRowHeight($rowIndex + 1, $curSubRow), $isHeader);
        }

        for ($colNumber = 0; $colNumber < count($this->columns); $colNumber++) {

            /** @var TableColumn $column */
            $column = $this->columns[$colNumber];

            $colW = $column->getWidthToUse();
            if (!is_null($row)) {
                if ($row->getJoinStart() >= 0 && $row->getJoinEnd() >= 0) {
                    if ($colNumber == $row->getJoinStart()) {

                        /** @var TableColumn $colToStart */
                        $colToStart = $this->columns[$colNumber];
                        $colW = $colToStart->getWidth();

                        // Add additional column width get the total width of the joined column
                        for ($joinCol = $row->getJoinStart() + 1; $joinCol <= $row->getJoinEnd() && $joinCol < count($this->columns); $joinCol++) {

                            /** @var TableColumn $colInJoin */
                            $colInJoin = $this->columns[$joinCol];
                            $colW += $colInJoin->getWidth();

                            // Remove text from columns in the join
                            $row->setText($colInJoin->getColumnName(), "");
                        }
                    }
                }
            }

            $text = $column->getString($isHeader, $row);
            $textStyle = $column->getTextStyle($row, $isHeader, $altRow);

            $size = $column->sizePaintCell($r, $text, $textStyle, $xPos, $yPos, $colW, $maxHeight, $sizeOnly);
            $currRowHeight = max($currRowHeight, $this->getValidHeight($size->height, $isHeader));

            $xPos += $column->getWidthToUse();

            if ($column->isLineBreak()) {
                if ($sizeOnly) {
                    $this->setSubRowHeight($rowIndex + 1, $curSubRow, $currRowHeight);
                    $curSubRow++;
                } else {
                    $curSubRow++;
                    $maxHeight = $this->getValidHeight($this->getSubRowHeight($rowIndex + 1, $curSubRow), $isHeader);
                }

                $rowHeight += $currRowHeight;

                $yPos += $currRowHeight;
                $xPos = $x;

                $currRowHeight = 0.0;
            }
        }
        $rowHeight += $currRowHeight;

        if ($sizeOnly && $this->numSubRows > 1) {
            $this->setSubRowHeight($rowIndex + 1, $curSubRow, $currRowHeight);
        } else {
            $rowHeight = $currRowHeight;
        }

        if ($showLine) {
            $rowHeight += $this->rowLine($r, $x, $yPos + $rowHeight, $this->width, $isHeader, false, $sizeOnly);
        }

        if (!$isHeader) {
            $rowHeight += $this->interRowSpace;
        }

        if ($row != null && $row->getRowType() == 'S') {
            $rowHeight += $this->rowLine($r, $x, $y, $this->width, false, true, $sizeOnly);
            $rowHeight += $this->marginBottomSubtotal;
        }

        if ($row != null && $row->getRowType() == 'T') {
            $rowHeight += $this->rowLine($r, $x, $y, $this->width, false, true, $sizeOnly);
        }

        return $rowHeight;
    }

    /**
     * Print a line for the row
     * @param Renderer $r Class that can add lines to the report
     * @param float $x Start x point
     * @param float $y Start y point
     * @param float $length Length of the line
     * @param bool $isHeader Flag for header rows
     * @param bool $isTotal Flag for total rows
     * @param bool $sizeOnly Flag if we are calculating the size or if we do the actual printing
     * @return float
     */
    protected function rowLine(Renderer $r, float $x, float $y, float $length, bool $isHeader, bool $isTotal, bool $sizeOnly): float
    {
        $height = 0;
        if ($isHeader) {
            $pen = $this->innerPenHeaderBottom;
        } else if ($isTotal) {
            $pen = $this->innerPenTotalTop;
        } else {
            $pen = $this->innerPenRow;
        }

        if ($pen->getExtent() != 0.0) {
            if (!$sizeOnly) {
                $y -= $pen->getExtent() / 2.0;
                $r->addLine($x, $y, $x + $length, $y, $pen->getExtent(), $pen->getDash(), $pen->getColor());

            }
            $height = $pen->getExtent();
        }
        return $height;
    }

    /**
     * Prints the defined rows into the table
     * @param Renderer $r Class that can add lines to the report
     * @param Rect $rect
     * @param bool $includeHeader
     * @return void
     */
    protected function printAllRowLines(Renderer $r, Rect $rect, bool $includeHeader): void
    {
        $x = $rect->left;
        $y = $rect->top;
        $rowWidth = $rect->getWidth();
        if ($includeHeader) {
            $this->rowLine($r, $x, $y + $this->headerRowHeight, $rowWidth, true, false, false);
            $y += $this->headerRowHeight;
        }

        for ($rowCount = 0; $rowCount < $this->dataRowsFit - 1; $rowCount++) {
            $height = $this->rowHeights[$rowCount];
            $this->rowLine($r, $x, $y + $height, $rowWidth, false, false, false);
            $y += $height;
        }
    }

    /**
     * Prints all the defined columns lines in the table
     * @param Renderer $r Class that can add lines to the report
     * @param Rect $rect
     * @return void
     */
    protected function printAllColumnLines(Renderer $r, Rect $rect) : void
    {
        if ($this->numSubRows > 1 || !$this->columnLines) {
            return;
        }

        $x = $rect->left;
        $y = $rect->top;

        for ($colNumber = 0; $colNumber < count($this->columns); $colNumber++) {

            /** @var TableColumn $column */
            $column = $this->columns[$colNumber];
            $x += $column->getWidthToUse();

            $column->drawRightLine($r, $x, $y, $rect->getHeight());
        }
    }

    /**
     * Returns the calculated height of a sub row
     * @param int $row The row to which the sub row belongs
     * @param int $subRow The number of the sub row
     * @return float The saved height
     */
    protected function getSubRowHeight(int $row, int $subRow): float
    {
        $height = 0.0;
        if (key_exists($row, $this->subRowHeightList)) {
            if (key_exists($subRow, $this->subRowHeightList[$row])) {
                $height = $this->subRowHeightList[$row][$subRow];
            }
        }
        return $height;
    }

    /**
     * Saves the height of a sub row
     * @param int $row The row to which the sub row belongs
     * @param int $subRow The number of the sub row
     * @param float $height The height of the sub row
     * @return void
     */
    public function setSubRowHeight(int $row, int $subRow, float $height): void
    {
        if (key_exists($row, $this->subRowHeightList)) {
            $this->subRowHeightList[$row][$subRow] = $height;
        } else {
            $this->subRowHeightList[$row] = array();
            $this->subRowHeightList[$row][$subRow] = $height;
        }
    }

    /**
     * Calculates the size of the table for the given rectangle
     * @param Renderer $r Class that can add the texts and lines to the report
     * @param Rect $forRect Rect in which the table has to be printed
     * @return SizeState
     */
    protected function doCalcSize(Renderer $r, Rect $forRect): SizeState
    {
        $sizeStates = new SizeState();

        $insideBorder = $this->border->getInnerRect($forRect);
        $this->calcHeaderSize($r, $insideBorder);
        $tableBounds = $this->getTableBounds($insideBorder);
        $originalPositionY = $tableBounds->top;

        if ($this->sizePrintHeader($r, $tableBounds, true)) {
            $this->dataRowsFit = $this->findDataRowsFit($r, $tableBounds);
            $this->tableHeightForPage = $tableBounds->top - $originalPositionY;
            if ($this->getTotalRows() == 0) {
                $sizeStates->fits = true;
            } else if ($this->dataRowsFit > 0) {
                $sizeStates->fits = true;
                if ($this->rowIndex + $this->dataRowsFit < $this->getTotalRows()) {
                    $sizeStates->continued = true;
                }
            } else {
                $sizeStates->continued = true;
                if ($this->dataRowsFit < $this->minDataRowsFit) {
                    $sizeStates->fits = false;
                }
            }
        } else {
            $sizeStates->fits = false;
            $sizeStates->continued = true;
        }

        $sizeStates->requiredSize = $this->border->addBorderSize(new Size ($this->width, $this->tableHeightForPage));

        return $sizeStates;
    }

    /**
     * Prints the table into the calculated rectangle
     * @param Renderer $r Class that can add the text and lines to the report
     * @param Rect $inRect Rect into which the table will be printed
     * @return void
     */
    protected function doPrint(Renderer $r, Rect $inRect): void
    {
        $tableBounds = $this->getTableBounds($inRect, $this->requiredSize);
        $insideBorders = $this->border->getInnerRect($tableBounds);
        $printingBounds = new Rect(rect: $insideBorders);

        $this->sizePrintHeader($r, $printingBounds, false);
        $this->printRows($r, $printingBounds);

        $this->printAllRowLines($r, $insideBorders, (!$this->suppressHeaderRow && $this->repeatHeaderRow));
        $this->printAllColumnLines($r, $insideBorders);
        $this->border->drawBorder($r, $tableBounds->getRectWithSizeAndAlign());
    }

    /**
     * Will be called when the printing begins
     * Resets the internal index for the current row
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r): void
    {
        if ($this->suppressHeaderRow || $this->repeatHeaderRow) {
            $this->rowIndex = 0;
        } else {
            $this->rowIndex = -1;
        }

        $this->dataRowsFit = 0;
    }

}