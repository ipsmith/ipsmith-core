<?php

class TranslationManager
{
	private $baselanguage = null;
	private $strings = array();
	function __construct($config)
	{
		$this->baselanguage = $config["baselanguage"];
	}

	public function load()
	{

		$result = mysql_query("SHOW COLUMNS FROM language_strings");
		      if (!$result) {
		        echo 'Could not run query: ' . mysql_error();
		      }
		      $fieldnames=array();
		      if (mysql_num_rows($result) > 0) {
		        while ($row = mysql_fetch_assoc($result)) {
			          $fieldnames[] = $row['Field'];
		        }
		      }

		$languages = array();

		foreach($fieldnames as $field)
		{
			if(startsWith($field,"lang_"))
			{	
				$languages[] = str_replace("lang_",null,$field);
			}			
		}

		$q = "SELECT * FROM language_strings";
		$result = mysql_query($q);
		while($row = mysql_fetch_assoc($result))
		{
				$this->strings[$row["languagekey"]] = array();
			foreach($languages as $lang)
			{
				if($row["lang_".$lang]!=null)
				{
				$this->strings[$row["languagekey"]][$lang] = $row["lang_".$lang];
				}
			}	
		}


	}
	public function get($string,$language='de')
	{
		$result = null;
		if(isset($this->strings[$string]))
		{
		if(isset( $this->strings[$string][$language]))
		{
			$result = $this->strings[$string][$language];
		}

		if($result == null)
		{
			$result = $this->strings[$string][$this->baselanguage];
		}

		if($result == null)
		{
			$q = "INSERT INTO translation_strings (languagekey,lang_en) VALUES ('%s','%s')";
			$q = sprintf($q,mysql_real_escape_string($string),"TODO");
			mysql_query($q) or die(mysql_error());
			$this->load();
		}
		}

		if($result == null)
		{
			$result = "Unknown string '".$string."'.";
		}
		
		return $result;

		
	}
}
