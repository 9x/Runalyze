<?php
/**
 * This file contains the class::HTML for easy html-manipulation.
 */

/**
 * New line for echoing text
 * @var string
 */
define('NL', "\n");

/**
 * HTML-tag: break
 * @var string
 */
define('BR', '<br />');

/**
 * Class: HTML
 * 
 * @author Hannes Christiansen <mail@laufhannes.de>
 * @version 1.0
 * @uses class::Error
 * @uses class::Mysql
 */
class HTML {
	/**
	 * MultiIndex for "multi[value][index]" instead of "value"
	 * @var mixed
	 */
	private static $MultiIndex = false;

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Destructor
	 */
	public function __destruct() {}

	/**
	 * Set internal MultiIndex
	 * @param int $index
	 */
	public static function setMultiIndex($index) {
		self::$MultiIndex = $index;
	}

	/**
	 * Encode opening and ending tag-character
	 * @param string $string
	 * @return string
	 */
	public static function encodeTags($string) {
		return str_replace(array('<', '>'), array('&lt;', '&gt;'), $string);
	}

	/**
	 * Return an empty td-Tag
	 * @param int $colspan
	 * @param string $string
	 * @return string
	 */
	public static function emptyTD($colspan = 0, $string = '&nbsp;') {
		$colspan = ($colspan > 0) ? ' colspan="'.$colspan.'"' : '';

		return '<td'.$colspan.'>'.$string.'</td>'.NL;
	}

	/**
	 * Get a tr-tag for a bold header-line containing all month-names
	 * @param int $fixedWidth Fixed width for every month-td in percent [set '0' for no fixed width]
	 * @param int $emptyTDs Number of empty td before the month-td
	 * @return string
	 */
	public static function monthTR($fixedWidth = 0, $emptyTDs = 1) {
		$width = ($fixedWidth > 0) ? ' width="'.$fixedWidth.'%"' : '';
		$html = '<tr class="b">'.NL;

		for ($i = 1; $i <= $emptyTDs; $i++)
			$html .= '<td />'.NL;

		for ($m = 1; $m <= 12; $m++)
			$html .= '<td'.$width.'>'.Helper::Month($m, true).'</td>'.NL;

		$html .= '</tr>'.NL;

		return $html;
	}

	/**
	 * Get a tr-tag for a bold header-line containing all years
	 * @param int $fixedWidth Fixed width for every year-td in percent [set '0' for no fixed width]
	 * @param int $emptyTDs Number of empty td before the year-td
	 * @return string
	 */
	public static function yearTR($fixedWidth = 0, $emptyTDs = 1) {
		$width = ($fixedWidth > 0) ? ' width="'.$fixedWidth.'%"' : '';
		$html = '<tr class="b">'.NL;

		for ($i = 1; $i <= $emptyTDs; $i++)
			$html .= '<td />'.NL;

		for ($y = START_YEAR; $y <= date("Y"); $y++)
			$html .= '<td'.$width.'>'.$y.'</td>'.NL;

		$html .= '</tr>'.NL;

		return $html;
	}

	/**
	 * Get a tr-tag for a space-line
	 * @param int $colspan
	 */
	public static function spaceTR($colspan) {
		return '
			<tr class="space">
				<td colspan="'.$colspan.'">
				</td>
			</tr>'.NL;
	}

	/**
	 * Return a break
	 * @return string
	 */
	public static function br() {
		return '<br />';
	}

	/**
	 * Return a break with class="clear"
	 * @return string
	 */
	public static function clearBreak() {
		return '<br class="clear" />';
	}

	/**
	 * Wrap value in span for plus/minus
	 * @param float $value
	 * @param float $ignorance
	 * @return string
	 */
	public static function plusMinus($value, $ignorance = 0) {
		if ($value > +$ignorance)
			return '<span class="plus">'.$value.'</span>';
		if ($value < -$ignorance)
			return '<span class="minus">'.$value.'</span>';

		return $value;
	}

	/**
	 * Wrap a string as span
	 * @param string $string
	 */
	public static function left($string) {
		return '<span class="left">'.$string.'</span>';
	}

	/**
	 * Wrap a string as span
	 * @param string $string
	 */
	public static function right($string) {
		return '<span class="right">'.$string.'</span>';
	}

	/**
	 * Wrap a string into emphasize-tag
	 * @param string $string
	 */
	public static function em($string) {
		return '<em>'.$string.'</em>';
	}

	/**
	 * Wrap a string into p-tag with class="info"
	 * @param string $string
	 */
	public static function info($string) {
		return '<p class="info">'.$string.'</p>';
	}

	/**
	 * Wrap a string into p-tag with class="error"
	 * @param string $string
	 */
	public static function error($string) {
		return '<p class="error">'.$string.'</p>';
	}

	/**
	 * Replace ampersands for a textarea
	 * @param string $text
	 * @return string
	 */
	public static function textareaTransform($text) {
		return stripslashes(str_replace("&", "&amp;", $text));
	}

	/**
	 * Transform html-code
	 * @param string $code
	 * @return string
	 */
	public static function codeTransform($code) {
		return str_replace(array('<','>'), array('&lt;','&gt;'), $code);
	}

	/**
	 * Transform given name if MultiIndex is in use
	 * @param string $name
	 */
	private static function transformNameForMultiIndex($name) {
		if (self::$MultiIndex == false)
			return $name;

		$parts = explode('[', $name);

		if (count($parts) == 2)
			return 'multi['.self::$MultiIndex.']['.$parts[0].']['.$parts[1];

		return 'multi['.self::$MultiIndex.']['.$name.']';
	}

	/**
	 * Get html-tag for a textarea
	 * @param string $name
	 * @param int $cols
	 * @param int $rows
	 * @param string $value if not set, uses post-data as value
	 */
	public static function textarea($name, $cols = 70, $rows = 3, $value = '') {
		if ($value == '' && isset($_POST[$name]))
			$value = $_POST[$name];

		$name = self::transformNameForMultiIndex($name);

		return '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$value.'</textarea>'.NL;
		
	}

	/**
	 * Get a simple input field, filled with post-data for this name
	 * @param string $name name for this field
	 * @param int $size size for this input
	 * @param string $default default value if $_POST[$name] is not set
	 * @return string
	 */
	public static function simpleInputField($name, $size = 20, $default = '') {
		$value = isset($_POST[$name]) ? $_POST[$name] : $default;
		$value = self::textareaTransform($value);

		$name = self::transformNameForMultiIndex($name);

		return '<input type="text" name="'.$name.'" size="'.$size.'" value="'.$value.'" />';
	}

	/**
	 * Get a disabled input field, filled with post-data for this name
	 * @param string $name name for this field
	 * @param int $size size for this input
	 * @param string $default default value if $_POST[$name] is not set
	 * @return string
	 */
	public static function disabledInputField($name, $size = 20, $default = '') {
		$value = isset($_POST[$name]) ? $_POST[$name] : $default;
		$value = self::textareaTransform($value);

		$name = self::transformNameForMultiIndex($name);

		return '<input type="text" name="'.$name.'" size="'.$size.'" value="'.$value.'" disabled="disabled" />';
	}

	/**
	 * Get a hidden input field, filled with post-data for this name
	 * @param string $name name for this field
	 * @param string $value value, if empty uses post-data
	 * @return string
	 */
	public static function hiddenInput($name, $value = '') {
		if ($value == '' && isset($_POST[$name]))
			$value = $_POST[$name];

		$name = self::transformNameForMultiIndex($name);

		return '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
	}

	/**
	 * Get a select box with given options
	 * @param string $name Name for this select-box
	 * @param mixed $checked [optional] bool for beeing checked or not, otherwise checks for post-data
	 * @param bool $noHiddenSent
	 * @return string
	 */
	public static function checkBox($name, $checked = -1, $noHiddenSent = false) {
		if ($checked === -1)
			$checked = isset($_POST[$name]) && $_POST[$name] != 0;

		$key = self::transformNameForMultiIndex($name);
		$hiddenSent = self::hiddenInput($name.'_sent','true');

		return (!$noHiddenSent ? $hiddenSent : '').'<input type="checkbox" name="'.$key.'"'.self::Checked($checked).' />';
	}

	/**
	 * Get ' checked="checked"' if boolean value is true
	 * @param bool $value
	 * @param mixed $value_to_be_checked [optional]
	 * @return string
	 */
	public static function Checked($value, $value_to_be_checked = NULL) {
		if ($value_to_be_checked != NULL)
			$value = ($value == $value_to_be_checked);
		if ($value == NULL || !isset($value))
			$value = false;

		return ($value === true)
			? ' checked="checked"'
			: '';
	}

	/**
	 * Get a select box with given options
	 * @param string $name Name for this select-box
	 * @param array $options Array containing values as indices, displayed text as values
	 * @param mixed $selected Value to be selected
	 * @return string
	 */
	public static function selectBox($name, $options, $selected = -1) {
		if ($selected == -1 && isset($_POST[$name]))
			$selected = $_POST[$name];

		$name = self::transformNameForMultiIndex($name);

		$html = '<select name="'.$name.'">'.NL;

		foreach ($options as $value => $text)
			$html .= '<option value="'.$value.'"'.self::Selected($value, $selected).'>'.$text.'</option>'.NL;

		return $html.'</select>'.NL;
	}

	/**
	 * Get ' selected="selected"' if boolean value is true
	 * @param bool $value
	 * @param mixed $value_to_be_checked [optional]
	 * @return string
	 */
	public static function Selected($value, $value_to_be_checked = NULL) {
		if ($value_to_be_checked != NULL)
			$value = ($value == $value_to_be_checked);

		return ($value === true)
			? ' selected="selected"'
			: '';
	}

	/**
	 * Wrap given image-tag in a div-tag for showing a background-image for loading
	 * @param string $img complete <img>-tag
	 * @param int $width [px]
	 * @param int $height [px]
	 * @param string $addClass [optional] string added to class-attribute
	 * @return string
	 */
	public static function wrapImgForLoading($img, $width, $height, $addClass = '') {
		if (!empty($addClass))
			$addClass = ' '.$addClass;

		return '<div class="bigImg'.$addClass.'" style="width:'.$width.'px;height:'.$height.'px;">'.$img.'</div>'.NL;
	}

	/**
	 * Get class for table-row depending on index
	 * @param int $i
	 * @param string $style style-class for this row, look at CSS-file for more information
	 * @return string eg. 'a1'/'a2'
	 */
	public static function trClass($i, $style = 'a') {
		return $style.($i%2 == 0 ? '1' : '2');
	}

	/**
	 * Get class for table-row depending on index
	 * @param int $i
	 * @param string $style style-class for this row, look at CSS-file for more information
	 * @return string eg. 'a2'/'a3'
	 */
	public static function trClass2($i, $style = 'a') {
		return $style.($i%2 == 0 ? '2' : '3');
	}

	/**
	 * Check if currently used browser is IE
	 * @return bool
	 */
	public static function isInternetExplorer() {
		return (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false);
	}
}
?>