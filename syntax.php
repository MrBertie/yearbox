<?php
/**
 * yearbox Plugin: provides a year calendar, with links to a new page for each day
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Symon Bent: <symonbent [at] gmail [dot] com>
 *
 *
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once (DOKU_PLUGIN.'syntax.php');
require_once (DOKU_INC . 'inc/html.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_yearbox extends DokuWiki_Syntax_Plugin {

    /**
     * What kind of syntax is this?
     */
    function getType() { return 'substition'; }
    function getPType() { return 'block'; }

    /**
     * What modes are allowed within this mode?
     */
    function getAllowedTypes() {
        return array('substition','protected','disabled','formatting');
    }

    /**
     * What position in the sort order?
     */
    function getSort(){
        return 125;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      	$this->Lexer->addSpecialPattern('{{yearbox>.*?}}', $mode, 'plugin_yearbox');
    }

    /**
     * Handle the match
     * E.g.: {{yearbox>year=2010;name=journal;size=12;ns=diary}}
     *
     */
    function handle($match, $state, $pos, &$handler) {
        global $INFO;
        $opt = array();

        // default options
        $opt['ns'] = $INFO['namespace'];   // this namespace
        $opt['size'] = 12;                 // 12px font size
        $opt['name'] = 'day';              // a boring default page name
        $opt['year'] = date('Y');          // this year
        $opt['recent'] = false;            // special 1-2 row 'recent pages' view...
        $opt['months'] = array();          // months to be displayed (csv list), e.g. 1,2,3,4... 1=Sun
        $opt['weekdays'] = array();        // weekdays which should have links (csv links)... 1=Jan
        $opt['align'] = '';                // default is centred

		$match = substr($match, 10, -2);
        $args = explode(';', $match);
        foreach ($args as $arg) {
            list($key, $value) = explode('=', $arg);
            switch ($key) {
                case 'year':
                    $opt['year'] = $value;
                    break;
                case 'name':
                    $opt['name'] = $value;
                    break;
                case 'fontsize':
                case 'size':
                    $opt['size'] = $value;
                    break;
                case 'ns':
                    $opt['ns'] = (strpos($value, ':') === false) ?  ':' . $value : $value;
                    break;
                case 'recent':
                    $opt['recent'] = ($value > 0) ? abs($value) : 0;
                    break;
                case 'months':
                    $opt['months'] = explode(',', $value);
                    break;
                case 'weekdays':
                    $opt['weekdays'] = explode(',', $value);
                    break;
                case 'align':
                    if (in_array($value, array('left', 'right'))) {
                        $opt['align'] = $value;
                    }
                    break;
            }
        }
		return $opt;
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $opt) {
        if ($mode == 'xhtml') {
            $renderer->doc .= $this->build_calendar($opt);
            return true;
        }
        return false;
    }

	/**
     * Builds a complete HTML calendar of the year given
     * Provides a link to a page for each day of the year, with a popup abstract of page content
     *
     * $opt = array(
     * @param string $year      build calendar for one year (2011), or range of years (2011,2013)
     * @param string $name      prefix for new page name, e.g diary, journal, day
     * @param int    $size      font size to use
     * @param string $ns        root namespace for new page names
     * @param int    $recent    previous days that must be visible
     * @param array $months    which months are visible (1-12), 1=Jan, 2=Feb, etc
     * @param array $weekdays  which weekdays should have links (1-7), 1=Sun, 2=Mon, etc...
     * }
     * @return string   Complete marked up calendar table
     */
	function build_calendar($opt) {
        global $conf;

        $month_names = $this->getLang('yearbox_months');
		$day_names = $this->getLang('yearbox_days');
		$mth_weekday = array();     // first day of week of month (0-6) 0=Sun
		$mth_length = array();		// length of month in days
		$col_max = 0;
		$cal_start = 6;
		$cal = '';
		$today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $years = explode(',', $opt['year']);

        if ($opt['recent'] > 0) {
            // recent days (matching at least no. of recent days given; shows complete months only)
            $mth_last = (int) date('n');
            $yr_last = (int) date('Y');
            $prev_date = $today - ($opt['recent'] * 12 * 60 * 60);
            $mth_first = (int) date('n', $prev_date);
            $yr_first = (int) date('Y', $prev_date);
            $mth_last += ($yr_last - $yr_first) * 12;
        } elseif (count($years) == 2) {
            // if user provides two years: first -> last (inclusive)
            $mth_first = 1;
            list($yr_first, $yr_last) = $years;
            $mth_last = 12 + ($yr_last - $yr_first) * 12;
        } else {
            // plain old one year calender
            $mth_first = 1;
            $mth_last = 12;
            $yr_first = $yr_last = $opt['year'];
        }

		// first get start day for each month, and length of month,
        // exact no. of columns needed, and the starting day of week
        for ($mth = $mth_first; $mth <= $mth_last; $mth++) {
            // only consider displayed months when calculating column size
            if (empty($opt['months']) || in_array($mth % 12 + 1, $opt['months'])) {
                $year = $yr_first + floor(($mth - 1) / 12); // allow for year overlaps
                $mth_num = ($mth - 1) % 12 + 1;
                $start = date('w', mktime(0, 0, 0, $mth_num, 1, $year));
                $len = date('j', mktime(0, 0, 0, $mth_num + 1, 0, $year));
                $mth_weekday[$mth] = $start;    // weekday in which this month starts
                $mth_length[$mth] = $len;       // length of this month

                // max number of table columns needed (not including col for months!)
                $col_max = ($col_max < ($start + $len)) ? $start + $len : $col_max;
                // find the lowest day of week (i.e. Sun = 0, Mon = 1, etc...)
                // this determines which day of week to begin column headers with
                $cal_start = ($cal_start > $start) ? $start : $cal_start;
            }
        }
        $col_max -= $cal_start;

        $cur_mth = $mth_first;
        $year = $yr_first;

        // basic CSS
        $font_css = ($opt['size'] != 0) ? ' style="font-size:' . $opt['size'] .'px;"' : '';
        if ($opt['align'] == 'left') {
            $align = ' class=left';
        } elseif ($opt['align'] == 'right') {
            $align = ' class=right';
        } else {
            $align = '';
        }
        $cal .= '<div class="yearbox"' . $font_css . '><table' . $align . '><tbody>';

        // year loop
        do {
            $hdr = true;

            // month loop
            do {
                if ( ! $hdr) {
                    $cal .= '<tr>';
                    $mth_start = $mth_weekday[$cur_mth];
                    $mth_len = $mth_length[$cur_mth];
                } else {
                    $cal .= '<tr class="yr-header">';
                }

                $mth_num = ($cur_mth - 1) % 12 + 1;               // current month as number
                $cur_day = 0;
                $done = false;
                $next_yr = false;

                // day loop
                for ($col = 0; $col < $col_max; $col++) {
                    $weekday_num = ($col + $cal_start) % 7;       // current day of week as a number

                    if (($cur_day > 0 && $cur_day < $mth_len) || ($col < 7 && $weekday_num == $mth_start)) {
                        $cur_day++;
                    } else {
                        $cur_day = 0;
                    }

                    $is_weekend = ($weekday_num == 0 || $weekday_num == 6) ? true : false;
                    $day_css = ($is_weekend) ? ' class="wkend"' : '';

                    // add the year and week days abbreviations (the header)
                    if ($hdr) {
                        if ($col == 0) $cal .= '<th class="plain">' . $year . '</th>';
                        $h = $day_names[$weekday_num];
                        $cal .= '<th' . $day_css . '>' . $h . '</th>';

                    // otherwise add a day
                    } else {
                        if ( ! empty($mth_len)) {
                            // insert day name headers into first column of row
                            if ($col == 0) {
                                $alt_css = ($cur_mth % 2 == 0) ? ' class="alt"' : '';
                                $cal .= '<th' . $alt_css . '>' . $month_names[$mth_num - 1] . '</th>';
                            }
                            // add a link to the day's page if we are within this month
                            if ($cur_day > 0 && (empty($opt['weekdays']) || in_array($weekday_num, $opt['weekdays']))) {
                                $day = sprintf("%02d", $cur_day);
                                $month = sprintf("%02d",$mth_num);
                                $id = $opt['ns'] . ':' . $year . '-' . $month . ':' . $opt['name'] .'-' .
                                                        $year . '-' . $month . '-' . $day;
                                $current = mktime(0, 0, 0, $month, $day, $year);
                                if ($current == $today) $day_css = ' class="today"';

                                // swap normal link title (popup) for a more useful preview if page exists
                                if (page_exists($id)) {
                                    $link = $this->_wikilink_preview_popup($id, $day);
                                } else {
                                    $link = html_wikilink($id, $day);
                                    // skip the "do you want to create this page" bit
                                    $sym = ($conf['userewrite']) ? '?' : '&amp;';
                                    $link = preg_replace('/\" class/', $sym . 'do=edit" class', $link, 1);
                                }
                                $cal .= '<td' . $day_css . '>' . $link . '</td>';
                            } else {
                                $cal .= '<td class="blank">&nbsp;&nbsp;</td>';
                            }
                        }
                    }
                }
                $cal .= '</tr>';
                if ($hdr) {
                    $hdr = false;
                    continue;
                }

                $done = ($cur_mth == $mth_last);
                $next_yr = ($mth_num == 12);
                $cur_mth++;
            } while ( ! $done && ! $next_yr);

            $year++;
        } while ( ! $done);

        $cal .= '</tbody></table></div><div class="clearer"></div>';
		return $cal;
	}

    private function _wikilink_preview_popup($id, $name) {
        // swap normal link title (popup) for a more useful preview
        $link = html_wikilink($id, $name);
        $meta = p_get_metadata($id, false, true);
        $abstract = $meta['description']['abstract'] . '... ' . 'Edited: ' . date('Y-M-d',$meta['date']['modified']);
        $preview = str_replace("\n", '  ', $preview);
        $preview = htmlentities($abstract, ENT_QUOTES, 'UTF-8');
        $link = preg_replace('/title=\".+?\"/', 'title="' . $preview . '"', $link, 1);
        return $link;
    }
}