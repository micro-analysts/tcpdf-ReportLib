<?php
/*
 * //============================================================+
 * // File name     : Report.php
 * // Version       : 1.0.0
 * // Last Update   : 30.12.22, 06:31
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

use Exception;

include_once __DIR__ . "/../config/config.php";
include_once "SerialFrame.php";
include_once "PageFrame.php";
include_once "Renderer.php";


/**
 * @class Report
 * This is the main class for reports in the ReportLib
 * It creates some basic frames and prints them to a PDF document
  * @brief Main report class to create a report as a PDF document
 * @author Michael Hodel - info@adiuvaris.ch
 */
class Report
{

    /**
     * Main container frame for the header
     * @var SerialFrame
     */
    protected SerialFrame $header;

    /**
     * Main container frame for th footer
     * @var SerialFrame
     */
    protected SerialFrame $footer;

    /**
     * Main container frame for the report body
     * @var SerialFrame
     */
    protected SerialFrame $body;

    /**
     * Flag if pages should be counted
     * Only set to true if the total number of pages is needed in the report
     * If true the report has to be calculated twice (two-pass)
     * @var bool
     */
    protected bool $countPages;

    /**
     * Max height the header may use
     * @var float
     */
    protected float $headerMaxHeight;

    /**
     * Max height the footer may use
     * @var float
     */
    protected float $footerMaxHeight;

    /**
     * Renderer to put data to a PDF document
     * @var Renderer
     */
    protected Renderer $renderer;

    /**
     * Class constructor
     * @param PageFormat $pageFormat
     */
    public function __construct(PageFormat $pageFormat = new PageFormat())
    {
        $this->renderer = new Renderer($pageFormat);
        $this->body = new SerialFrame('V');
        $this->header = new SerialFrame('V');
        $this->footer = new SerialFrame('V');
        $this->header->setVAlignment('T');
        $this->footer->setVAlignment('B');

        $this->headerMaxHeight = 0.0;
        $this->footerMaxHeight = 0.0;

        $this->countPages = false;
    }

    /**
     * Create output of the constructed report
     * @param string $repFileName Name of the PDF file
     * @param string $action Action to carry out with the PDF document F=save the file, I=preview the PDF
     * @return void
     * @throws Exception
     */
    public function output(string $repFileName, string $action = 'I') : void
    {
        if ($this->isEndless()) {
            throw new Exception("Endless recursion loop in the report structure.");
        }
        $this->printReport($this->renderer,$repFileName, $action);
    }

    /**
     * Checks if the parts of the report have an endless loop in the recursion of frames
     * The header, the footer and the body will be checked separately
     * @return bool
     */
    public function isEndless() : bool
    {
        $list = array();
        if ($this->header->isEndless($list)) {
            return true;
        }
        if ($this->footer->isEndless($list)) {
            return true;
        }
        return $this->body->isEndless($list);
    }

    /**
     * Returns a color string (web-color) based on the name
     * @param string $colorName
     * @return string
     */
    public function getColorByName(string $colorName) : string
    {
        return $this->renderer->getColorByName($colorName);
    }

    /**
     * @return SerialFrame
     */
    public function getHeader(): SerialFrame
    {
        return $this->header;
    }

    /**
     * @return SerialFrame
     */
    public function getFooter(): SerialFrame
    {
        return $this->footer;
    }

    /**
     * @return SerialFrame
     */
    public function getBody(): SerialFrame
    {
        return $this->body;
    }

    /**
     * @return bool
     */
    public function isCountPages(): bool
    {
        return $this->countPages;
    }

    /**
     * @param bool $countPages
     */
    public function setCountPages(bool $countPages): void
    {
        $this->countPages = $countPages;
    }

    /**
     * Prints the report
     * @param Renderer $r
     * @param string $repFileName
     * @param string $action
     * @return int
     * @throws Exception
     */
    protected function printReport(Renderer $r, string $repFileName, string $action): int
    {
        $this->onBeginPrint($r);
        while ($this->onPrintPage($r)) {
        }

        $this->onEndPrint($r, $repFileName, $action);

        return $r->getCurrentPage();
    }


    /**
     * @param Renderer $r
     * @return void
     */
    protected function onBeginPrint(Renderer $r): void
    {
        $r->createNewPDF();
        $r->setPagesCounted(false);
        $this->reset($r);
    }


    /**
     * @param Renderer $r
     * @return void
     */
    protected function reset(Renderer $r): void
    {
        $this->body->reset();
        $this->header->reset();
        $this->footer->reset();
    }

    /**
     * @param Renderer $r
     * @return bool
     * @throws Exception
     */
    protected function printAPage(Renderer $r): bool
    {
        $pageBounds = $r->getPageBounds();

        if ($this->header->getFrameCount() > 0) {
            $headerBounds = new Rect(rect: $pageBounds);

            if ($this->headerMaxHeight > 0) {
                $headerBounds->bottom = $headerBounds->top + $this->headerMaxHeight;
            }
            $this->header->print($r, $headerBounds);
            $this->header->reset();

            $pageBounds->top += $this->header->getSize()->height;
        }

        if ($this->footer->getFrameCount() > 0) {
            $footerBounds = new Rect(rect: $pageBounds);

            if ($this->footerMaxHeight > 0) {
                $footerBounds->top = $footerBounds->bottom + $this->footerMaxHeight;
            }

            $this->footer->calcSize($r, $footerBounds);
            $footerBounds = $footerBounds->getRectWithSizeAndAlign($this->footer->getSize(), $this->footer->getHAlignment(), $this->footer->getVAlignment());

            $this->footer->print($r, $footerBounds);
            $this->footer->reset();

            $pageBounds->bottom -= $this->footer->getSize()->height;
        }

        if ($this->body->getFrameCount() > 0) {
            $this->body->print($r, $pageBounds);
            $hasMorePages = $this->body->isContinued();
        } else {
            $hasMorePages = false;
        }

        return $hasMorePages;
    }


    /**
     * @param Renderer $r
     * @return int
     * @throws Exception
     */
    protected function countPages(Renderer $r): int
    {
        if (!$this->countPages) {
            return 0;
        }

        if (!$r->isPagesCounted()) {

            while ($this->printAPage($r)) {
                $r->addPage();
            }

            $r->setPagesCounted(true);

            $r->createNewPDF();
            $this->reset($r);
        }

        return $r->getTotalPages();
    }


    /**
     * @param Renderer $r
     * @return bool
     * @throws Exception
     */
    protected function onPrintPage(Renderer $r): bool
    {
        if ($this->countPages && !$r->isPagesCounted()) {
            $this->countPages($r);
        }

        $hasMorePages = $this->printAPage($r);

        if ($hasMorePages) {
            $r->addPage();
        }
        return $hasMorePages;
    }


    /**
     * @param Renderer $r
     * @param string $repFileName
     * @param string $action
     * @return void
     */
    protected function onEndPrint(Renderer $r, string $repFileName, string $action): void
    {
        if (strlen($repFileName) > 0) {
            $r->output($repFileName, $action);
        }
    }


}