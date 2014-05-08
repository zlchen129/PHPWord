<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Writer\Word2007\Style\Image as ImageStyleWriter;

/**
 * Image element writer
 *
 * @since 0.10.0
 */
class Image extends AbstractElement
{
    /**
     * Write element
     */
    public function write()
    {
        $element = $this->getElement();

        if ($element->isWatermark()) {
            $this->writeWatermark();
        } else {
            $this->writeImage();
        }
    }

    /**
     * Write image element
     */
    private function writeImage()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();

        $rId = $element->getRelationId() + ($element->isInSection() ? 6 : 0);
        $style = $element->getStyle();
        $styleWriter = new ImageStyleWriter($xmlWriter, $style);

        if (!$this->withoutP) {
            $xmlWriter->startElement('w:p');
            if (!is_null($style->getAlign())) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:jc');
                $xmlWriter->writeAttribute('w:val', $style->getAlign());
                $xmlWriter->endElement(); // w:jc
                $xmlWriter->endElement(); // w:pPr
            }
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:pict');
        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');
        $styleWriter->write();
        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement(); // v:imagedata
        $styleWriter->writeW10Wrap();
        $xmlWriter->endElement(); // v:shape
        $xmlWriter->endElement(); // w:pict
        $xmlWriter->endElement(); // w:r

        if (!$this->withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }
    /**
     * Write watermark element
     */
    private function writeWatermark()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();

        $rId = $element->getRelationId();
        $style = $element->getStyle();
        $style->setPositioning('absolute');
        $styleWriter = new ImageStyleWriter($xmlWriter, $style);

        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:pict');
        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');
        $styleWriter->write();
        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement(); // v:imagedata
        $xmlWriter->endElement(); // v:shape
        $xmlWriter->endElement(); // w:pict
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
    }
}
