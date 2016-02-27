<?php
/**
 * Defautl configuration File for DokuWiki Plugin Yearbox
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Max Westen <max [at] dlmax [dot] org>
 */

$conf['namespace']        = '';       // Set a default namespace to use if not mentioned and not falling back to current.
$conf['font_size']        = 12;       // 12px font size
$conf['page_name']        = 'day';    // a boring default page name
$conf['align']            = '';       // default is centred
$conf['namestructure']    = 0;        // Default naming scheme for new entries 

$conf['newpage']          = false;    // Use the newpagetemplate plugin (https://www.dokuwiki.org/plugin:newpagetemplate)
$conf['np_template']      = ':pagetemplates:journaltemplate';    // The full path to the wiki-entry that will be used as a newpagetemplate template
$conf['np_date']          = true;    // Pass the date as @YBDATE@ parameter to the template, using the newpagetemplate plugin
$conf['np_date_format']   = 0;       // The dateformat used to pass to the @YBDATE@ parameter to the template, using the newpagetemplate plugin

// TODO:
//$conf['default_display']  = 0;        // With no additional display what (default=0 => displays the whole current year)
