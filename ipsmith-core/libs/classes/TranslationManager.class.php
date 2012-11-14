<?php
/**
 * Project:     IPSmith - Free ip address managing tool
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This Software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc., please join the
 * IPSmith mailing list. Go to http://www.ipsmith.org/lists 
 *
 **/

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
