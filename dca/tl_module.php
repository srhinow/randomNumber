<?php
/**
 * @copyright  Sven Rhinow 2016
 * @author     Sven Rhinow <sven@sr-tag.de>
 * @package    randomNumber
 * @license    LGPL
 * @filesource

 */
 
$GLOBALS['TL_DCA']['tl_module']['palettes']['random_number']  = 'name,headline,type,m2u_jumpTo;{expert_legend:hide},space,cssID';


$GLOBALS['TL_DCA']['tl_module']['fields']['m2u_jumpTo'] = array(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['m2u_jumpTo'],
		'exclude'                 => true,
		'inputType'               => 'pageTree',
		'eval'                    => array('fieldType'=>'radio')
	);
