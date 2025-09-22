<?php
/*
 * //============================================================+
 * // File name     : PageFormat.php
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

include_once __DIR__ . "/../config/config.php";
include_once "Size.php";


/**
 * @class PageFormat
 * This class collects the information for a page format
 * It has an orientation (portrait, landscape) and a format (e.g. A4).
 * It has the definition of the margins for a page and a flag
 * if the left and right margins should be mirrored for odd and even pages.
 * @brief Settings for a page (format, size and margins)
 * @author Michael Hodel - info@adiuvaris.ch
 */
class PageFormat
{
    /**
     * Page orientation ("P" or "L")
     * @var string
     */
    protected string $pageOrientation;

    /**
     * Page size (e.g. 'A4')
     * @var string
     */
    protected string $pageSize;

    /**
     * Top margin
     * @var float
     */
    protected float $marginTop;

    /**
     * Left margin
     * @var float
     */
    protected float $marginLeft;

    /**
     * Right margin
     * @var float
     */
    protected float $marginRight;

    /**
     * Bottom margin
     * @var float
     */
    protected float $marginBottom;

    /**
     * Flag if the left and right margins should be mirrored
     * @var bool
     */
    protected bool $mirrorMargins;

    /**
     * Class constructor
     * @param string $pageSize
     * @param string $pageOrientation
     * @param float $marginLeft
     * @param float $marginTop
     * @param float $marginRight
     * @param float $marginBottom
     * @param bool $mirrorMargins
     */
    public function __construct(string $pageSize = DEF_PAGE_SIZE, string $pageOrientation = DEF_PAGE_ORIENTATION, float $marginLeft = DEF_PAGE_MARGIN_LEFT, float $marginTop = DEF_PAGE_MARGIN_TOP, float $marginRight = DEF_PAGE_MARGIN_RIGHT, float $marginBottom = DEF_PAGE_MARGIN_BOTTOM, bool $mirrorMargins = false)
    {
        $this->pageSize = $pageSize;
        $this->pageOrientation = $pageOrientation;
        $this->marginLeft = $marginLeft;
        $this->marginTop = $marginTop;
        $this->marginRight = $marginRight;
        $this->marginBottom = $marginBottom;
        $this->mirrorMargins = $mirrorMargins;
    }

    /**
     * @param string $pageOrientation
     * @return self
     */
    public function setPageOrientation(string $pageOrientation): self
    {
        $this->pageOrientation = $pageOrientation;
        return $this;
    }

    /**
     * @param string $pageSize
     * @return self
     */
    public function setPageSize(string $pageSize): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageOrientation(): string
    {
        return $this->pageOrientation;
    }

    /**
     * @return string
     */
    public function getPageSize(): string
    {
        return $this->pageSize;
    }

    /**
     * @return float
     */
    public function getMarginTop(): float
    {
        return $this->marginTop;
    }

    /**
     * @param float $marginTop
     * @return self
     */
    public function setMarginTop(float $marginTop): self
    {
        $this->marginTop = $marginTop;
        return $this;
    }

    /**
     * @return float
     */
    public function getMarginLeft(): float
    {
        return $this->marginLeft;
    }

    /**
     * @param float $marginLeft
     * @return self
     */
    public function setMarginLeft(float $marginLeft): self
    {
        $this->marginLeft = $marginLeft;
        return $this;
    }

    /**
     * @return float
     */
    public function getMarginRight(): float
    {
        return $this->marginRight;
    }

    /**
     * @param float $marginRight
     * @return self
     */
    public function setMarginRight(float $marginRight): self
    {
        $this->marginRight = $marginRight;
        return $this;
    }

    /**
     * @return float
     */
    public function getMarginBottom(): float
    {
        return $this->marginBottom;
    }

    /**
     * @param float $marginBottom
     * @return self
     */
    public function setMarginBottom(float $marginBottom): self
    {
        $this->marginBottom = $marginBottom;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMirrorMargins(): bool
    {
        return $this->mirrorMargins;
    }

    /**
     * @param bool $mirrorMargins
     * @return self
     */
    public function setMirrorMargins(bool $mirrorMargins): self
    {
        $this->mirrorMargins = $mirrorMargins;
        return $this;
    }
}
