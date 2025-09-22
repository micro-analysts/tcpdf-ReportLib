<?php
/*
 * //============================================================+
 * // File name     : TableRow.php
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
 * @class TableRow
 * Class to hold the data for one row in a TableFrame
 * The table has a list of columns and data rows.
 * @brief Data of a row in a TableFrame
 * @see TableFrame, TableColumn
 * @author Michael Hodel - info@adiuvaris.ch
 */
class TableRow
{
    /**
     * Array with the data for the row
     * The key is the name of a TableColumn and the data is a simple string
     * that may be formatted before inserting into this array
     * @var array
     */
    protected array $data;

    /**
     * RowType ('H' for header row, 'D' for a detail row etc.)
     * @var string
     */
    protected string $rowType;

    /**
     * Number of the first column that have to be joined for the output
     * @var int
     */
    protected int $joinStart;

    /**
     * Number of the last column that have to be joined for the output
     * @var int
     */
    protected int $joinEnd;

    /**
     * Class constructor
     * @param string $rowType Can be one of
     * <ul>
     * <li>'H' for header row</li>
     * <li>'D' for detail row</li>
     * <li>'S' for subtotal row</li>
     * <li>'T' for total row</li>
     * </ul>
     */
    public function __construct(string $rowType = 'D')
    {
        $this->data = array();
        $this->rowType = $rowType;
        $this->joinStart = -1;
        $this->joinEnd = -1;
    }

    /**
     * Returns the text for a column with the given name
     * @param string $columnName
     * @return string
     */
    public function getText(string $columnName): string
    {
        if (key_exists($columnName, $this->data)) {
            return $this->data[$columnName];
        }

        return "";
    }

    /**
     * Sets the value for a column in this row
     * @param string $columnName
     * @param string $value
     * @return void
     */
    public function setText(string $columnName, string $value): void
    {
        $this->data[$columnName] = $value;
    }

    /**
     * Returns the whole data for the row
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Sets the data for the whole row
     * Can be used if the data for the rows will be created somewhere else (e.g. database query)
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getRowType(): string
    {
        return $this->rowType;
    }

    /**
     * @param string $rowType
     */
    public function setRowType(string $rowType): void
    {
        $this->rowType = $rowType;
    }

    /**
     * @return int
     */
    public function getJoinStart(): int
    {
        return $this->joinStart;
    }

    /**
     * @param int $joinStart
     */
    public function setJoinStart(int $joinStart): void
    {
        $this->joinStart = $joinStart;
    }

    /**
     * @return int
     */
    public function getJoinEnd(): int
    {
        return $this->joinEnd;
    }

    /**
     * @param int $joinEnd
     */
    public function setJoinEnd(int $joinEnd): void
    {
        $this->joinEnd = $joinEnd;
    }
}
