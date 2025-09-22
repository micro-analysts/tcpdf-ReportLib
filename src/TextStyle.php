<?php
/*
 * //============================================================+
 * // File name     : TextStyle.php
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

use JsonSerializable;

include_once __DIR__ . "/../config/config.php";

/**
 * @class TextStyle
 * Class that represents the style of a portion of text.
 * A text style contains a font name, a font size, a color and attributes like bold, italic or underline
 * @brief Class representing a text style that can be used in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class TextStyle implements JsonSerializable
{
    /**
     * Base text style
     * @var TextStyle|null
     */
    protected ?TextStyle $defaultStyle = null;

    /**
     * Name of the text style
     * @var string
     */
    protected string $name;

    /**
     * Bold flag
     * @var bool
     */
    protected bool $bold;

    /**
     * Flag if the local bold flag or the bold flag of the base text style has to be used
     * @var bool
     */
    protected bool $boldSet = false;

    /**
     * Italic flag
     * @var bool
     */
    protected bool $italic;

    /**
     * Flag if the local italic flag or the italic flag of the base text style has to be used
     * @var bool
     */
    protected bool $italicSet = false;

    /**
     * Underline flag
     * @var bool
     */
    protected bool $underline;

    /**
     * Flag if the local underline flag or the underline flag of the base text style has to be used
     * @var bool
     */
    protected bool $underlineSet = false;

    /**
     * Font size in pixel
     * @var float
     */
    protected float $size;

    /**
     * Flag if the local size or the size of the base text style has to be used
     * @var bool
     */
    protected bool $sizeSet = false;

    /**
     * Delta size in pixel in respect to the size in the base text style
     * Can be positive oder negative
     * @var float
     */
    protected float $sizeDelta;

    /**
     * Font family
     * Possible core font names 'courier', 'helvetica', 'times', 'symbol', 'zapfdingbats'
     * @var string
     */
    protected string $fontFamily;

    /**
     * Flag if the local font family or the font family of the base text style has to be used
     * @var bool
     */
    protected bool $fontFamilySet = false;

    /**
     * Text color
     * @var string
     */
    protected string $textColor;

    /**
     * Flag if the local text color or the text color of the base text style has to be used
     * @var bool
     */
    protected bool $textColorSet = false;

    /**
     * Background color
     * @var string
     */
    protected string $backgroundColor;

    /**
     * Flag if the local background color or the background color of the base text style has to be used
     * @var bool
     */
    protected bool $backgroundColorSet = false;

    /**
     * Class constructor
     * @param string $name Name for the text style
     * @param TextStyle|null $defaultStyle Base text style
     */
    public function __construct(string $name, ?TextStyle $defaultStyle = null)
    {
        $this->name = $name;
        $this->defaultStyle = $defaultStyle;

        $this->bold = false;
        $this->italic = false;
        $this->underline = false;
        $this->sizeDelta = 0.0;

        $this->size = DEF_TEXT_FONT_SIZE;
        $this->fontFamily = DEF_TEXT_FONT_FAMILY;
        $this->textColor = DEF_TEXT_COLOR;
        $this->backgroundColor = DEF_TEXT_BACKGROUND_COLOR;

        $this->resetToDefault();
    }

    /**
     * Resets the style to its defaults
     * @return void
     */
    public function resetToDefault(): void
    {
        if ($this->defaultStyle != null) {
            $this->boldSet = false;
            $this->italicSet = false;
            $this->underlineSet = false;
            $this->sizeSet = false;
            $this->sizeDelta = 0;
            $this->fontFamilySet = false;
            $this->textColorSet = false;
            $this->backgroundColorSet = false;
        }
    }

    public function clone(string $name): self
    {
        return (clone $this)
            ->setName($name);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'bold' => $this->isBold(),
            'boldSet' => $this->boldSet,
            'italic' => $this->isItalic(),
            'italicSet' => $this->italicSet,
            'underline' => $this->isUnderline(),
            'underlineSet' => $this->underlineSet,
            'size' => $this->getSize(),
            'sizeSet' => $this->sizeSet,
            'fontFamily' => $this->getFontFamily(),
            'fontFamilySet' => $this->fontFamilySet,
            'textColor' => $this->getTextColor(),
            'textColorSet' => $this->textColorSet,
            'backgroundColor' => $this->getBackgroundColor(),
            'backgroundColorSet' => $this->backgroundColorSet,
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBold(): bool
    {
        if ($this->boldSet) {
            return $this->bold;
        }
        return $this->defaultStyle != null && $this->defaultStyle->isBold();
    }

    /**
     * @param bool $bold
     * @return self
     */
    public function setBold(bool $bold): self
    {
        $this->boldSet = true;
        $this->bold = $bold;
        return $this;
    }

    /**
     * @return bool
     */
    public function isItalic(): bool
    {
        if ($this->italicSet) {
            return $this->italic;
        }
        return $this->defaultStyle != null && $this->defaultStyle->isItalic();
    }

    /**
     * @param bool $italic
     * @return self
     */
    public function setItalic(bool $italic): self
    {
        $this->italicSet = true;
        $this->italic = $italic;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUnderline(): bool
    {
        if ($this->underlineSet) {
            return $this->underline;
        }
        return $this->defaultStyle != null && $this->defaultStyle->isUnderline();
    }

    /**
     * @param bool $underline
     * @return self
     */
    public function setUnderline(bool $underline): self
    {
        $this->underlineSet = true;
        $this->underline = $underline;
        return $this;
    }

    /**
     * @return float
     */
    public function getSize(): float
    {
        if ($this->sizeSet) {
            return $this->size;
        } else {
            return $this->defaultStyle != null
                ? $this->defaultStyle->getSize() + $this->sizeDelta
                : DEF_TEXT_FONT_SIZE + $this->sizeDelta;
        }
    }

    /**
     * @param float $size
     * @return self
     */
    public function setSize(float $size): self
    {
        $this->sizeSet = true;
        $this->size = $size;
        return $this;
    }

    /**
     * @return float
     */
    public function getSizeDelta(): float
    {
        return $this->sizeDelta;
    }

    /**
     * @param float $sizeDelta
     * @return self
     */
    public function setSizeDelta(float $sizeDelta): self
    {
        $this->sizeDelta = $sizeDelta;
        return $this;
    }

    /**
     * @return string
     */
    public function getFontFamily(): string
    {
        if ($this->fontFamilySet) {
            return $this->fontFamily;
        } else {
            return $this->defaultStyle != null
                ? $this->defaultStyle->getFontFamily()
                : DEF_TEXT_FONT_FAMILY;
        }
    }

    /**
     * @param string $fontFamily
     * @return self
     */
    public function setFontFamily(string $fontFamily): self
    {
        $this->fontFamilySet = true;
        $this->fontFamily = $fontFamily;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextColor(): string
    {
        if ($this->textColorSet) {
            return $this->textColor;
        } else {
            return $this->defaultStyle != null
                ? $this->defaultStyle->getTextColor()
                : "#000000";
        }
    }

    /**
     * @param string $textColor
     * @return self
     */
    public function setTextColor(string $textColor): self
    {
        $this->textColorSet = true;
        $this->textColor = $textColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor(): string
    {
        if ($this->backgroundColorSet) {
            return $this->backgroundColor;
        } else {
            return $this->defaultStyle != null
                ? $this->defaultStyle->getBackgroundColor()
                : "#FFFFFF";
        }
    }

    /**
     * @param string $backgroundColor
     * @return self
     */
    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->backgroundColorSet = true;
        $this->backgroundColor = $backgroundColor;
        return $this;
    }
}
