<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 *
 * This file modifies the data container array of table tl_module.
 *
 * @copyright  Sven Rhinow 2011
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


?>