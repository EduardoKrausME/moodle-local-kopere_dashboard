<?php
// This file is part of the local_kopere_dashboard plugin for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_kopere_dashboard
 * @copyright   2024 Eduardo Kraus https://eduardokraus.com/
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @date        10/01/2024 14:19
 */

namespace local_kopere_dashboard\fonts;

use Exception;

class font_attributes {

    /**
     * Name of the truetype font file
     *
     * @access private
     * @var string
     */
    private $_filename = null;

    /**
     * Copyright
     *
     * @access private
     * @var string
     */
    private $_copyright = null;

    /**
     * Font Family
     *
     * @access private
     * @var string
     */
    private $_fontfamily = null;

    /**
     * Font SubFamily
     *
     * @access private
     * @var string
     */
    private $_fontsubfamily = null;

    /**
     * Font Unique Identifier
     *
     * @access private
     * @var string
     */
    private $_fontidentifier = null;

    /**
     * Font Name
     *
     * @access private
     * @var string
     */
    private $_fontname = null;

    /**
     * Font Version
     *
     * @access private
     * @var string
     */
    private $_fontversion = null;

    /**
     * Postscript Name
     *
     * @access private
     * @var string
     */
    private $_postscriptname = null;

    /**
     * Trademark
     *
     * @access private
     * @var string
     */
    private $_trademark = null;

    private function return_value($instring) {
        if (ord($instring) == 0) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($instring, "UTF-8", "UTF-16");
            } else {
                return str_replace(chr(00), '', $instring);
            }
        } else {
            return $instring;
        }
    }

    /**
     * @access public
     * @return integer
     */
    public function get_copyright() {
        return $this->return_value($this->_copyright);
    }

    /**
     * @access public
     * @return integer
     */
    public function get_font_family() {
        return $this->return_value($this->_fontfamily);
    }

    /**
     * @access public
     * @return integer
     */
    public function get_font_family_id() {
        $font = $this->get_font_family();
        $font = str_replace(" ", "-", $font);
        $font = strtolower($font);

        return $font;
    }

    /**
     * @access public
     * @return integer
     */
    public function get_font_sub_family() {
        return $this->return_value($this->_fontsubfamily);
    }

    /**
     * @access public
     * @return integer
     */
    public function get_font_identifier() {
        return $this->return_value($this->_fontidentifier);
    }

    /**
     * @access public
     * @return integer
     */
    public function get_font_name() {
        return $this->return_value($this->_fontname);
    }

    public function get_font_name_id() {
        $font = $this->get_font_name();
        $font = str_replace(" ", "-", $font);
        $font = strtolower($font);

        return $font;
    }

    /**
     * @access public
     * @return integer
     */
    public function get_font_version() {
        return $this->return_value($this->_fontversion);
    }

    /**
     * @access public
     * @return integer
     */
    public function get_postscript_name() {
        return $this->return_value($this->_postscriptname);
    }

    /**
     * @access public
     * @return integer
     */
    public function get_trademark() {
        return $this->return_value($this->_trademark);
    }

    /**
     * Convert a big-endian word or longword value to an integer
     *
     * @access private
     * @return integer
     */
    private function u_convert($bytesvalue, $bytecount) {
        $retval = 0;
        $byteslength = strlen($bytesvalue);
        for ($i = 0; $i < $byteslength; $i++) {
            $tmpval = ord($bytesvalue[$i]);
            $t = pow(256, ($bytecount - $i - 1));
            $retval += $tmpval * $t;
        }

        return $retval;
    }

    /**
     * Convert a big-endian word value to an integer
     *
     * @access private
     * @return integer
     */
    private function u_short($stringvalue) {
        return $this->u_convert($stringvalue, 2);
    }

    /**
     * Convert a big-endian word value to an integer
     *
     * @access private
     * @return integer
     */
    private function u_long($stringvalue) {
        return $this->u_convert($stringvalue, 4);
    }

    /**
     * Read the Font Attributes
     *
     * @return bool
     *
     * @throws Exception
     */
    private function read_font_attributes() {
        $fonthandle = fopen($this->_filename, "rb");

        // Read the file header.
        $ttoffsettable = fread($fonthandle, 12);

        $umajorversion = $this->u_short(substr($ttoffsettable, 0, 2));
        $uminorversion = $this->u_short(substr($ttoffsettable, 2, 2));
        $unumoftables = $this->u_short(substr($ttoffsettable, 4, 2));

        // Check is this is a true type font and the version is 1.0.
        if ($umajorversion != 1 || $uminorversion != 0) {
            fclose($fonthandle);
            throw new Exception($this->_filename . ' is not a Truetype font file');
        }

        // Look for details of the name table.
        $nametablefound = false;
        for ($t = 0; $t < $unumoftables; $t++) {
            $tttabledirectory = fread($fonthandle, 16);
            $sztag = substr($tttabledirectory, 0, 4);
            if (strtolower($sztag) == 'name') {
                $uoffset = $this->u_long(substr($tttabledirectory, 8, 4));
                $nametablefound = true;
                break;
            }
        }

        if (!$nametablefound) {
            fclose($fonthandle);
            throw new Exception('Can\'t find name table in ' . $this->_filename);
        }

        // Set offset to the start of the name table.
        fseek($fonthandle, $uoffset, SEEK_SET);

        $ttnametableheader = fread($fonthandle, 6);

        $unrcount = $this->u_short(substr($ttnametableheader, 2, 2));
        $ustorageoffset = $this->u_short(substr($ttnametableheader, 4, 2));

        $attributecount = 0;
        for ($a = 0; $a < $unrcount; $a++) {
            $ttnamerecord = fread($fonthandle, 12);

            $unameid = $this->u_short(substr($ttnamerecord, 6, 2));
            if ($unameid <= 7) {
                $ustringlength = $this->u_short(substr($ttnamerecord, 8, 2));
                $ustringoffset = $this->u_short(substr($ttnamerecord, 10, 2));

                if ($ustringlength > 0) {
                    $npos = ftell($fonthandle);
                    fseek($fonthandle, $uoffset + $ustringoffset + $ustorageoffset, SEEK_SET);
                    $testvalue = fread($fonthandle, $ustringlength);

                    if (trim($testvalue) > '') {
                        switch ($unameid) {
                            case 0  :
                                if ($this->_copyright == null) {
                                    $this->_copyright = $testvalue;
                                    $attributecount++;
                                }
                                break;
                            case 1  :
                                if ($this->_fontfamily == null) {
                                    $this->_fontfamily = $testvalue;
                                    $attributecount++;
                                }
                                break;
                            case 2  :
                                if ($this->_fontsubfamily == null) {
                                    $this->_fontsubfamily = $testvalue;
                                    $attributecount++;
                                }
                                break;
                            case 3  :
                                if ($this->_fontidentifier == null) {
                                    $this->_fontidentifier = $testvalue;
                                    $attributecount++;
                                }
                                break;
                            case 4  :
                                if ($this->_fontname == null) {
                                    $this->_fontname = $testvalue;
                                    $attributecount++;
                                }
                                break;
                            case 5  :
                                if ($this->_fontversion == null) {
                                    $this->_fontversion = $testvalue;
                                    $attributecount++;
                                }
                                break;
                            case 6  :
                                if ($this->_postscriptname == null) {
                                    $this->_postscriptname = $testvalue;
                                    $attributecount++;
                                }
                                break;
                            case 7  :
                                if ($this->_trademark == null) {
                                    $this->_trademark = $testvalue;
                                    $attributecount++;
                                }
                                break;
                        }
                    }
                    fseek($fonthandle, $npos, SEEK_SET);
                }
            }
            if ($attributecount > 7) {
                break;
            }
        }

        fclose($fonthandle);
        return true;
    }

    /**
     * @param string $filename
     *
     * @throws Exception
     */
    public function __construct($filename = '') {

        $this->_filename = $filename;

        if ($this->_filename == '') {
            throw new Exception('Font File has not been specified');
        } else if (!file_exists($this->_filename)) {
            throw new Exception($this->_filename . ' does not exist');
        } else if (!is_readable($this->_filename)) {
            throw new Exception($this->_filename . ' is not a readable file');
        }

        return $this->read_font_attributes();
    }

}
