<?php
/*
 * //============================================================+
 * // File name     : index.php
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

echo '<'.'?'.'xml version="1.0" encoding="UTF-8"'.'?'.'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
<title>ReportLib Examples</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="ReportLib is a PHP class for generating PDF reports" />
<meta name="author" content="Michael Hodel" />
<meta name="keywords" content="Examples, ReportLib, PDF, PHP class" />
</head>

<body style="max-width: 1280px; margin: auto;">
<h1>ReportLib Examples</h1>

<p>For more information see <a href="https://reportlib.adiuvaris.ch" title="ReportLib" target="_blank">ReportLib</a></p>

<h2>ReportLib basics</h2>
<p>
    <ol>
    <li><b>Simple empty report with paper format A4 in portrait mode with default margins, showing the printable area:</b> [<a href="example_001.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-1" title="PHP [new window]" target="_blank">Source</a>]
        <p>There is just an instance of a Report and all attributes get a default value. This means that the page format A4 in portrait mode. The margins are 20mm on the left side and 10mm on all other sides.<br>
        To show the printable area a BoxFrame is added to the report body. The usage of BoxFrames can be found in a later example.</p>
    </li>
    <li><b>Simple empty report with paper format Letter in landscape mode with a margin of one inch on top and half an inch on the other sides. A thin line around the printable area:</b>  [<a href="example_002.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-2" title="PHP [new window]" target="_blank">Source</a>]
        <p>An instance of a Report is created and the attributes are passed to the report. Therefore, a PageFormat instance is used to define the page format to 'Letter' in landscape mode.<br>
            The margins are one inch on the top side and half an inch on all other sides. ReportLib works always with the unit of millimeters.<br>
            To show the printable area a BoxFrame is added to the report body.</p>
    </li>
    <li><b>Report with default format and three paragraphs of text on it:</b>  [<a href="example_003.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-3" title="PHP [new window]" target="_blank">Source</a>]
        <p>Three paragraphs of left aligned text are added to the report body using the standard text style. The used function to add text uses the full width of the surrounding frame, which in this case is the printable area.<br>
            More about text styles can be found in later examples.</p>
    </li>
    <li><b>Report with text paragraphs showing different formatting possibilities:</b>  [<a href="example_004.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-4" title="PHP [new window]" target="_blank">Source</a>]
        <p>The first paragraph uses the standard left alignment. The second paragraph is right aligned and the third is justified.<br>
            The next short text is centered and the last one is centered as well but is printed in red. Colors have to be given as strings like in HTML '#RRGGBB' <br>
            To split the paragraphs a vertical distance is added to the report body after every text.</p>
    </li>
    <li><b>Using the predefined text styles:</b>  [<a href="example_005.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-5" title="PHP [new window]" target="_blank">Source</a>]
        <p>The default text style (NORMAL) has a size of 9 points, uses font family 'Helvetica', the font color is black and the background is white. It acts as the base for all other predefined text styles in the following list.<br>
        <ul>
            <li>BOLD</li>
            <li>ITALIC</li>
            <li>UNDERLINE</li>
            <li>SMALLNORMAL</li>
            <li>SMALLBOLD</li>
            <li>HEADING1</li>
            <li>HEADING2</li>
            <li>HEADING3</li>
            <li>HEADING4</li>
            <li>TABLE_HEADER</li>
            <li>TABLE_ROW</li>
            <li>TABLE_SUBTOTAL</li>
            <li>TABLE_TOTAL</li>
            <li>FOOTER</li>
            <li>HEADER</li>
        </ul>
        <p>This are only names, every of this text style can be used for any text in the report. Only the TABLE styles will be used by default for the respective row type.
        </p>
    </li>
    <li><b>Adjust the predefined text styles to use other font families or sizes and colors:</b>  [<a href="example_006.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-6" title="PHP [new window]" target="_blank">Source</a>]
        <p>Here the NORMAL text style has been set to font family 'Times' and the base size is now 11 points. Because the other text styles base on the NORMAL style, they use these settings as well.<br>
            But it is also possible to adjust only some text styles to use other settings. In this example the table styles use now 'Courier' font family instead of 'Times'. The FOOTER style gets a grey text color and the HEADER style will be printed in green.</p>
    </li>
    <li><b>Create new named text styles with any desired settings:</b>  [<a href="example_007.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-7" title="PHP [new window]" target="_blank">Source</a>]
        <p>It is possible to define new text styles, and they can be put into the global list of text styles. They can be accessed by their names.<br>
            The new text styles can use any other text style as base and inherit some settings and change others. The example shows three self defined text styles.</p>
    </li>
    <li><b>Adding horizontal lines with convenience functions and manually with LineFrames and Pens: </b> [<a href="example_008.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-8" title="PHP [new window]" target="_blank">Source</a>]
        <p>The first line is a solid line with default color black and a default extent of 0.1mm<br>
            Then follows a solid line with an extent of 1mm<br>
            Next is a centered solid line with a length of 100mm and an extent of 0.5mm. The color of the line has been set to green.<br>
            Then a red dashed line with a length of 120mm, left aligned.<br>
            Then a blue dotted line with a length of 50mm, right aligned.<br>
            At last there is a grey dash-dotted line.
        </p>
    </li>
    <li><b>Automatic and manual page breaks and changes of the page formats and margins:</b>  [<a href="example_009.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-9" title="PHP [new window]" target="_blank">Source</a>]
        <p>The example prints a paragraph of text 20 times each followed by a 2mm distance before the next paragraph. A page break is needed at about paragraph number 13 and the text of this paragraph will be split to the next page.<br>
            After the 20 text paragraphs a manual page break is inserted and then a paragraph of text. The format of the page has not been changed, so it is still A4 in portrait mode.<br>
            After the last text a manually added page break has been inserted. The orientation has been changed to landscape and the left and right margins are set to 30mm, the top margin is set to 20mm. This settings will be used from this page on until one other format is set again.<br>
            With the current format the example prints a paragraph of text 10 times, so that a page break is needed. The text of paragraph number 10 will be split to the next page. The next page uses the same settings.<br>
            Then a manual page break is added. The format is now A5 in landscape mode with default margins. To show that a text paragraph is added.<br>
            Now the format is set to A5 in portrait mode with another manually added page break. The left and right margins will be mirrored. To show that effect, a text will be printed 10 times to force an automatic page break.
        </p>
    </li>
    <li><b>Adding images to a report:</b>  [<a href="example_010.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-10" title="PHP [new window]" target="_blank">Source</a>]
        <p>The example prints an image into a rectangle with a width of 100mm and a height of 100mm, but keep the aspect ratio of the picture - therefore the frame height will be adjusted. The original size is 1920x1208 pixels.<br>
            Next it prints an image into a rectangle with a width of 100mm and a height of 30mm, but keep the aspect ratio of the picture - therefore the frame width will be adjusted.<br>
            Last it prints an image into a rectangle with a width of 20mm and a height of 40mm, it does not keep the aspect ratio of the picture.
        </p>
    </li>
    <li><b>Adding barcodes to a report: </b> [<a href="example_011.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-11" title="PHP [new window]" target="_blank">Source</a>]
        <p>This example prints first a QR code into a rectangle with a width of 50mm and a height of 50mm. QR codes are squares, so the space will be filled with the QR code.<br>
            Then it prints a QR code into a rectangle with a width of 50mm and a height of 80mm. The code will be centered in the given rectangle. Above and below of the QR code there is some white space.<br>
            Next follows a QR code in a rectangle with a width of 100mm and a height of 40mm. The code will be centered in the given rectangle. Left and right of the QR code there is some white space.<br>
            And then a PDF417 code is printed into a rectangle with a width of 50mm and a height of 50mm. PDF417 codes are rectangles, because of that there is white space above and below the code.<br>
            Last it prints a DATAMATRIX code into a rectangle with a width of 50mm and a height of 50mm. DATAMATRIX codes are squares, so the space will be filled with the code image.
        </p>
    </li>
    <li><b>Add a simple table to a report:</b>  [<a href="example_012.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-12" title="PHP [new window]" target="_blank">Source</a>]
        <p>This example adds a simple table to the report body. The table has four columns with different alignments. The widths of the columns are fix and if the text needs more space it will be wrapped.<br>
            The first row just below the header shows the widths of the columns. The header row will be generated by the library based on the title texts for the columns (but it could be suppressed)</p>
    </li>
    <li><b>Add a table to a report using the full width:</b>  [<a href="example_013.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-13" title="PHP [new window]" target="_blank">Source</a>]
        <p>In this example the simple table from the last example is used, but the table uses the full width of the surrounding frame, which in this case is the printable area.<br>
            The first row just below the header shows the original widths of the columns. The columns will be enlarged relative to their defined size.</p>
    </li>
    <li><b>A table using percent values for the widths of the columns:</b>  [<a href="example_014.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-14" title="PHP [new window]" target="_blank">Source</a>]
        <p>Here the widths of the columns in the table are defined in percent of the full width of the frame.<br>
            To do that the values for the width has to be passed as string (the percent sign is not necessary, but it makes the code more readable).<br>
            The first row just below the header shows the original widths of the columns.<br>
            If the sum of all columns is less the 100 percent (80% in the example), it will use only that part of the frame width.<br>
            If it is more than 100 percent 'line breaks' will be added (see example about 'line breaks' in tables)</p>
    </li>
    <li><b>A table using more space than available - line break: </b> [<a href="example_015.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-15" title="PHP [new window]" target="_blank">Source</a>]
        <p>In this example the table has five columns, whereas the fourth column would make the table wider than the available width.<br>
            Therefore a line break will be automatically added to the third column. The columns four and five are printed on a second row. To show that an inter-row-space of 1.5mm has been added.<br>
            In the example the fourth columns is just a dummy column to show some more structure in the table.<br>
            It would be also possible to force a line break after a column programmatically.
        </p>
    </li>
    <li><b>Use row types for a table:</b>  [<a href="example_016.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-16" title="PHP [new window]" target="_blank">Source</a>]
        <p>In this example there were a subtotal row and a total row added to the table. The row with the column widths is defined as subtotal row and the last row is of type total row.<br>
            The row type only defines the text style that will be used to print the row.</p>
    </li>
    <li><b>Add some border and some grid lines to a table:</b>  [<a href="example_017.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-17" title="PHP [new window]" target="_blank">Source</a>]
        <p>It is possible to define the border and table lines individually. Here the top and bottom line of the border are defined as red lines with different extents.<br>
            The horizontal lines under the header line and above the total line are as defined per default. The vertical lines after the first three columns are defined as thin grey lines.</p>
    </li>
    <li><b>Use text styles in tables: </b> [<a href="example_018.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-18" title="PHP [new window]" target="_blank">Source</a>]
        <p>It is possible to define different text styles for the different row types in a table. Any attribute of the text styles can be changed as needed.<br>
            Here the subtotal lines use font family 'Courier' and the total lines use 'Times'. The detail rows have a blue font color and the header row uses a font size of 11 points.</p>
    </li>
    <li><b>Tables that spread over more than one page:</b>  [<a href="example_019.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-19" title="PHP [new window]" target="_blank">Source</a>]
        <p>A table that has more lines that fit on one page will insert automatic page breaks (regarding the margins).<br>
            The header line will be repeated on every page. It is possible to suppress the repetition of the header.
            This example also shows the effect of an inter-row-space of 1mm</p>
    </li>
    <li><b>How to use BoxFrames:</b>  [<a href="example_020.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-20" title="PHP [new window]" target="_blank">Source</a>]
        <p>A BoxFrame is just a rectangular space in the report. It can be filled with any other frame type (e.g. text).<br>
            A box with no content wouldn't be visible in the report, because it has no border by default. To make them visible in this example all boxes have a border.<br>
            The first example box has a width of 70mm and a height of 50mm and a thin red border.<br>
            The next example box has a width of 150mm and a height of 20mm and a thin red border. But the pens on the left and right side have been set to 5mm.<br>
            The last example box uses the full width of the frame and has a height of 10mm. It has a special border with different colors and line extents.
        </p>
    </li>
    <li><b>Use BoxFrames to place and format texts: </b> [<a href="example_021.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-21" title="PHP [new window]" target="_blank">Source</a>]
        <p>The first element has a left aligned text in a box with a width of 50mm.<br>
            Next the same text is in a box with a width of 120mm and a bold text style.<br>
            The third box is centered text in a box with a width of 40mm.<br>
            Last there is some justified text in a box with a width of 140mm and a thin border around it. The box has a left margin of 20mm and the text has a padding of 1mm to get some distance from the border.
        </p>
    </li>
    <li><b>BoxFrames in SerialFrames: </b> [<a href="example_022.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-22" title="PHP [new window]" target="_blank">Source</a>]
        <p>The 'body' that has been used in all examples is in fact a vertical SerialFrame. Each frame added to the body will be printed just below the preceding one (in the flow of the document).<br>
            First there is a horizontal SerialFrame added to the body with a left aligned text in a box with a width of 50mm. Right to the first frame follows a bold text in box of width 120m. The texts are close because there is no padding.<br>
            Then a second horizontal SerialFrame is added to the body and will be printed therefore below the first one. Three columns of justified text in separate boxes, each with a width of 55mm. The columns have a distance of 4mm.<br>
            A SerialFrame can also hold other frame types which is shown in the next section. On the left side there is a barcode followed by a distance of 5mm. Then follows an image and on the right side there is a 50mm width box with text.<br>
            BoxFrames and SerialFrames can also be used to create aligned labels and texts. The labels have a fixed width of 25mm and are right aligned. The texts will use the rest of the width of the surrounding frame and are left aligned.
        </p>
    </li>
    <li><b>Use FixposFrames to place content on an absolute position on the report: </b> [<a href="example_023.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-23" title="PHP [new window]" target="_blank">Source</a>]
        <p>In this example a frame is added at a position of 120mm from the left and 50mm from the top of the paper. The box has a size of 50mm by 70mm. To show the box it has a red border.<br>
        Below the box there is a text paragraph.<br>
         Then a FixposFrame is added at the position 60mm from the left and 130mm from the top of the paper. The box has a size of 100mm by 50mm.<br>
            The frame cannot be placed on the first (the current) page because there is already a text frame. Therefore, a page break is inserted automatically and the frame will be printed on the second page.
        </p>
    </li>
    <li><b>Header and footer: </b> [<a href="example_024.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-24" title="PHP [new window]" target="_blank">Source</a>]
        <p>A header and footer can be defined. Their height will reduce the printable height of the body.<br>
            The header here in the example contains a small image on the left side and some text on the right side. It has a grey background and a green line at the bottom of it.<br>
            The footer has a black line at the top and below that the page number and the total number of pages are printed.<br>
            To print page numbers there are two variables that can be inserted in texts: [VAR_PAGE] and [VAR_TOTAL_PAGES].<br>
            If the total number of pages is needed the report needs to be instructed to count the pages - setCountPages(true).<br>
        A manual page break is added to show the repetition of the header and footer.</p>
    </li>
    <li><b>Different headers and footers via PageFrames:</b>  [<a href="example_025.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-25" title="PHP [new window]" target="_blank">Source</a>]
        <p>PageFrames can be used to define that a header or footer should be printed only on certain pages.<br>
            Just add multiple PageFrames with different contents to the header or footer container of the report.<br>
            In this example there is header for all pages but not the first page and there are two footers on for odd and one for even pages.<br>
        A few manual page breaks has been added to show the effects.</p>
    </li>
    <li><b>BoxFrames using percent values as width:</b>  [<a href="example_026.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-26" title="PHP [new window]" target="_blank">Source</a>]
        <p>The width of BoxFrames can be defined in percent of the surrounding frame.<br>
            This example looks like example 22 but the widths of the BoxFrames are defined in percent.<br>
            The first two frames use 60% and 40% of the width. Then there are three columns with a third of the space. The QR code uses 25%, the image 40% and the text 45% (but limited by the width of the page).<br>
            Frames added to a BoxFrame can only use the space of the box, therefore uses the QR-code only the given space and not the defined 100mm.<br>
            To show the effect the same report structure is printed on three pages with different page formats and margins.<br>
            Nothing has to be changed on the structure, and it is printed correctly on every page.</p>
    </li>
    <li><b>Table rows with joined columns:</b>  [<a href="example_027.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-27" title="PHP [new window]" target="_blank">Source</a>]
        <p>It is possible to join multiple columns in a row to be printed as one.<br>
            That can be used to create subtitles that do need more space than is available in a column.<br>
            In this example the second data row joins all columns to print a subtitle.
            </p>
    </li>
    <li><b>Exceptions:</b>  [<a href="example_028.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-28" title="PHP [new window]" target="_blank">Source</a>]
        <p>There are situations where the library can't create a report. In these cases an exception is thrown.<br>
            One problem is an endless loop because of a circle dependency in the report structure.<br>
            A problem is also if a frame should be kept together but the size of the complete frame is bigger than the space on one page.<br>
            Another problem is when there is no space left in frame to put another frame into it. In the example the first text uses the whole width so the second text does not get any space.<br>
            If a FixposFrame uses offset values which are outside the printable area and the frame may not overlay other frames an exception is thrown.<br>
            If the file which name is passed to an ImageFrame does not exist or is not a valid image file an exception is thrown.
        </p>
    </li>
    <li><b>Example report</b>  [<a href="example_029.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-29" title="PHP [new window]" target="_blank">Source</a>]
        <p>A sample report with a header and a footer.<br>
            It contains a list of the example files in a table with some information about the file<br>
            On the following pages the content of the first three example files will be listed.
        </p>
    </li>
    <li><b>More complex report</b>  [<a href="example_030.php" title="PDF [new window]" target="_blank">PDF</a>] [<a href="https://reportlib.adiuvaris.ch/example-30" title="PHP [new window]" target="_blank">Source</a>]
        <p>A more complex sample report.<br>
            Please notice that this is a dummy invoice as it would be used in Switzerland. Therefore, it contains German texts.<br>
            The report contains a lot of hard coded text, normally this information would come from a database.
        </p>
    </li>
</ol>
</body>
</html>
