<?php

/**
 * Class ModuleRandomNumber
 *
 * @copyright  sr-tag 2016
 * @author     sr-tag Sven Rhinow Webentwicklung <support@sr-tag.de>
 * @package    randomNumber
 */

class ModuleRandomNumber extends \Module
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

        protected $defaultCharValues = array('lowercase' => 1, 'uppercase' => 1, 'numerals' => 1, 'specialchars' => 1);

        protected $defaultCharAnz = 12;

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
		
		// AJAX request
		if (\Environment::get('isAjaxRequest'))
		{
			$this->generateAjax();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
	    global $objPage;

	    $zufallszahl = '';

        $this->Template->action = ($this->m2u_jumpTo)? $this->m2u_jumpTo : $objPage->id;
        $this->Template->inputValues = $this->defaultCharValues;
        $this->Template->inputAnz = $this->defaultCharAnz;

	    if($this->Input->post('FORM_SUBMIT')=='random_number')
	    {
			$anz = \Input::post('anz');
			if((int) $anz < 1) $anz = $this->defaultCharAnz;

			$chars = \Input::post('chars');
			if(!is_array($chars)) $chars = $this->defaultCharValues;

			$gn = $this->generateNumber($chars, $anz);

			$this->Template->inputValues = $chars;
			$this->Template->inputAnz = $anz;

        } else {
        	$gn = $this->generateNumber($this->defaultCharValues, $this->defaultCharAnz);
        }

		$this->Template->randomNumber = $gn;

		$GLOBALS['TL_MOOTOOLS'][] = "
		<script type=\"text/javascript\">

			var doAjax = function(e){
			  e.stop(); // prevent the form from submitting

			  new Request({
			    url: window.location + '?isAjax=1',
			    method: 'post',
			    data: this,
			    onRequest: function(){
			      $('generate_number').fade('out');
			    },
			    onSuccess: function(r){
			      // the 'r' is response from server
			      $('generate_number').set('html', r).fade('in');
			    }
			  }).send();
			}

			addEvent('domready', function(){
			  $('form_random_number').addEvent('submit', doAjax);
			});

			</script>";

		$GLOBALS['TL_JQUERY'][] = "
		<script type=\"text/javascript\">

			jQuery(document).ready(function( $ ) {

				$('#form_random_number').on('submit', function(event){

       				event.preventDefault();

					var form = $(this);
					var action = form.attr('action'),
						method = form.attr('method'),
						data = form.serialize();

					$('#generate_number').fadeOut();

        			$.ajax({
						url: action,
				    	method: method,
				    	data: data,
					}).done(function (data) {
        				// Bei Erfolg
        				$('#generate_number').html(data).fadeIn();
    				});
				});

			});
			</script>";

	}

	/**
	* @param array
	* @param int
	* @return string
	*/
	private function generateNumber($chars,$anz)
	{
		$zufallszahl = '';
		$currArr = array();
		$characters = array
		(
			'binary'  =>   array(0,1),
			'numerals'    =>  range(0,10),
			'uppercase'   =>   range('A','Z'),
			'lowercase'  =>   range("a","z"),
			'specialchars' =>   array("!",".","?", "$", "%", "^", "&")
		);

		if(is_array($chars) && count($chars) > 0)
		{
			foreach($chars as $type => $val)
			{
				if($val == 1) $currArr = array_merge($currArr,$characters[$type]);
			}
		}

		srand((float)microtime() * 1000000);

		shuffle($currArr);
		$arrCount = count($currArr);

		srand($this->make_seed());
		for($i=0;$i<$anz;$i++) $zufallszahl .=$currArr[mt_rand(0, $arrCount-1)];

		return $zufallszahl;
	}

    private function make_seed()
	{
	  list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000);
	}

	public function generateAjax()
	{
	    if(\Input::post('FORM_SUBMIT') == 'random_number')
	    {
			$anz = \Input::post('anz');
			if((int) $anz < 1) $anz = $this->defaultCharAnz;

			$chars = \Input::post('chars');
			if(!is_array($chars)) $chars = $this->defaultCharValues;

			$gn = $this->generateNumber($chars, $anz);

        } else {
        	$gn = $this->generateNumber($this->defaultCharValues, $this->defaultCharAnz);
        }

		print $gn;
		exit();
	}
}
