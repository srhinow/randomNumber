<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 */


/**
 * Class ModuleRandomNumber
 *
 * @copyright  sr-tag 2011 
 * @author     sr-tag Sven Rhinow Webentwicklung <support@sr-tag.de>
 * @package    randomNumber 
 */
class ModuleRandomNumber extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_randomnumber';

        /**
        * error files and layer
        * @var bool
        */
        protected $error = false;
        
                
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### RANDOM_NUMBER ###';

			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
	    global $objPage;
	    
	    $inputCharTypes = new FormSelectMenu();
	    $inputCharTypes->id = 'charTypes';
	    $inputCharTypes->name = 'chars';
	    $inputCharTypes->label = 'Art der Zeichen';
	    $inputCharTypes->class = 'select';
	    $inputCharTypes->value = '';
	    $inputCharTypes->options = array( 
		array('value'=>'allnumbig', 'label'=>'Zahlen &amp; Gro&szlig;buchstaben'),
		array('value'=>'allnumtiny','label'=>'Zahlen &amp; Kleinbuchstaben'),
		array('value'=>'allnumbigtiny','label'=>'Zahlen &amp; Gro&szlig;-, Kleinbuchstaben'),
		array('value'=>'allnumspecial','label'=>'Zahlen &amp; Buchstaben &amp; Sonderzeichen (!.! " ? $ % ^ &amp;)'),
		array('value'=>'num','label'=>'nur Zahlen'),
		array('value'=>'bigchar','label'=>'nur Gro&szlig;buchstaben'),
		array('value'=>'tinychar','label'=>'nur Kleinbuchstaben'),
		array('value'=>'binaer','label'=>'Binär (1,0)'),
	    );	    
	    
	    $inputAnz = new FormTextField();
	    $inputAnz->id = 'anz';
	    $inputAnz->name = 'anz';
	    $inputAnz->label = 'Passwortlänge';	    
	    $inputAnz->value = '7';
		 
	    $zufallszahl = 0;
	    
	    if($this->Input->post('FORM_SUBMIT')=='random_number')
	    {
		$anz = $this->Input->post('anz');  
		$inputAnz->validate();			
	      
		//anz auf Fehler pruefen
		if(strlen($inputAnz->getErrorAsString())) $this->error = true;	      
		
		$chars = $this->Input->post('chars');  
		$inputCharTypes->validate();			
		
		//chars auf Fehler pruefen
		if(strlen($inputCharTypes->getErrorAsString())) $this->error = true;
						
		if(!$this->error)
		{		        
		    $BinaerArr  =   array(0,1);
		    $ZahlArr    =   array(1,2,3,4,5,6,7,8,9,0);
		    $AlphaArr   =   array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		    $AlphaArr2  =   array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
		    $specialArr =   array("!",".","?", "$", "%", "^", "&");
		    
		    srand((float)microtime() * 1000000);
		    
		    switch($chars){
			case "num":
			    $aktArr=$ZahlArr;
			break;
			case "bigchar":
			    $aktArr=$AlphaArr;
			break;
		       case "tinychar":
			    $aktArr=$AlphaArr2;
			break;
			case "binaer":
			   $aktArr=$BinaerArr;
			break;
			case "allnumspecial":
			    $aktArr=array_merge($ZahlArr,$AlphaArr,$AlphaArr2,$specialArr);	
			break;    
			case "allnumbigtiny":
			    $aktArr=array_merge($ZahlArr,$AlphaArr,$AlphaArr2);	
			break;
		       case "allnumtiny":
			    $aktArr=array_merge($ZahlArr,$AlphaArr2);	
			break;
			default:
			case "allnumbig":
			    $aktArr=array_merge($ZahlArr,$AlphaArr);	
			break;
		    }  
		      
		    shuffle($aktArr);
		    $ArrCount = count($aktArr);
		    
		    srand($this->make_seed());
		    for($i=0;$i<$anz;$i++) $zufallszahl .=$aktArr[mt_rand(0, $ArrCount-1)];
                    
		}

            }
            $pageId = ($this->m2u_jumpTo)? $this->m2u_jumpTo : $objPage->id;           		
            $this->Template->action = $pageId;
            $this->Template->inputAnz = $inputAnz;			   
	    $this->Template->inputCharTypes = $inputCharTypes;
	    $this->Template->randomNumber = $zufallszahl;
	    
	    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/randomNumber/html/ajax.js';

	    if ($this->Input->get('isAjax') == '1')
	    {   
	      print $zufallszahl;
	      exit; // IMPORTANT!
	    }
	   	  	   
	}
	
        private function make_seed()
	{
	  list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000);
	}
}