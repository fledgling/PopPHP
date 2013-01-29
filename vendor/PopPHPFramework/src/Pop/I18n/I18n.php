<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_I18n
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\I18n;

/**
 * I18n exception class
 *
 * @category   Pop
 * @package    Pop_I18n
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.2.0
 */
class I18n
{

    /**
     * Default system language
     * @var string
     */
    protected $language = null;

    /**
     * Default system locale
     * @var string
     */
    protected $locale = null;

    /**
     * Language content
     * @var array
     */
    //protected $content = array('source' => array(), 'output' => array());
    protected $content = array();

    /**
     * Constructor
     *
     * Instantiate the I18n object.
     *
     * @param  string $lang
     * @param  string $locale
     * @return \Pop\I18n\I18n
     */
    public function __construct($lang = null, $locale = null)
    {
        if (null !== $lang) {
            $this->language = $lang;
        } else if (defined('POP_DEFAULT_LANG')) {
            $this->language = POP_DEFAULT_LANG;
        } else {
            $this->language = 'en';
        }

        if (null !== $locale) {
            $this->locale = $locale;
        } else if (defined('POP_DEFAULT_LOCALE')) {
            $this->locale = POP_DEFAULT_LOCALE;
        } else {
            $this->locale = 'us';
        }

        $this->loadCurrentLanguage();
    }

    /**
     * Static method to load the I18n object.
     *
     * @param  string $lang
     * @param  string $locale
     * @return \Pop\I18n\I18n
     */
    public static function factory($lang = null, $locale = null)
    {
        return new self($lang, $locale);
    }

    /**
     * Get current language setting.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get current locale setting.
     *
     * @return string
     */
    public function getI18n()
    {
        return $this->locale;
    }

    /**
     * Load language content from an XML file.
     *
     * @param  string $langFile
     * @throws Exception
     * @return void
     */
    public function loadFile($langFile)
    {
        if (file_exists($langFile)) {
            if (($xml =@ new \SimpleXMLElement($langFile, LIBXML_NOWARNING, true)) !== false) {
                foreach ($xml->text as $text) {
                    if (isset($text->source) && isset($text->output)) {
                        $this->content['source'][] = (string)$text->source;
                        $this->content['output'][] = (string)$text->output;
                    }
                }
            } else {
                throw new Exception('Error: There was an error processing that XML file.');
            }
        } else {
            throw new Exception('Error: The language file ' . $langFile . ' does not exist.');
        }
    }

    /**
     * Return the translated string
     *
     * @param  string $str
     * @param  string|array $params
     * @return $str
     */
    public function __($str, $params = null)
    {
        return $this->translate($str, $params);
    }

    /**
     * Echo the translated string.
     *
     * @param  string $str
     * @param  string|array $params
     * @return void
     */
    public function _e($str, $params = null)
    {
        echo $this->translate($str, $params);
    }

    /**
     * Get languages from the XML files.
     *
     * @param  string $dir
     * @return array
     */
    public function getLanguages($dir = null)
    {
        $langsAry = array();
        $langDirectory = (null !== $dir) ? $dir : __DIR__ . '/Data';

        if (file_exists($langDirectory)) {
            $langDir = new \Pop\File\Dir($langDirectory);
            $files = $langDir->getFiles();
            foreach ($files as $file) {
                if ($file != '__.xml') {
                    if (($xml =@ new \SimpleXMLElement($langDirectory . '/' . $file, LIBXML_NOWARNING, true)) !== false) {
                        if ((string)$xml->attributes()->name == (string)$xml->attributes()->native) {
                            $langsAry[str_replace('.xml', '', $file)] = (string)$xml->attributes()->native;
                        } else {
                            $langsAry[str_replace('.xml', '', $file)] = $xml->attributes()->native . ' (' . $xml->attributes()->name . ")";
                        }
                    }
                }
            }
        }

        ksort($langsAry);
        return $langsAry;
    }

    /**
     * Translate and return the string.
     *
     * @param  string $str
     * @param  string|array $params
     * @return mixed
     */
    protected function translate($str, $params = null)
    {
        $key = array_search($str, $this->content['source']);
        $trans = ($key !== false) ? $this->content['output'][$key] : $str;

        if (null !== $params) {
            if (is_array($params)) {
                foreach ($params as $key => $value) {
                    $trans = str_replace('%' . ($key + 1), $value, $trans);
                }
            } else {
                $trans = str_replace('%1', $params, $trans);
            }
        }

        return $trans;
    }

    /**
     * Get language content from the XML file.
     *
     * @return void
     */
    protected function loadCurrentLanguage()
    {
        $this->loadFile(__DIR__ . '/Data/' . $this->language . '.xml');
    }

}