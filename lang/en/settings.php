<?php
/**
 * English settings language file
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Max Westen <max [at] dlmax [dot] org>
 */

$danger_icon = '<img src="'.DOKU_PLUGIN_IMAGES.'danger.png" alt="danger" title="Danger: Changing this option could make your wiki and the configuration menu inaccessible.">';
$warning_icon = '<img src="'.DOKU_PLUGIN_IMAGES.'warning.png" alt="warning" title="Warning: Changing this option could cause unintended behaviour.">';

$lang['_defaults'] = "Defaults";
$lang['_structure'] = "Entry-Structure";
$lang['namespace'] = "The <code>&lt;root&gt;</code> namespace entries will be loaded from and in which ALL new items will be made. <small>ie: <code>:journal</code></small><br>If left empty, it fetches the current root namespace.";
$lang['page_name'] = "The <code>&lt;name&gt;</code> part given to the new entries that will be created.(if left empty, it will be omitted)";

// TODO: default_display
//$lang['default_display'] = "Specifies what is shown when just using the tag <code>{{yearbox>}}</code> without additional parameters.";
$lang['align'] = "The alignment of the calendar on the page.";
$lang['font_size'] = "Default font size to use; this controls the width/height of the calendar table";
$lang['namestructure']  = "The naming scheme used for creating new entries and finding the entries to display." . $warning_icon;

$lang['newpage']          = "You can enable the use of the [[doku>plugin:yearbox]] plugin if it's installed, you can use it to use templates and pass the date to it.";
$lang['np_template']      = "The full path to the template-file (ie: <code>:pagetemplates:journaltemplate</code>)";
$lang['np_date']          = "Pass the date of the selected page as a parameter <code>@YBDATE@</code> to the template?";
$lang['np_date_format']   = "The format that will be used for the <code>@YBDATE@</code> parameter.";

/* align options */
$lang['align_o_left']   = 'Left';
$lang['align_o_']       = 'Centered';
$lang['align_o_right']  = 'Right';

/* namestructure options */
$lang['namestructure_o_0']  = '<root> : year-month : <name>-year-month-day';
$lang['namestructure_o_1']  = '<root> : year : month : <name>-year-month-day';
$lang['namestructure_o_2']  = '<root> : year : month : day';

// TODO: default_display
/* default_display options */
//$lang['default_display_o_0']  = 'the current year (default)';
//$lang['default_display_o_1']  = 'the previous and current month';
//$lang['default_display_o_2']  = 'the 2 previous months and the current month';
//$lang['default_display_o_3']  = 'the previous, current and next month';
//$lang['default_display_o_4']  = 'the 2 previous, the current and the next month';

/* np_date_format options */
$lang['np_date_format_o_0']  = 'year-month-day';
$lang['np_date_format_o_1']  = 'year-day-month';
$lang['np_date_format_o_2']  = 'month-day-year';
$lang['np_date_format_o_3']  = 'day-month-year';


