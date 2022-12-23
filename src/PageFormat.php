<?php
/*
 * //============================================================+
 * // File name     : PageFormat.php
 * // Version       : 1.0.0
 * // Last Update   : 22.12.22, 15:10
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

include_once __DIR__ . "/../config/config.php";
include_once "Size.php";

require __DIR__ . "/../vendor/autoload.php";
use TCPDF_STATIC;


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
     * Page orientation
     * @var string
     */
    protected string $pageOrientation;

    /**
     * Page format
     * @var string
     */
    protected string $pageFormat;

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
     * @param string $pageFormat
     * @param string $pageOrientation
     * @param float $marginLeft
     * @param float $marginTop
     * @param float $marginRight
     * @param float $marginBottom
     * @param bool $mirrorMargins
     */
    public function __construct(string $pageFormat = DEF_PAGE_FORMAT, string $pageOrientation = DEF_PAGE_ORIENTATION, float $marginLeft = DEF_PAGE_MARGIN_LEFT, float $marginTop = DEF_PAGE_MARGIN_TOP, float $marginRight = DEF_PAGE_MARGIN_RIGHT, float $marginBottom = DEF_PAGE_MARGIN_BOTTOM, bool $mirrorMargins = false)
    {
        $this->pageFormat = $pageFormat;
        $this->pageOrientation = $pageOrientation;
        $this->marginLeft = $marginLeft;
        $this->marginTop = $marginTop;
        $this->marginRight = $marginRight;
        $this->marginBottom = $marginBottom;
        $this->mirrorMargins = $mirrorMargins;
    }

    /**
     * @param string $pageOrientation
     */
    public function setPageOrientation(string $pageOrientation): void
    {
        $this->pageOrientation = $pageOrientation;
    }

    /**
     * @param string $pageFormat
     */
    public function setPageFormat(string $pageFormat): void
    {
        $this->pageFormat = $pageFormat;
    }

    /**
     * @return string
     */
    public function getPageOrientation() : string
    {
        return $this->pageOrientation;
    }

    /**
     * @return string
     */
    public function getPageFormat() : string
    {
        return $this->pageFormat;
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
     */
    public function setMarginTop(float $marginTop): void
    {
        $this->marginTop = $marginTop;
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
     */
    public function setMarginLeft(float $marginLeft): void
    {
        $this->marginLeft = $marginLeft;
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
     */
    public function setMarginRight(float $marginRight): void
    {
        $this->marginRight = $marginRight;
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
     */
    public function setMarginBottom(float $marginBottom): void
    {
        $this->marginBottom = $marginBottom;
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
     */
    public function setMirrorMargins(bool $mirrorMargins): void
    {
        $this->mirrorMargins = $mirrorMargins;
    }

    /**
     * Returns the printable width on the page,
     * i.e. the width of the paper minus the left and right margins
     * @return float
     */
    public function getPrintableWidth() : float
    {
        return $this->getPageBounds()->getWidth();
    }

    /**
     * Returns the printable height on the page,
     * i.e. the height of the paper minus the top and bottom margins
     * @return float
     */
    public function getPrintableHeight() : float
    {
        return $this->getPageBounds()->getHeight();
    }

    /**
     * Returns a rectangle with the printable area on a page with this format.
     * The orientation defines which is the longer side.
     * @param int $page Page number that should be used - only needed when margins are mirrored
     * @return Rect The printable area
     */
    public function getPageBounds(int $page = 0) : Rect
    {
        // calculate the page size in millimeters
        $w = 210.0;
        $h = 297.0;

        if (key_exists($this->pageFormat, TCPDF_STATIC::$page_formats)) {
            $si = TCPDF_STATIC::$page_formats[$this->pageFormat];
            $w = $si[0]  * 25.4 / 72.0;
            $h = $si[1]  * 25.4 / 72.0;
        }

        if ($this->pageOrientation == 'P') {
            $size = new Size(round($w, 2), round($h, 2));
        } else {
            $size = new Size(round($h, 2), round($w, 2));
        }

        if ($this->mirrorMargins) {
            if ($page % 2 == 0) {
                $pageBounds = new Rect($this->marginRight, $this->marginTop,$size->width - $this->marginLeft, $size->height - $this->marginBottom);
            } else {
                $pageBounds = new Rect($this->marginLeft, $this->marginTop, $size->width - $this->marginRight, $size->height - $this->marginBottom);
            }
        } else {
            $pageBounds = new Rect($this->marginLeft, $this->marginTop, $size->width - $this->marginRight, $size->height - $this->marginBottom);
        }
        return $pageBounds;
    }

}