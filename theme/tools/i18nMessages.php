<?php
/**
 * author: marlon.jerez
 * Date: 12/02/2018
 * Time: 18:03
 * repo: https://github.com/M-jerez/php-translation
 */


class i18nMessages {
    
   

    //const pattern = "/\sp\(.*\)/";
    const copyright = "/** \n* This file is generated with 'i18nMessages.php' \n* Website: http://github.com/M-jerez/ \n* Author : m-jerez \n*/";
    const languagesDir = 'lang';
    const systemDir = 'system';
    const phpExtension = '.php';
    
    
    private static $rootDir = __DIR__;
    private static $locale = 'en';
    private static $languages = array('en', 'es', 'fr', 'de');

    
    
    /**
     * Sets a new root directory from which scan all source code.
     * @param type $rootDir the new root Dyrectory
     */
    public static function setRootDirectory($rootDir){
        self::$rootDir = $rootDir;
    }
    
    /**
     * Sets the default locale 'language' to work with. Messages are translated tho this
     * default language whe the p() function is called. 
     * @param type $lang
     */
    public static function setLocale($locale) {
        self::$locale = $locale;
    }

    /**
     * Sets the range of available languages 'locales' by the system.
     * @param type $languagesArray
     */
    public static function setlanguages($languagesArray) {
        self::$languages = $languagesArray;
    }
    
    private $systemTranslations;
    private $newTranslations;
    private $messages;
    
    
    function __construct() {
        $this->messages = self::initLangArray();
    }
    
    
    /** 
     * Creates and initilize a language array with the existing languages files.
     * This function makes use of require() to load lang files, so it throws a fatal error
     * if the lang file doesn't exist. you must use of the compile() function of this class
     * to create an empty language file and avoid this error.
     * 
     * @param type $subdirectory the subdirectory withing the languages files
     */
    private function initLangArray($subdirectory="") {
        $langsArray = self::createLangArray();
        $ext = self::phpExtension; 
        $ds = DIRECTORY_SEPARATOR;
        $sds = empty($subdirectory)?"":$ds;
        $languagesDir = self::$rootDir. $ds .self::languagesDir. $ds .$subdirectory.$sds;
        $phpFiles = glob("$languagesDir*$ext");        
        foreach ($phpFiles as $filename) {
            $lang = str_replace($languagesDir, "", $filename);
            $lang = str_replace($ext, "", $lang);
            if(isset($langsArray[$lang])){
                $aux = require($filename);
                if (is_array($aux))
                  $langsArray[$lang] = $aux;
            }
        }        
        return $langsArray;
    }
    
    
    /** Creates an empty Languages array with the next structure.
     * array(n) {
     *  ['en']=> array(0){}
     *  ['es']=> array(0){}
     *  ..........
     *  ['lang_N']=> array(0){}
     * } 
     * @return array  the new empty Languages Array.
     */
    private function createLangArray(){
        $langArray = array();
        $languages = self::$languages;
        foreach ($languages as $lang) {
            $langArray[$lang] = array();
        }
        return $langArray;
    }
    
    /**
     * Scans all php files searching for calls to the p() function, then creates 
     * languages files from the found calls. 
     */
    public function compile($rootDir=null) {
        if($rootDir!=null) self::setRootDirectory ($rootDir);
        $this->newTranslations = self::createLangArray();        
        $this->systemTranslations = self::initLangArray(self::systemDir);
        $this->readSources();
        $this->save();
    }    
    
    /**
     * Scans all php files and creates a Language Array from all p() function calls
     * found.
     * To do so, this function uses file_get_contents($filename) that transform php source files into tokens.
     * @link http://php.net/manual/en/function.token-get-all.php
     */
    private function readSources() {
        $ext = self::phpExtension;
        $sorceFiles = self::rglob(self::$rootDir);
        
        foreach ($sorceFiles as $filename) {
        	$rname = realpath($filename);
            if (__FILE__ == $rname)
                continue;
            $content = file_get_contents($rname);
            $tokens = token_get_all($content);
            for ($index = 0; $index < count($tokens); $index++) {
                $token = &$tokens[$index];
                if(is_array($token) && $token[0] === T_STRING && ($token[1] === 'p' || $token[1] === 'g') ){
                    // this is the token of the 'p' function.
                    $index = $this->scanMessage($tokens, $index, $rname, $token[2]);
                } 
            }
        }
    }
    
    private static function rglob($path) {
        $directories = glob($path. DIRECTORY_SEPARATOR ."*", GLOB_ONLYDIR|GLOB_NOSORT); 
        $ext = self::phpExtension;
        $files = glob($path. DIRECTORY_SEPARATOR . "*$ext");
        foreach ($directories as $dirName) {
            $langdir = self::$rootDir. DIRECTORY_SEPARATOR .self::languagesDir;
            if($dirName==$langdir)
                    continue;
            $subFiles = self::rglob($dirName);
            if($subFiles!=null)
                $files = array_merge($files,$subFiles );
        }
        return $files;
    }
    
    
    /**
     * Search for the first argument of the p() function, wich corresponds to the 
     * message to be translated and set a new entry in the languages Array
     * This funcion Retrieves a tokenized array and an index where the p() function starts
     * in the given tokenized array.  
     * 
     * @param type $tokens the tokenized array 
     * @param type $index the index where starts the p() function call in the $tokens array
     */
    private function scanMessage(&$tokens, $index, $filename, $lineNum){    
        $shortFilename = str_replace(__DIR__, '', $filename);
        do{
            $index++;
        }while($tokens[$index]=="(" || $tokens[$index][0]==T_WHITESPACE);
        
        if($tokens[$index][0]==T_CONSTANT_ENCAPSED_STRING){
            $message = $tokens[$index][1];
            $this->setNewEntry($message, $lineNum, $shortFilename);
        }else{
            error_log ("i18nMessages error: php variables are not allowed in messages to translate in $filename : line $lineNum") ;
        }                 
        return $index;
    }
    
    /**
     * Sets a new entry in each language in the $newTranslations Languages array.
     * @param type $message the message to be translated 'the key in the lang array'.
     * @param type $lineNum the line number where it appears in source code.
     * @param type $filename the file name where it appears.
     */
    private function setNewEntry($message, $lineNum, $filename) {
        $delimiter = $message[0];
        $message = trim($message, $delimiter);
        $translated = "";
        foreach (self::$languages as $lang) {
            $this->newTranslations[$lang][$message] = array($translated, $filename , $lineNum , $delimiter);
        }
    }

    
    /**
     * Saves the Languages Arrays into a languages files.
     * A language file is the array written in php code.
     * One file is generated by each language in the language array.
     * A copy of old messages is stored in the /system directory.
     */
    private function save() {
        $langsDir = self::$rootDir . DIRECTORY_SEPARATOR . self::languagesDir . DIRECTORY_SEPARATOR;
        $systemDir = $langsDir . self::systemDir . DIRECTORY_SEPARATOR;
        if (!file_exists($langsDir)) {
            mkdir($langsDir, 0755, true);
        }
        if (!file_exists($systemDir)) {
            mkdir($systemDir, 0755, true);
        }

        foreach (self::$languages as $lang) {
            $ext = self::phpExtension;
            // NEW FILE CREATION
            $copy = self::copyright;
            $userfile = "<?php \n$copy \nreturn array( \n\n";
            foreach ($this->newTranslations[$lang] as $message => &$value) {               
                $translated = $this->messages[$lang][$message];
                if($translated==null) $translated = $this->systemTranslations[$lang][$message][0]; 
                if($translated==null) $translated = "";
                else   $this->newTranslations[$lang][$message][0] = $translated;
               
                $filename = $value[1];
                $linenum = $value[2];
                $del = $value[3];
                $userfile .= "\n/* $filename : line $linenum */\n$del$message$del\n=>\n$del$translated$del\n,";
                $this->systemTranslations[$lang][$message] = $value;
            }
            $userfile .= "\n);";
            

            //BACKUP OLD VALUES

            $systemFile = "<?php \n$copy \nreturn array(";
            foreach ($this->systemTranslations[$lang] as $message => $value) {                
                $translated = $value[0];
                $filename = $value[1];
                $linenum = $value[2];
                $del = $value[3];
                $printdel = addcslashes($value[3],$value[3]);
                $array = "array($del$translated$del,$del$filename$del,$linenum,$del$printdel$del)";
                $systemFile .= "\n$del$message$del=>$array,";
            }
            $systemFile .= "\n);";
            
            $langFileName = "$lang$ext";
            file_put_contents($langsDir . $langFileName, trim($userfile));
            file_put_contents($systemDir . $langFileName, trim($systemFile));
             
        }
    }

    /**
     * Returns the translated message or the message itself if there is no translation for the message. 
     * @param type $message the message to tranlate
     * @return type the translated message.
     */
    public function getMessage($message) {
        if (isset($this->messages[self::$locale][$message]))
            return (empty($this->messages[self::$locale][$message])) ? $message : $this->messages[self::$locale][$message];
        else {
            return $message;
        }
    }

}

/**
 * Prints the translated message or the message itself if there is no translation for the message. 
 * This function can be used both as print() or printf().
 * p("hello world") or p("%s %s", 'hello', 'world').
 * @staticvar null $messages
 * @param type $string the string to tranlates
 */
function p($string){
    static $messages = null;
    if ($messages==null)
        $messages = new i18nMessages();
    if (count(func_get_args()) > 1) {
        $args = func_get_args();
        unset($args[0]);
        vprintf($messages->getMessage($string), $args);
    } else {
        echo $messages->getMessage($string);
    }
}

/**
 * Returns the translated message or the message itself if there is no translation for the message. 
 * This function can be used both as print() or sprintf().
 * $string = g("hello world") or $string = g("%s %s", 'hello', 'world').
 * @staticvar null $messages
 * @param type $string the string to tranlates
 */
function g($string){
    static $messages = null;
    if ($messages==null)
        $messages = new i18nMessages();
    if (count(func_get_args()) > 1) {
        $args = func_get_args();
        unset($args[0]);
        return sprintf($messages->getMessage($string), $args);
    } else {
        return $messages->getMessage($string);
    }
}