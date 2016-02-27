<?php
/**
 * Configuration form for DokuWiki Plugin Yearbox.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html) 
 * @author Max Westen <max [at] dlmax [dot] org>
 */

$meta['_defaults']        = array('fieldset');
$meta['font_size']        = array('numeric');
// TODO: default_display
//$meta['default_display']  = array('multichoice', '_choices' => array(0,1,2,3,4));
$meta['align']            = array('multichoice', '_choices' => array('left', '', 'right'));

$meta['_structure']       = array('fieldset');
$meta['namestructure']    = array('multichoice', '_choices' => array(0,1,2));
$meta['namespace']        = array('string');
$meta['page_name']        = array('string');

$meta['newpage']          = array('onoff');
$meta['np_template']      = array('string');
$meta['np_date']          = array('onoff');
$meta['np_date_format']   = array('multichoice', '_choices' => array(0,1,2,3));

