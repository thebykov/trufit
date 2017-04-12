<?php

/*
Name:    gdr2_SEO
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/

== Copyright ==
Copyright 2008 - 2015 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('gdr2_phpSEO')) {
    /* 
     * Based on:         phpSEO
     * Version:          v0.5
     * 
     * Expanded by:      Milan Petrovic, http://www.dev4press.com/
     * 
     * License:          GPL v3
     * Original Date:    17/02/2010
     * Original Author:  Chema Garrido, http://neo22s.com/phpseo
     * Original Notes:   Based on http://neo22s.com/seo-functions-for-php/
     */
    class gdr2_phpSEO {
        private $text;
        private $maxDescriptionLen = 220;
        private $maxKeywords = 25;
        private $minWordLen = 3;
        private $bannedWords = array('able', 'about', 'above', 'act', 'add', 'afraid', 'after', 'again', 'against', 'age', 'ago', 'agree', 'all', 'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am', 'amount', 'an', 'and', 'anger', 'angry', 'animal', 'another', 'answer', 'any', 'appear', 'apple', 'are', 'arrive', 'arm', 'arms', 'around', 'arrive', 'as', 'ask', 'at', 'attempt', 'aunt', 'away', 'back', 'bad', 'bag', 'bay', 'be', 'became', 'because', 'become', 'been', 'before', 'began', 'begin', 'behind', 'being', 'bell', 'belong', 'below', 'beside', 'best', 'better', 'between', 'beyond', 'big', 'body', 'bone', 'born', 'borrow', 'both', 'bottom', 'box', 'boy', 'break', 'bring', 'brought', 'bug', 'built', 'busy', 'but', 'buy', 'by', 'call', 'came', 'can', 'cause', 'choose', 'close', 'close', 'consider', 'come', 'consider', 'considerable', 'contain', 'continue', 'could', 'cry', 'cut', 'dare', 'dark', 'deal', 'dear', 'decide', 'deep', 'did', 'die', 'do', 'does', 'dog', 'done', 'doubt', 'down', 'during', 'each', 'ear', 'early', 'eat', 'effort', 'either', 'else', 'end', 'enjoy', 'enough', 'enter', 'even', 'ever', 'every', 'except', 'expect', 'explain', 'fail', 'fall', 'far', 'fat', 'favor', 'fear', 'feel', 'feet', 'fell', 'felt', 'few', 'fill', 'find', 'fit', 'fly', 'follow', 'for', 'forever', 'forget', 'from', 'front', 'gave', 'get', 'gives', 'goes', 'gone', 'good', 'got', 'gray', 'great', 'green', 'grew', 'grow', 'guess', 'had', 'half', 'hang', 'happen', 'has', 'hat', 'have', 'he', 'hear', 'heard', 'held', 'hello', 'help', 'her', 'here', 'hers', 'high', 'hill', 'him', 'his', 'hit', 'hold', 'hot', 'how', 'however', 'I', 'if', 'ill', 'in', 'indeed', 'instead', 'into', 'iron', 'is', 'it', 'its', 'just', 'keep', 'kept', 'knew', 'know', 'known', 'late', 'least', 'led', 'left', 'lend', 'less', 'let', 'like', 'likely', 'likr', 'lone', 'long', 'look', 'lot', 'make', 'many', 'may', 'me', 'mean', 'met', 'might', 'mile', 'mine', 'moon', 'more', 'most', 'move', 'much', 'must', 'my', 'near', 'nearly', 'necessary', 'neither', 'never', 'next', 'no', 'none', 'nor', 'not', 'note', 'nothing', 'now', 'number', 'of', 'off', 'often', 'oh', 'on', 'once', 'only', 'or', 'other', 'ought', 'our', 'out', 'please', 'prepare', 'probable', 'pull', 'pure', 'push', 'put', 'raise', 'ran', 'rather', 'reach', 'realize', 'reply', 'require', 'rest', 'run', 'said', 'same', 'sat', 'saw', 'say', 'see', 'seem', 'seen', 'self', 'sell', 'sent', 'separate', 'set', 'shall', 'she', 'should', 'side', 'sign', 'since', 'so', 'sold', 'some', 'soon', 'sorry', 'stay', 'step', 'stick', 'still', 'stood', 'such', 'sudden', 'suppose', 'take', 'taken', 'talk', 'tall', 'tell', 'ten', 'than', 'thank', 'that', 'the', 'their', 'them', 'then', 'there', 'therefore', 'these', 'they', 'this', 'those', 'though', 'through', 'till', 'to', 'today', 'told', 'tomorrow', 'too', 'took', 'tore', 'tought', 'toward', 'tried', 'tries', 'trust', 'try', 'turn', 'two', 'under', 'until', 'up', 'upon', 'us', 'use', 'usual', 'various', 'verb', 'very', 'visit', 'want', 'was', 'we', 'well', 'went', 'were', 'what', 'when', 'where', 'whether', 'which', 'while', 'white', 'who', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yes', 'yet', 'you', 'young', 'your', 'br', 'img', 'p', 'lt', 'gt', 'quot', 'copy');

        function __construct($text = null) {
            $this->setText($text);
        }

        /**
         * Get the description.
         *
         * @param ing $len length for the description
         * @return string 
         */
        public function getMetaDescription($len = null) {
            $this->setDescriptionLen($len);

            return substr($this->getText(), 0, $this->getDescriptionLen());
        }

        /**
         * Get the list of keywords.
         *
         * @param ing $mKw number of keywords
         * @return array list of the keywords
         */
        public function getKeyWords($mKw = null) {
            $this->setMaxKeywords($mKw);

            $text = $this->getText();
            $text = str_replace(array('Ã¢â‚¬â€œ', '{', '}', '[', ']', '(', ')', '+', ':', '.', '?', '!', '_', '*', '-'), '', $text);
            $text = str_replace(array(' ', '.'), ',', $text);

            $wordCounter = array();

            $arrText = explode(',', $text);
            unset($text);

            foreach ($arrText as $value)  {
                $value = trim($value);

                if (strlen($value) >= $this->getMinWordLen() && !in_array($value, $this->getBannedWords())) {
                    if (array_key_exists($value, $wordCounter)) {
                        $wordCounter[$value] = $wordCounter[$value]+1;
                    } else {
                        $wordCounter[$value] = 1;
                    }
                }
            }

            unset($arrText);

            uasort($wordCounter, array($this, 'cmp'));

            $i = 1;
            $keywords = array();
            foreach($wordCounter as $key => $value) {
                $keywords[] = sanitize_title($key);

                if ($i < $this->getMaxKeywords()) {
                    $i++;
                } else {
                    break;
                }
            }

            unset($wordCounter);
            return $keywords;
        }

        /**
         * Set the content for parsing.
         *
         * @param string $text content for parsing.
         */
        public function setText($text) {
            $text = strip_shortcodes( $text );
            $text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]&gt;', $text);
            $text = strip_tags($text);
            $text = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $text);

            $text = str_replace(array('\t'), ' ', $text);
            $text = str_replace(array('\r\n', '\n', '\r', '+'), ',', $text);
            $text = remove_accents($text);

            $text = strtolower($text);
            $this->text = $text;
        }

        /**
         * Get the content.
         *
         * @return string content.
         */
        public function getText() {
            return $this->text;
        }

        /**
         * Set length for the description.
         *
         * @param int $len length for the descritpion
         */
        public function setDescriptionLen($len) {
            if (is_numeric($len)) {
                $this->maxDescriptionLen = $len;
            }
        }

        /**
         * Get length for the description.
         *
         * @return int length for the descritpion
         */
        public function getDescriptionLen() {
            return $this->maxDescriptionLen;
        }

        /**
         * Set number of keywords.
         *
         * @param int $len number of keywords
         */
        public function setMaxKeywords($len) {
            if (is_numeric($len)) {
                $this->maxKeywords = $len;
            }
        }

        /**
         * Get number of keywords.
         *
         * @return int number of keywords
         */
        public function getMaxKeywords() {
            return $this->maxKeywords;
        }

        /**
         * Set minimal keyword length.
         *
         * @param type $len minimal keyword length
         */
        public function setMinWordLen($len) {
            if (is_numeric($len)) {
                $this->minWordLen = $len;
            }
        }

        /**
         * Get minimal keyword length.
         *
         * @return int minimal keyword length
         */
        public function getMinWordLen() {
            return $this->minWordLen;
        }

        /**
         * Set list of banned keywords.
         *
         * @param string|array $words comma separated keywords string or array 
         * with keywords.
         */
        public function setBannedWords($words) {
            if (isset($words)) {
                if (!is_array($words)) {
                    $words = explode(",", $words);
                }

                $this->bannedWords = $words;
            }
        }

        /**
         * Get the list of banned words.
         *
         * @return array list of banned words
         */
        public function getBannedWords() {
            return $this->bannedWords;
        }

        /**
         * Compare two values to determine if they are equal, smaller or bigger.
         *
         * @param mixed $a first value to compare
         * @param mixed $b second value to compare
         * @return int 0 if equal, 1 if $a is smaller, -1 if $b is smaller
         */
        public function cmp($a, $b) {
            if ($a == $b) {
                return 0;
            }

            return ($a < $b) ? 1 : -1;
        }
    }
}

if (!class_exists('gdr2_SEO')) {
    /**
     * Shared SEO functions.
     */
    class gdr2_SEO {
        function __construct() { }

        /**
         * Get tags using internal phpSEO class.
         * 
         * @param string $title title for the content
         * @param string $content content, can contain HTML
         * @return string|array tags array or error message if fails
         */
        public function get_tags_from_internal($title, $content) {
            $content = $title.' '.strip_tags($content);

            $phpseo = new gdr2_phpSEO($content);
            return $phpseo->getKeyWords(256);
        }

        /**
         * Get tags from OpenCalais.
         * 
         * @param string $title title for the content
         * @param string $content content, can contain HTML
         * @param int $timeout operation timeout. Set to -1 to not use timeout.
         * @param string $api_key API Key
         * @return string|array tags array or error message if fails
         */
        public function get_tags_from_opencalais($title, $content, $timeout = -1, $api_key = "") {
            if (empty($api_key)) return 'api_key_missing';

            $timeout = intval($timeout);
            if ($timeout > -1) {
                $timeout = $timeout < 15 ? 120 : $timeout;
                set_time_limit($timeout);
            }

            if(!function_exists('curl_init')) return array();
            $content = $title.' '.strip_shortcodes(strip_tags($content));
            $tags = '';
            
            $paramsXML = "<c:params xmlns:c=\"http://s.opencalais.com/1/pred/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"> ".
			 "<c:processingDirectives c:contentType=\"text/txt\" c:outputFormat=\"Application/JSON\" ".
                         "c:enableMetadataType=\"SocialTags\"></c:processingDirectives> ".
			 "<c:userDirectives c:allowDistribution=\"false\" c:allowSearch=\"false\" c:externalID=\" \" ".
			 "c:submitter=\"Dev4Press SEO Tagger\"></c:userDirectives> ".
			 "<c:externalMetadata><c:Caller>Dev4Press SEO Tagger</c:Caller></c:externalMetadata></c:params>";

            $data = 'licenseID='.urlencode($api_key).'&&paramsXML='.urlencode($paramsXML).'&content='.urlencode($content);

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_URL, 'http://api.opencalais.com/enlighten/rest/');
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($crl, CURLOPT_HEADER, 0);
            curl_setopt($crl, CURLOPT_TIMEOUT, 3600);
            curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($crl, CURLOPT_POST, 1);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $data);

            try {
                $response = @curl_exec($crl);

                if (curl_errno($crl)) {
                    $tags = curl_error($crl);
                } else {
                    $results = json_decode($response);
                    foreach ($results as $data) {
                        if (isset($data->_typeGroup) && isset($data->name) && $data->_typeGroup == 'socialTag') {
                            $tags[] = $data->name;
                        }
                    }
                }
            } catch (Exception $exc) {
                $tags = $exc->getTraceAsString();
            }

            curl_close($crl);
            return $tags;
        }

        /**
         * Get tags from Alchemy.
         * 
         * @param string $title title for the content
         * @param string $content content, can contain HTML
         * @param int $timeout operation timeout. Set to -1 to not use timeout.
         * @param string $api_key API Key
         * @return string|array tags array or error message if fails
         */
        public function get_tags_from_alchemy($title, $content, $timeout = -1, $api_key = "") {
            if (empty($api_key)) return 'api_key_missing';

            $timeout = intval($timeout);
            if ($timeout > -1) {
                $timeout = $timeout < 15 ? 120 : $timeout;
                set_time_limit($timeout);
            }

            if(!function_exists('curl_init')) return array();
            $content = $title.' '.strip_shortcodes(strip_tags($content));
            $tags = '';

            $data = 'apikey='.$api_key.'&keywordExtractMode=normal&outputMode=json&text='.$content;

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_URL, 'http://access.alchemyapi.com/calls/text/TextGetRankedKeywords');
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($crl, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($crl, CURLOPT_TIMEOUT, 3600);
            curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($crl, CURLOPT_POST, 1);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $data);

            try {
                $response = @curl_exec($crl);

                if (curl_errno($crl)) {
                    $tags = curl_error($crl);
                } else {
                    $results = json_decode($response);
                    if (is_array($results->keywords) && !empty($results->keywords)) {
                        foreach ($results->keywords as $keyword) {
                            $tags[] = $keyword->text;
                        }
                    }
                }
            } catch (Exception $exc) {
                $tags = $exc->getTraceAsString();
            }

            curl_close($crl);
            return $tags;
        }        

        /**
         * Get tags from Zemanta.
         * 
         * @param string $title title for the content
         * @param string $content content, can contain HTML
         * @param int $timeout operation timeout. Set to -1 to not use timeout.
         * @param string $api_key API Key
         * @return string|array tags array or error message if fails
         */
        public function get_tags_from_zemanta($title, $content, $timeout = -1, $api_key = "") {
            if (empty($api_key)) return 'api_key_missing';

            $timeout = intval($timeout);
            if ($timeout > -1) {
                $timeout = $timeout < 15 ? 120 : $timeout;
                set_time_limit($timeout);
            }

            if(!function_exists('curl_init')) return array();
            $content = $title."\r\n".strip_shortcodes(strip_tags($content));
            $tags = '';

            $data = 'api_key='.$api_key.'&return_images=0&return_categories=0&method=zemanta.suggest&format=json&text='.$content;

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_URL, 'http://api.zemanta.com/services/rest/0.0/');
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($crl, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($crl, CURLOPT_TIMEOUT, 3600);
            curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($crl, CURLOPT_POST, 1);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $data);

            try {
                $response = @curl_exec($crl);

                if (curl_errno($crl)) {
                    $tags = curl_error($crl);
                } else {
                    $results = json_decode($response);
                    if (is_array($results->keywords) && !empty($results->keywords)) {
                        foreach ($results->keywords as $keyword) {
                            $tags[] = $keyword->name;
                        }
                    }
                }
            } catch (Exception $exc) {
                $tags = $exc->getTraceAsString();
            }

            curl_close($crl);
            return $tags;
        }

        /**
         * Get tags from Yahoo.
         * 
         * @param string $title title for the content
         * @param string $content content, can contain HTML
         * @param int $timeout operation timeout. Set to -1 to not use timeout.
         * @param string $app_id ID name for the application
         * @return string|array tags array or error message if fails
         */
        public function get_tags_from_yahoo($title, $content, $timeout = -1, $app_id = 'GDR2') {
            if (empty($app_id)) return 'app_id_missing';

            $timeout = intval($timeout);
            if ($timeout > -1) {
                $timeout = $timeout < 15 ? 120 : $timeout;
                set_time_limit($timeout);
            }

            if (!function_exists('curl_init')) {
                return array();
            }

            $content = str_replace('"', ' ', $title." ".strip_shortcodes(strip_tags($content)));
            $tags = array();

            $query = 'SELECT * FROM contentanalysis.analyze WHERE enable_categorizer="false" and unique="true" and text="'.$content.'"';
            $query = str_replace(array(' ', '=', '"'), array('%20', '%3D', '%22'), $query);

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_URL, 'http://query.yahooapis.com/v1/public/yql');
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($crl, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($crl, CURLOPT_TIMEOUT, 3600);
            curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($crl, CURLOPT_POST, 1);
            curl_setopt($crl, CURLOPT_AUTOREFERER, 1);
            curl_setopt($crl, CURLOPT_POSTFIELDS, "q=$query&format=json");

            try {
                $response = @curl_exec($crl);

                if (curl_errno($crl)) {
                    $tags = curl_error($crl);
                } else {
                    $results = json_decode($response);

                    if (!isset($results->error) && isset($results->query)) {
                        if (isset($results->query->results->entities->entity)) {
                            $set = $results->query->results->entities->entity;

                            if (!is_array($set)) {
                                $set = array($set);
                            }

                            foreach ($set as $obj) {
                                $tags[] = $obj->text->content;
                            }
                        }
                    }
                }
            } catch (Exception $exc) {
                $tags = $exc->getTraceAsString();
            }

            curl_close($crl);
            return $tags;
        }
    }
}

?>