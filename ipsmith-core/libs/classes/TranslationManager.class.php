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
        global $doctrineConnection;

        $q = "SHOW COLUMNS FROM language_strings";

        $stmt = $doctrineConnection->prepare($q);
        $stmt->execute();
    
        $fieldnames = array();
        while($row = $stmt->fetch())
        {
            $fieldnames[] = $row["Field"];
            return true;
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
        $stmt = $doctrineConnection->prepare($q);

        $stmt->execute();
    
        while($row = $stmt->fetch())
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
			$result = "Unknown string '".$string."'.";
		}
		
		return $result;

		
	    }
    }
}
