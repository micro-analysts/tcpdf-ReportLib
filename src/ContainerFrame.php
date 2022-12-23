<?php
/*
 * //============================================================+
 * // File name     : ContainerFrame.php
 * // Version       : 1.0.0
 * // Last Update   : 22.12.22, 07:36
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

include_once "ReportFrame.php";


/**
 * @class ContainerFrame
 * This is an abstract class that may contain one or more instances
 * of frame types which inherit from ReportFrame.
 * It has some convenience functions to add other frames (their names start with a capital letter).
 * @brief Container class for multiple frames that will be printed into a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
abstract class ContainerFrame extends ReportFrame
{
    /**
     * Array of frames in the container
     * @var array
     */
    protected array $frames;

    /**
     * Index of current frame during calculation and printing
     * @var int
     */
    protected int $currentFrameIndex;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->currentFrameIndex = 0;
        $this->frames = array();
    }

    /**
     * Adds a vertical container
     * @param float $margin
     * @param bool $useFullWidth
     * @return SerialFrame
     */
    public function AddVContainer(float $margin = DEF_FRAME_MARGIN, bool $useFullWidth = true): SerialFrame
    {
        $sf = new SerialFrame('V');
        $this->addFrame($sf);
        $sf->setMargin($margin);
        $sf->setUseFullWidth($useFullWidth);
        return $sf;
    }

    /**
     * Adds a horizontal container
     * @param float $margin
     * @param bool $useFullWidth
     * @return SerialFrame
     */
    public function AddHContainer(float $margin = DEF_FRAME_MARGIN, bool $useFullWidth = false): SerialFrame
    {
        $sf = new SerialFrame('H');
        $this->addFrame($sf);
        $sf->setMargin($margin);
        $sf->setUseFullWidth($useFullWidth);
        return $sf;
    }

    /**
     * Adds a vertical distance
     * @param float $distance
     * @return SerialFrame
     */
    public function AddVDistance(float $distance): SerialFrame
    {
        $sf = new SerialFrame('V');
        $this->addFrame($sf);
        $sf->setMarginBottom($distance);
        return $sf;
    }

    /**
     * Adds a horizontal distance
     * @param float $distance
     * @return SerialFrame
     */
    public function AddHDistance(float $distance): SerialFrame
    {
        $sf = new SerialFrame('H');
        $this->addFrame($sf);
        $sf->setMarginRight($distance);
        return $sf;
    }

    /**
     * Adds a line
     * @param float $length
     * @param string $direction
     * @param string $vAlignment
     * @param string $hAlignment
     * @param float $extent
     * @param string $color
     * @return LineFrame
     */
    public function AddLine(float $length, string $direction = 'H', string $hAlignment = DEF_FRAME_H_ALIGNMENT, string $vAlignment = DEF_FRAME_V_ALIGNMENT, float $extent = DEF_LINE_EXTEND, string $color = DEF_LINE_COLOR): LineFrame
    {
        $lf = new LineFrame($direction, $extent, $color, $length);
        $this->addFrame($lf);
        $lf->setVAlignment($vAlignment);
        $lf->setHAlignment($hAlignment);
        return $lf;
    }

    /**
     * Adds a horizontal line
     * @param float $extent
     * @param string $color
     * @return LineFrame
     */
    public function AddHLine(float $extent = DEF_LINE_EXTEND, string $color = DEF_LINE_COLOR): LineFrame
    {
        $frame = $this->AddHContainer();
        $lf = $frame->AddLine(999.0, 'H', DEF_FRAME_H_ALIGNMENT, DEF_FRAME_V_ALIGNMENT, $extent, $color);
        $lf->setUseFullWidth(true);
        return $lf;
    }

    /**
     * Adds a vertical line
     * @param float $extent
     * @param string $color
     * @return LineFrame
     */
    public function AddVLine(float $extent = DEF_LINE_EXTEND, string $color = DEF_LINE_COLOR): LineFrame
    {
        $frame = $this->AddHContainer();
        $lf = $frame->AddLine(999.0, 'V', DEF_FRAME_H_ALIGNMENT, DEF_FRAME_V_ALIGNMENT, $extent, $color);
        $lf->setUseFullHeight(true);
        return $lf;
    }

    /**
     * Adds a box with a horizontal container in it
     * @param float|string $width
     * @param float $margin
     * @param bool $keepTogether
     * @param float $extent
     * @return SerialFrame
     */
    public function AddHBox(mixed $width = 0.0, float $margin = DEF_FRAME_MARGIN, bool $keepTogether = false, float $extent = DEF_LINE_EXTEND): SerialFrame
    {
        $box = $this->AddBox($width, $keepTogether, $extent);
        return $box->AddHContainer($margin, true);
    }

    /**
     * Adds a box with a vertical container in it
     * @param float|string $width
     * @param float $margin
     * @param bool $keepTogether
     * @param float $extent
     * @return SerialFrame
     */
    public function AddVBox(mixed $width = 0.0, float $margin = DEF_FRAME_MARGIN, bool $keepTogether = false, float $extent = DEF_LINE_EXTEND): SerialFrame
    {
        $box = $this->AddBox($width, $keepTogether, $extent);
        return $box->AddVContainer($margin);
    }

    /**
     * Adds a box with a background color and a horizontal container in it
     * @param string $background
     * @param float|string $width
     * @param float|string $height
     * @param bool $keepTogether
     * @param string $hAlignment
     * @return Serialframe
     */
    public function AddHBlock(string $background, mixed $width = 0.0, mixed $height = 0.0, bool $keepTogether = false, string $hAlignment = DEF_FRAME_H_ALIGNMENT): Serialframe
    {
        $bf = $this->AddBox($width, $keepTogether);
        $bf->setHeight($height);
        $bf->setBackground($background);
        $bf->setHAlignment($hAlignment);
        return $bf->AddHContainer(0.0, true);
    }

    /**
     * Adds a box with a background color and a vertical container in it
     * @param string $background
     * @param float|string $width
     * @param float|string $height
     * @param bool $keepTogether
     * @param string $vAlignment
     * @return Serialframe
     */
    public function AddVBlock(string $background, mixed $width = 0.0, mixed $height = 0.0, bool $keepTogether = false, string $vAlignment = DEF_FRAME_V_ALIGNMENT): Serialframe
    {
        $bf = $this->AddBox($width, $keepTogether);
        $bf->setHeight($height);
        $bf->setBackground($background);
        $bf->setVAlignment($vAlignment);
        return $bf->AddVContainer();
    }

    /**
     * Adds a text frame
     * @param string $text
     * @param TextStyle $textStyle
     * @param bool $useFullWidth
     * @param string $hAlignment
     * @return TextFrame
     */
    public function AddText(string $text, TextStyle $textStyle, bool $useFullWidth = false, string $hAlignment = DEF_FRAME_H_ALIGNMENT): TextFrame
    {
        $tf = new TextFrame($text, $textStyle);
        $this->addFrame($tf);
        $tf->setHAlignment($hAlignment);
        $tf->setUseFullWidth($useFullWidth);

        return $tf;
    }

    /**
     * Adds a box with a fixed width and a text frame in it
     * @param float|string $width
     * @param string $text
     * @param TextStyle $textStyle
     * @param string $hAlignment
     * @param float $extent
     * @param bool $keepTogether
     * @return TextFrame
     */
    public function AddTextInBox(mixed $width, string $text, TextStyle $textStyle, string $hAlignment = DEF_FRAME_H_ALIGNMENT, float $extent = 0.0, bool $keepTogether = false): TextFrame
    {
        // Create a box, default extent is no border
        $box = $this->AddBox($width, $keepTogether, $extent);
        $tf = $box->AddText($text, $textStyle, true, $hAlignment);
        if ($tf->getMinimumWidth() > $width) {
            $tf->setMinimumWidth($width);
        }
        return $tf;
    }

    /**
     * Adds a box with a background color with a fixed width and a text frame in it
     * @param string $background
     * @param float|string $width
     * @param string $text
     * @param TextStyle $textStyle
     * @return TextFrame
     */
    public function AddTextBlock(string $background, mixed $width, string $text, TextStyle $textStyle) : TextFrame
    {
        $bf = $this->AddBlock($background, $width);
        return $bf->AddText($text, $textStyle);
    }

    /**
     * Adds a simple page break
     * @param PageFormat $pageFormat
     * @return BreakFrame
     */
    public function AddPageBreak(PageFormat $pageFormat = new PageFormat()): BreakFrame
    {
        $bf = new BreakFrame($pageFormat);
        $this->addFrame($bf);

        return $bf;
    }

    /**
     * Adds an image
     * @param string $name
     * @param bool $keepAspect
     * @param float $width
     * @param float $height
     * @return ImageFrame
     */
    public function AddImage(string $name, bool $keepAspect, float $width, float $height): ImageFrame
    {
        $ifr = new ImageFrame($name);
        $this->addFrame($ifr);

        $ifr->setPreserveAspectRatio($keepAspect);

        if ($width > 0.0) {
            $ifr->setMaxWidth($width);
        }

        if ($height > 0.0) {
            $ifr->setMaxHeight($height);
        }

        return $ifr;
    }


    /**
     * Adds a barcode frame
     * @param string $barcodeText
     * @param string $barcodeType
     * @param float $width
     * @param float $height
     * @return BarcodeFrame
     */
    public function AddBarcode(string $barcodeText, string $barcodeType, float $width, float $height): BarcodeFrame
    {
        $bfr = new BarcodeFrame($barcodeText, $barcodeType, $width, $height);
        $this->addFrame($bfr);

        return $bfr;
    }

    /**
     * Adds an empty table
     * @return TableFrame
     */
    public function AddTable(): TableFrame
    {
        $tf = new TableFrame();
        $this->addFrame($tf);
        return $tf;
    }

    /**
     * Adds a box with a background color
     * @param string $background
     * @param float|string $width
     * @return BoxFrame
     */
    protected function addBlock(string $background, mixed $width) : BoxFrame
    {
        $bf = $this->AddBox($width);
        $bf->setBackground($background);

        return $bf;
    }

    /**
     * Adds a box with a fixed width
     * @param float|string $width
     * @param bool $keepTogether
     * @param float $extent
     * @return BoxFrame
     */
    public function AddBox(mixed $width = 0.0, bool $keepTogether = false, float $extent = DEF_LINE_EXTEND): BoxFrame
    {
        $bf = new BoxFrame($width);
        $this->addFrame($bf);
        $bf->setBorderPen(new Pen($extent));
        $bf->setKeepTogether($keepTogether);

        return $bf;
    }

    /**
     * Adds a PageFrame
     * @param int $onPageNr Definition on which page this frame will be printed
     * @param bool $useFullWidth Flag if the frame may use the full width
     */
    public function AddPageFrame(int $onPageNr = PageFrame::C_OnAllPages, bool $useFullWidth = true): PageFrame
    {
        $pf = new PageFrame($onPageNr, $useFullWidth);
        $this->addFrame($pf);
        return $pf;
    }

        /**
     * Resets the frame so the calculation can be started again
     * @param bool $keepTogether
     * @return void
     */
    public function resetSize(bool $keepTogether): void
    {
        parent::resetSize($keepTogether);

        if ($keepTogether) {
            for ($i = 0; $i < count($this->frames); $i++) {
                $this->frames[$i]->resetSize(true);
            }
        } else {
            if ($this->getCurrentFrame() != null) {
                $this->getCurrentFrame()->resetSize(false);
            }
        }
    }

    /**
     * Checks if the container has an endless loop in the recursion of frames
     * @param array $list List with all the frames seen until this position
     * @return bool
     */
    public function isEndless(array $list): bool
    {
        for ($i = 0; $i < count($this->frames); $i++) {
            if (in_array($this->frames[$i], $list, true)) {
                return true;
            }
            $list[] = $this->frames[$i];
            if ($this->frames[$i]->isEndless($list)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Resets the frame and all the frames in the container
     * @return void
     */
    public function reset(): void
    {
        parent::reset();
        for ($i = 0; $i < count($this->frames); $i++) {
            $this->frames[$i]->reset();
        }
    }

    /**
     * Adds a frame to the container
     * @param ReportFrame $frame
     * @return int
     */
    public function addFrame(ReportFrame $frame): int
    {
        $frame->setParent($this);
        $this->frames[] = $frame;
        return count($this->frames);
    }

    /**
     * Removes a frame from the container by its index
     * @param int $index
     * @return void
     */
    public function removeFrame(int $index): void
    {
        if ($index >= $this->getFrameCount()) {
            return;
        }
        unset($this->frames[$index]);
        $this->frames = array_values($this->frames);
    }

    /**
     * Returns a frame in the container by its index
     * @param int $index
     * @return ReportFrame|null
     */
    public function getFrame(int $index): ?ReportFrame
    {
        if ($index >= $this->getFrameCount()) {
            return null;
        }
        return $this->frames[$index];
    }

    /**
     * Returns the number of frames in the container
     * @return int
     */
    public function getFrameCount(): int
    {
        return count($this->frames);
    }

    /**
     * Clears all frames from the container
     * @return void
     */
    public function clearFrames(): void
    {
        unset($this->frames);
        $this->frames = array();
    }

    /**
     * Returns the current frame if possible
     * @return ReportFrame|null
     */
    protected function getCurrentFrame(): ?ReportFrame
    {
        if ($this->currentFrameIndex < count($this->frames)) {
            return $this->frames[$this->currentFrameIndex];
        } else {
            return null;
        }
    }

    /**
     * Will be called when the printing begins
     * @param Renderer $r
     * @return void
     */
    protected function doBeginPrint(Renderer $r) : void
    {
    }

}