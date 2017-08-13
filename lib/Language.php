<?php

class Language {

	private $UserLng;
	private $langSelected;
	public $lang = array();


	public function __construct($userLanguage){

        if ($userLanguage == 1) {
            $this->UserLng = "heb";
        } elseif ($userLanguage == 2) {
            $this->UserLng = "en";
        } elseif ($userLanguage == 3) {
            $this->UserLng = "ci";
        }else{
            $this->UserLng = "en";
        }
        //construct lang file
		$langFile = DIRECTORY.'/lang/'. $this->UserLng . '.ini';
		if(!file_exists($langFile)){
			$langFile = DIRECTORY.'/lang/en.ini';
//			throw new Exception("Language could not be loaded"); //or default to a language
		}

		$this->lang = parse_ini_file($langFile);
	}

	public function userLanguage(){
		return $this->lang;
	}

}