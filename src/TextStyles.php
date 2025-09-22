<?php
/*
 * //============================================================+
 * // File name     : TextStyles.php
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

include_once "TextStyle.php";

/**
 * @class TextStyles
 * Class with an array of text styles that can be used in a report
 * There is a list of default text styles which bases on a normal text style.
 * So if the normal text style will be changed all text style that bases on
 * the normal text style will be changed as well. So it is possible to
 * change e.g. the font family of the normal font and all other fonts
 * which bases on the normal font will use that font family also.
 * The text style can be accessed by their name.
 * @brief Class with a list of styles that can be used in a report
 * @author Michael Hodel - info@adiuvaris.ch
 */
class TextStyles
{
    /**
     * Names for the predefined text styles
     */
    const NORMAL = "Normal";
    const SMALLNORMAL = "SmallNormal";
    const HEADING1 = "Heading1";
    const HEADING2 = "Heading2";
    const HEADING3 = "Heading3";
    const HEADING4 = "Heading4";
    const BOLD = "Bold";
    const SMALLBOLD = "SmallBold";
    const ITALIC = "Italic";
    const UNDERLINE = "Underline";
    const FOOTER = "Footer";
    const HEADER = "Header";
    const TABLE_HEADER = "TableHeader";
    const TABLE_ROW = "TableRow";
    const TABLE_SUBTOTAL = "TableSubTotal";
    const TABLE_TOTAL = "TableTotal";

    /**
     * Array with the defined text styles
     * @var array
     */
    protected static array $styles;

    /**
     * Adds a text style with the given name in the array of text styles,
     * so it can be used at any place in the report.
     * If the name is already in the array the function returns this text style
     * @param string $name Name for the text style
     * @param TextStyle $baseStyle Base text style
     * @return TextStyle The newly added text style (or the one already in the list)
     */
    public static function addTextStyle(string $name, TextStyle $baseStyle): TextStyle
    {
        if (!array_key_exists($name, self::$styles)) {
            $ts = new TextStyle($name, $baseStyle);
            self::$styles[$name] = $ts;
        } else {
            $ts = self::$styles[$name];
        }
        return $ts;
    }

    /**
     * Returns a text style by a name if it exists in the array
     * If the text style does not exist it will be added to the list
     * with the normal text style as base style
     * (i.e. it will look like the normal text style)
     * There is a list of predefined text styles and there
     * constants with the names for them.
     * * <ul>
     *   <li>Normal</li>
     *   <li>Header</li>
     *   <li>Footer</li>
     *   <li>Heading1</li>
     *   <li>Heading2</li>
     *   <li>Bold</li>
     *   <li>Italic</li>
     *   <li>Underline</li>
     *   <li>etc.</li>
     * </ul>
     * @param string $name
     * @return TextStyle
     */
    public static function getTextStyle(string $name): TextStyle
    {
        if (!isset(self::$styles)) {
            self::resetStyles();
        }

        if (!array_key_exists($name, self::$styles)) {
            $ts = self::addTextStyle($name, self::$styles[TextStyles::NORMAL]);
        } else {
            $ts = self::$styles[$name];
        }
        return $ts;
    }

    /**
     * Resets the default text styles to their initial settings
     * @return void
     */
    public static function resetStyles(): void
    {
        self::$styles = array();

        // Fill the array with the predefined text styles
        self::$styles[TextStyles::NORMAL] = new TextStyle(TextStyles::NORMAL, null);
        self::$styles[TextStyles::SMALLNORMAL] = new TextStyle(TextStyles::SMALLNORMAL, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::HEADING1] = new TextStyle(TextStyles::HEADING1, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::HEADING2] = new TextStyle(TextStyles::HEADING2, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::HEADING3] = new TextStyle(TextStyles::HEADING3, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::HEADING4] = new TextStyle(TextStyles::HEADING4, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::BOLD] = new TextStyle(TextStyles::BOLD, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::SMALLBOLD] = new TextStyle(TextStyles::SMALLBOLD, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::UNDERLINE] = new TextStyle(TextStyles::UNDERLINE, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::ITALIC] = new TextStyle(TextStyles::ITALIC, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::FOOTER] = new TextStyle(TextStyles::FOOTER, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::HEADER] = new TextStyle(TextStyles::HEADER, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::TABLE_HEADER] = new TextStyle(TextStyles::TABLE_HEADER, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::TABLE_ROW] = new TextStyle(TextStyles::TABLE_ROW, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::TABLE_SUBTOTAL] = new TextStyle(TextStyles::TABLE_SUBTOTAL, self::$styles[TextStyles::NORMAL]);
        self::$styles[TextStyles::TABLE_TOTAL] = new TextStyle(TextStyles::TABLE_TOTAL, self::$styles[TextStyles::NORMAL]);

        // Normal text style with default size (9 pixel), black text and white background, not bold, italic or underline
        self::$styles[TextStyles::NORMAL]->setFontFamily(DEF_TEXT_FONT_FAMILY);
        self::$styles[TextStyles::NORMAL]->setSize(DEF_TEXT_FONT_SIZE);
        self::$styles[TextStyles::NORMAL]->setBold(false);
        self::$styles[TextStyles::NORMAL]->setItalic(false);
        self::$styles[TextStyles::NORMAL]->setUnderline(false);
        self::$styles[TextStyles::NORMAL]->setTextColor("#000000");
        self::$styles[TextStyles::NORMAL]->setBackgroundColor("#FFFFFF");

        // Heading 1 - normal style but bold and 9 pixels taller
        self::$styles[TextStyles::HEADING1]->resetToDefault();
        self::$styles[TextStyles::HEADING1]->setBold(true);
        self::$styles[TextStyles::HEADING1]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_HEADING1);

        // Heading 2 - normal style but bold and 6 pixels taller
        self::$styles[TextStyles::HEADING2]->resetToDefault();
        self::$styles[TextStyles::HEADING2]->setBold(true);
        self::$styles[TextStyles::HEADING2]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_HEADING2);

        // Heading 3 - normal style but bold and italic and 3 pixels taller
        self::$styles[TextStyles::HEADING3]->resetToDefault();
        self::$styles[TextStyles::HEADING3]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_HEADING3);
        self::$styles[TextStyles::HEADING3]->setBold(true);
        self::$styles[TextStyles::HEADING3]->setItalic(true);

        // Heading 4 - normal style but bold and italic and 1 pixel taller
        self::$styles[TextStyles::HEADING4]->resetToDefault();
        self::$styles[TextStyles::HEADING4]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_HEADING4);
        self::$styles[TextStyles::HEADING4]->setBold(true);
        self::$styles[TextStyles::HEADING4]->setItalic(true);

        // Bold - normal style but bold
        self::$styles[TextStyles::BOLD]->resetToDefault();
        self::$styles[TextStyles::BOLD]->setBold(true);

        // Underline - normal style but underlined
        self::$styles[TextStyles::UNDERLINE]->resetToDefault();
        self::$styles[TextStyles::UNDERLINE]->setUnderline(true);

        // Italic - normal style but italic
        self::$styles[TextStyles::ITALIC]->resetToDefault();
        self::$styles[TextStyles::ITALIC]->setItalic(true);

        // Small normal - normal style but a pixel smaller
        self::$styles[TextStyles::SMALLNORMAL]->resetToDefault();
        self::$styles[TextStyles::SMALLNORMAL]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_SMALL);

        // Small bold - normal style but bold and a pixel smaller
        self::$styles[TextStyles::SMALLBOLD]->resetToDefault();
        self::$styles[TextStyles::SMALLBOLD]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_SMALL);
        self::$styles[TextStyles::SMALLBOLD]->setBold(true);

        // Table header - normal style but bold and a pixel smaller
        self::$styles[TextStyles::TABLE_HEADER]->resetToDefault();
        self::$styles[TextStyles::TABLE_HEADER]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_TABLE);
        self::$styles[TextStyles::TABLE_HEADER]->setBold(true);

        // Table row - normal style but a pixel smaller
        self::$styles[TextStyles::TABLE_ROW]->resetToDefault();
        self::$styles[TextStyles::TABLE_ROW]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_TABLE);

        // Table subtotal - normal style but italic and a pixel smaller
        self::$styles[TextStyles::TABLE_SUBTOTAL]->resetToDefault();
        self::$styles[TextStyles::TABLE_SUBTOTAL]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_TABLE);
        self::$styles[TextStyles::TABLE_SUBTOTAL]->setItalic(true);

        // Table total - normal style but bold and a pixel smaller
        self::$styles[TextStyles::TABLE_TOTAL]->resetToDefault();
        self::$styles[TextStyles::TABLE_TOTAL]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_TABLE);
        self::$styles[TextStyles::TABLE_TOTAL]->setBold(true);

        // Footer - normal style but a pixel smaller
        self::$styles[TextStyles::FOOTER]->resetToDefault();
        self::$styles[TextStyles::FOOTER]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_TABLE);

        // Header - normal style but a pixel smaller
        self::$styles[TextStyles::HEADER]->resetToDefault();
        self::$styles[TextStyles::HEADER]->setSizeDelta(DEF_TEXT_FONT_SIZE_DELTA_TABLE);
    }
}
