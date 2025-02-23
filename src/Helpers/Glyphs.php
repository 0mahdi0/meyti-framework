<?php

namespace App\Helpers;


class Glyphs
{
    private $_glyphs   = null;
    private $_hex      = null;
    private $_prevLink = null;
    private $_nextLink = null;
    private $_vowel    = null;

    /**
     * Loads initialize values
     *
     * @ignore
     */
    public function __construct()
    {
        $this->_prevLink  = '،؟؛ـئبتثجحخسشصضطظعغفقکلمنهی';
        $this->_nextLink  = 'ـآأؤإائبةتثجحخدذرز';
        $this->_nextLink .= 'سشصضطظعغفقکلمنهوىی';
        $this->_vowel     = 'ًٌٍَُِّْ';

        /*
         $this->_glyphs['ً']  = array('FE70','FE71');
         $this->_glyphs['ٌ']  = array('FE72','FE72');
         $this->_glyphs['ٍ']  = array('FE74','FE74');
         $this->_glyphs['َ']  = array('FE76','FE77');
         $this->_glyphs['ُ']  = array('FE78','FE79');
         $this->_glyphs['ِ']  = array('FE7A','FE7B');
         $this->_glyphs['ّ']  = array('FE7C','FE7D');
         $this->_glyphs['ْ']  = array('FE7E','FE7E');
         */

        $this->_glyphs = 'ًٌٍَُِّْٰ';
        //$this->_hex    = 'FB91064BFB90064B064C064C064C064C064D064D064D064D064E064E';
        $this->_hex    = '2063206320632063064C064C064C064C064D064D064D064D064E064E';
        $this->_hex   .= '064E064E064F064F064F064F06500650065006500651065106510651';
        $this->_hex   .= '06520652065206520670067006700670';

        $this->_glyphs .= 'ءآأؤإئاب';
        $this->_hex    .= 'FE80FE80FE80FE80FE81FE82FE81FE82FE83FE84FE83FE84FE85FE86';
        $this->_hex    .= 'FE85FE86FE87FE88FE87FE88FE89FE8AFE8BFE8CFE8DFE8EFE8DFE8E';
        $this->_hex    .= 'FE8FFE90FE91FE92';

        $this->_glyphs .= 'ةتثجحخدذ';
        $this->_hex    .= 'FE93FE94FE93FE94FE95FE96FE97FE98FE99FE9AFE9BFE9CFE9DFE9E';
        $this->_hex    .= 'FE9FFEA0FEA1FEA2FEA3FEA4FEA5FEA6FEA7FEA8FEA9FEAAFEA9FEAA';
        $this->_hex    .= 'FEABFEACFEABFEAC';

        $this->_glyphs .= 'رزسشصضطظ';
        $this->_hex    .= 'FEADFEAEFEADFEAEFEAFFEB0FEAFFEB0FEB1FEB2FEB3FEB4FEB5FEB6';
        $this->_hex    .= 'FEB7FEB8FEB9FEBAFEBBFEBCFEBDFEBEFEBFFEC0FEC1FEC2FEC3FEC4';
        $this->_hex    .= 'FEC5FEC6FEC7FEC8';

        $this->_glyphs .= 'عغفقکلمن';
        $this->_hex    .= 'FEC9FECAFECBFECCFECDFECEFECFFED0FED1FED2FED3FED4FED5FED6';
        $this->_hex    .= 'FED7FED8FB8EFB8FFB90FB91FEDDFEDEFEDFFEE0FEE1FEE2FEE3FEE4';
        $this->_hex    .= 'FEE5FEE6FEE7FEE8';

        $this->_glyphs .= 'هوىيـ،؟؛';
        $this->_hex    .= 'FEE9FEEAFEEBFEECFEEDFEEEFEEDFEEEFBFCFBFDFBFCFBFDFBFCFBFD';
        $this->_hex    .= 'FBFEFBFF0640064006400640060C060C060C060C061F061F061F061F';
        $this->_hex    .= '061B061B061B061B';

        // Support the extra 4 Persian letters (p), (ch), (zh) and (g)
        // This needs value in getGlyphs function to be 52 instead of 48
        // $this->_prevLink .= chr(129).chr(141).chr(142).chr(144);
        // $this->_nextLink .= chr(129).chr(141).chr(142).chr(144);
        $this->_prevLink .= 'پچژگ';
        $this->_nextLink .= 'پچژگ';

        // $this->_glyphs .= chr(129).chr(141).chr(142).chr(144);
        $this->_glyphs .= 'پچژگ';
        $this->_hex    .= 'FB56FB57FB58FB59FB7AFB7BFB7CFB7DFB8AFB8BFB8AFB8BFB92';
        $this->_hex    .= 'FB93FB94FB95';

        // Arabic numbers - extended
        $this->_glyphs .= '۰۱۲۳۴۵۶۷۸۹';
        $this->_hex    .= '06F006F006F006F006F106F106F106F106F206F206F206F206F306F306F306F306F406F406F406F406F506F506F506F5';
        $this->_hex    .= '06F606F606F606F606F706F706F706F706F806F806F806F806F906F906F906F9';

        /*
		// Arab numbers - regular - not to be used if extended is used [and visa versa]
        $this->_glyphs .= '٠١٢٣٤٥٦٧٨٩';   
        $this->_hex    .= '066006600660066006610661066106610662066206620662066306630663066306640664066406640665066506650665';
        $this->_hex    .= '0666066606660666066706670667066706680668066806680669066906690669';
*/

        $this->_prevLink .= 'ی';
        $this->_nextLink .= 'ی';
        $this->_glyphs .= 'ی';
        $this->_hex    .= 'FBFCFBFDFBFEFBFF';



        // Example:     $text = 'نمونة قلم: لا‌گچ ژافپ';
        // Email Yossi Beck <yosbeck@gmail.com> ask him to save that example

        // string using ANSI encoding in Notepad
        $this->_glyphs .= '';
        $this->_hex    .= '';

        $this->_glyphs .= 'لآلألإلا';
        $this->_hex    .= 'FEF5FEF6FEF5FEF6FEF7FEF8FEF7FEF8FEF9FEFAFEF9FEFAFEFBFEFC';
        $this->_hex    .= 'FEFBFEFC';
    }

    /**
     * Get glyphs
     * 
     * @param string  $char Char
     * @param integer $type Type
     * 
     * @return string
     */
    protected function getGlyphs($char, $type)
    {

        $pos = mb_strpos($this->_glyphs, $char);

        if ($pos > 64) {
            $pos = ($pos - 64) / 2 + 64;
        }

        $pos = $pos * 16 + $type * 4;

        return substr($this->_hex, $pos, 4);
    }

    /**
     * Convert Arabic Windows-1256 charset string into glyph joining in UTF-8 
     * hexadecimals stream
     *      
     * @param string $str Arabic string in Windows-1256 charset
     *      
     * @return string Arabic glyph joining in UTF-8 hexadecimals stream
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function preConvert($str)
    {
        $crntChar = null;
        $prevChar = null;
        $nextChar = null;
        $output   = '';

        $_temp = mb_strlen($str);

        for ($i = 0; $i < $_temp; $i++) {
            $chars[] = mb_substr($str, $i, 1);
        }

        $max = count($chars);

        for ($i = $max - 1; $i >= 0; $i--) {
            $crntChar = $chars[$i];
            $prevChar = ' ';

            if ($i > 0) {
                $prevChar = $chars[$i - 1];
            }

            if ($prevChar && mb_strpos($this->_vowel, $prevChar) !== false) {
                $prevChar = $chars[$i - 2];
                if ($prevChar && mb_strpos($this->_vowel, $prevChar) !== false) {
                    $prevChar = $chars[$i - 3];
                }
            }

            $Reversed    = false;
            $flip_arr    = ')]>}';
            $ReversedChr = '([<{';

            if ($crntChar && mb_strpos($flip_arr, $crntChar) !== false) {
                $crntChar = $ReversedChr[mb_strpos($flip_arr, $crntChar)];
                $Reversed = true;
            } else {
                $Reversed = false;
            }

            if (
                $crntChar && !$Reversed
                && (mb_strpos($ReversedChr, $crntChar) !== false)
            ) {
                $crntChar = $flip_arr[mb_strpos($ReversedChr, $crntChar)];
            }

            if (ord($crntChar) < 128) {
                $output  .= $crntChar;
                $nextChar = $crntChar;
                continue;
            }

            if (
                $crntChar == 'ل' && isset($chars[$i + 1])
                && (mb_strpos('آأإا', $chars[$i + 1]) !== false)
            ) {
                continue;
            }

            if ($crntChar && mb_strpos($this->_vowel, $crntChar) !== false) {
                if (
                    isset($chars[$i + 1])
                    && (mb_strpos($this->_nextLink, $chars[$i + 1]) !== false)
                    && (mb_strpos($this->_prevLink, $prevChar) !== false)
                ) {
                    $output .= '&#x' . $this->getGlyphs($crntChar, 1) . ';';
                } else {
                    $output .= '&#x' . $this->getGlyphs($crntChar, 0) . ';';
                }
                continue;
            }

            $form = 0;

            if (($prevChar == 'لا' || $prevChar == 'لآ' || $prevChar == 'لأ'
                    || $prevChar == 'لإ' || $prevChar == 'ل')
                && (mb_strpos('آأإا', $crntChar) !== false)
            ) {
                if (mb_strpos($this->_prevLink, $chars[$i - 2]) !== false) {
                    $form++;
                }

                if (mb_strpos($this->_vowel, $chars[$i - 1])) {
                    $output .= '&#x';
                    $output .= $this->getGlyphs($crntChar, $form) . ';';
                } else {
                    $output .= '&#x';
                    $output .= $this->getGlyphs($prevChar . $crntChar, $form) . ';';
                }
                $nextChar = $prevChar;
                continue;
            }

            if ($prevChar && mb_strpos($this->_prevLink, $prevChar) !== false) {
                $form++;
            }

            if ($nextChar && mb_strpos($this->_nextLink, $nextChar) !== false) {
                $form += 2;
            }

            $output  .= '&#x' . $this->getGlyphs($crntChar, $form) . ';';
            $nextChar = $crntChar;
        }

        // from Arabic Presentation Forms-B, Range: FE70-FEFF, 
        // file "UFE70.pdf" (in reversed order)
        // into Arabic Presentation Forms-A, Range: FB50-FDFF, file "UFB50.pdf"
        // Example: $output = str_replace('&#xFEA0;&#xFEDF;', '&#xFCC9;', $output);
        // Lam Jeem

        $output = $this->decodeEntities($output, $exclude = array('&'));
        return $output;
    }

    /**
     * Regression analysis calculate roughly the max number of character fit in 
     * one A4 page line for a given font size.
     *      
     * @param integer $font Font size
     *      
     * @return integer Maximum number of characters per line
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function a4MaxChars($font)
    {
        $x = 381.6 - 31.57 * $font + 1.182 * pow($font, 2) - 0.02052 *
            pow($font, 3) + 0.0001342 * pow($font, 4);
        return floor($x - 2);
    }

    /**
     * Calculate the lines number of given Arabic text and font size that will 
     * fit in A4 page size
     *      
     * @param string  $str  Arabic string you would like to split it into lines
     * @param integer $font Font size
     *                    
     * @return integer Number of lines for a given Arabic string in A4 page size
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function a4Lines($str, $font)
    {
        $str = str_replace(array("\r\n", "\n", "\r"), "\n", $str);

        $lines     = 0;
        $chars     = 0;
        $words     = explode(' ', $str);
        $w_count   = count($words);
        $max_chars = $this->a4MaxChars($font);

        for ($i = 0; $i < $w_count; $i++) {
            $w_len = mb_strlen($words[$i]) + 1;

            if ($chars + $w_len < $max_chars) {
                if (mb_strpos($words[$i], "\n") !== false) {
                    $words_nl = explode("\n", $words[$i]);

                    $nl_num = count($words_nl) - 1;
                    for ($j = 1; $j < $nl_num; $j++) {
                        $lines++;
                    }

                    $chars = mb_strlen($words_nl[$nl_num]) + 1;
                } else {
                    $chars += $w_len;
                }
            } else {
                $lines++;
                $chars = $w_len;
            }
        }
        $lines++;

        return $lines;
    }


    /**
     * Convert Arabic Windows-1256 charset string into glyph joining in UTF-8 
     * hexadecimals stream (take care of whole the document including English 
     * sections as well as numbers and arcs etc...)
     *                    
     * @param string  $str       Arabic string in Windows-1256 charset
     * @param integer $max_chars Max number of chars you can fit in one line
     * @param boolean $hindo     If true use Hindo digits else use Arabic digits
     *                    
     * @return string Arabic glyph joining in UTF-8 hexadecimals stream (take
     *                care of whole document including English sections as well
     *                as numbers and arcs etc...)
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function utf8Glyphs($str, $max_chars = 150, $hindo = true)
    {
        $str = str_replace(array("\r\n", "\n", "\r"), " \n ", $str);
        $str = str_replace("\t", "        ", $str);

        $str = str_replace("ژ", "ژ‌", $str); // a Zero width non joiner is place after the char ژ to make sure the next char will not ne in "end" form.

        $lines   = array();
        $words   = explode(' ', $str);
        $w_count = count($words);
        $c_chars = 0;
        $c_words = array();

        $english  = array();
        $en_index = -1;

        $en_words = array();
        $en_stack = array();
        /*		
		function utf8_strrev($str){
			preg_match_all('/./us', $str, $ar);
			return join('',array_reverse($ar[0]));
		}
*/

        for ($i = 0; $i < $w_count; $i++) {
            $pattern  = '/^(\n?)';
            $pattern .= '[a-z\d\\/\@\#\$\%\^\&\*\(\)\_\~\"\'\[\]\{\}\;\,\|\-\.\:!]*';
            $pattern .= '([\.\:\+\=\-\!،؟]?)$/i';

            $arnums_pattern = '(۰|۱|۲|۳|۴|۵|۶|۷|۸|۹)';
            if (preg_match($arnums_pattern, $words[$i])) {
                //$words[$i] = utf8_strrev($words[$i]);
                preg_match_all('/./us', $words[$i], $ar);
                $words[$i] = join('', array_reverse($ar[0]));
            }

            if (preg_match($pattern, $words[$i], $matches)) {
                if ($matches[1]) {
                    $words[$i] = mb_substr($words[$i], 1) . $matches[1];
                }
                if ($matches[2]) {
                    $words[$i] = $matches[2] . mb_substr($words[$i], 0, -1);
                }
                $words[$i] = strrev($words[$i]);
                array_push($english, $words[$i]);
                if ($en_index == -1) {
                    $en_index = $i;
                }
                $en_words[] = true;
            } elseif ($en_index != -1) {
                $en_count = count($english);

                for ($j = 0; $j < $en_count; $j++) {
                    $words[$en_index + $j] = $english[$en_count - 1 - $j];
                }

                $en_index = -1;
                $english  = array();

                $en_words[] = false;
            } else {
                $en_words[] = false;
            }
        }

        if ($en_index != -1) {
            $en_count = count($english);

            for ($j = 0; $j < $en_count; $j++) {
                $words[$en_index + $j] = $english[$en_count - 1 - $j];
            }
        }

        // need more work to fix lines starts by English words
        if (isset($en_start)) {
            $last = true;
            $from = 0;

            foreach ($en_words as $key => $value) {
                if ($last !== $value) {
                    $to = $key - 1;
                    array_push($en_stack, array($from, $to));
                    $from = $key;
                }
                $last = $value;
            }

            array_push($en_stack, array($from, $key));

            $new_words = array();

            while (list($from, $to) = array_pop($en_stack)) {
                for ($i = $from; $i <= $to; $i++) {
                    $new_words[] = $words[$i];
                }
            }

            $words = $new_words;
        }

        for ($i = 0; $i < $w_count; $i++) {
            $w_len = mb_strlen($words[$i]) + 1;

            if ($c_chars + $w_len < $max_chars) {
                if (mb_strpos($words[$i], "\n") !== false) {
                    $words_nl = explode("\n", $words[$i]);

                    array_push($c_words, $words_nl[0]);
                    array_push($lines, implode(' ', $c_words));

                    $nl_num = count($words_nl) - 1;
                    for ($j = 1; $j < $nl_num; $j++) {
                        array_push($lines, $words_nl[$j]);
                    }

                    $c_words = array($words_nl[$nl_num]);
                    $c_chars = mb_strlen($words_nl[$nl_num]) + 1;
                } else {
                    array_push($c_words, $words[$i]);
                    $c_chars += $w_len;
                }
            } else {
                array_push($lines, implode(' ', $c_words));
                $c_words = array($words[$i]);
                $c_chars = $w_len;
            }
        }
        array_push($lines, implode(' ', $c_words));

        $maxLine = count($lines);
        $output  = '';

        for ($j = $maxLine - 1; $j >= 0; $j--) {
            $output .= $lines[$j] . "\n";
        }

        $output = rtrim($output);

        $output = $this->preConvert($output);
        if ($hindo) {
            $nums   = array(
                '0', '1', '2', '3', '4',
                '5', '6', '7', '8', '9'
            );
            $arNums = array(
                '٠', '١', '٢', '٣', '٤',
                '٥', '٦', '٧', '٨', '٩'
            );

            foreach ($nums as $k => $v) {
                $p_nums[$k] = '/' . $v . '/ui';
            }
            $output = preg_replace($p_nums, $arNums, $output);

            foreach ($arNums as $k => $v) {
                $p_arNums[$k] = '/([a-z-\d]+)' . $v . '/ui';
            }
            foreach ($nums as $k => $v) {
                $r_nums[$k] = '${1}' . $v;
            }
            $output = preg_replace($p_arNums, $r_nums, $output);

            foreach ($arNums as $k => $v) {
                $p_arNums[$k] = '/' . $v . '([a-z-\d]+)/ui';
            }
            foreach ($nums as $k => $v) {
                $r_nums[$k] = $v . '${1}';
            }
            $output = preg_replace($p_arNums, $r_nums, $output);
        }

        return $output;
    }

    /**
     * Decode all HTML entities (including numerical ones) to regular UTF-8 bytes. 
     * Double-escaped entities will only be decoded once 
     * ("&amp;lt;" becomes "&lt;", not "<").
     *                   
     * @param string $text    The text to decode entities in.
     * @param array  $exclude An array of characters which should not be decoded.
     *                        For example, array('<', '&', '"'). This affects
     *                        both named and numerical entities.
     *                        
     * @return string           
     */
    protected function decodeEntities($text, $exclude = array())
    {
        static $table;

        // We store named entities in a table for quick processing.
        if (!isset($table)) {
            // Get all named HTML entities.
            $table = array_flip(get_html_translation_table(HTML_ENTITIES));

            // PHP gives us ISO-8859-1 data, we need UTF-8.
            $table = @array_map('utf8_encode', $table);

            // Add apostrophe (XML)
            $table['&apos;'] = "'";
        }
        $newtable = array_diff($table, $exclude);

        // Use a regexp to select all entities in one pass, to avoid decoding 
        // double-escaped entities twice.
        //return preg_replace('/&(#x?)?([A-Za-z0-9]+);/e', 
        //                    '$this->decodeEntities2("$1", "$2", "$0", $newtable, 
        //                                             $exclude)', $text);

        $pieces = explode('&', $text);
        $text   = array_shift($pieces);
        foreach ($pieces as $piece) {
            if ($piece[0] == '#') {
                if ($piece[1] == 'x') {
                    $one = '#x';
                } else {
                    $one = '#';
                }
            } else {
                $one = '';
            }
            $end   = mb_strpos($piece, ';');
            $start = mb_strlen($one);

            $two   = mb_substr($piece, $start, $end - $start);
            $zero  = '&' . $one . $two . ';';
            $text .= $this->decodeEntities2($one, $two, $zero, $newtable, $exclude) .
                mb_substr($piece, $end + 1);
        }
        return $text;
    }

    /**
     * Helper function for decodeEntities
     * 
     * @param string $prefix    Prefix      
     * @param string $codepoint Codepoint         
     * @param string $original  Original        
     * @param array  &$table    Store named entities in a table      
     * @param array  &$exclude  An array of characters which should not be decoded
     * 
     * @return string                  
     */
    protected function decodeEntities2(
        $prefix,
        $codepoint,
        $original,
        &$table,
        &$exclude
    ) {
        // Named entity
        if (!$prefix) {
            if (isset($table[$original])) {
                return $table[$original];
            } else {
                return $original;
            }
        }

        // Hexadecimal numerical entity
        if ($prefix == '#x') {
            $codepoint = base_convert($codepoint, 16, 10);
        }

        // Encode codepoint as UTF-8 bytes
        if ($codepoint < 0x80) {
            $str = chr($codepoint);
        } elseif ($codepoint < 0x800) {
            $str = chr(0xC0 | ($codepoint >> 6)) .
                chr(0x80 | ($codepoint & 0x3F));
        } elseif ($codepoint < 0x10000) {
            $str = chr(0xE0 | ($codepoint >> 12)) .
                chr(0x80 | (($codepoint >> 6) & 0x3F)) .
                chr(0x80 | ($codepoint & 0x3F));
        } elseif ($codepoint < 0x200000) {
            $str = chr(0xF0 | ($codepoint >> 18)) .
                chr(0x80 | (($codepoint >> 12) & 0x3F)) .
                chr(0x80 | (($codepoint >> 6) & 0x3F)) .
                chr(0x80 | ($codepoint & 0x3F));
        }

        // Check for excluded characters
        if (in_array($str, $exclude)) {
            return $original;
        } else {
            return $str;
        }
    }
}
