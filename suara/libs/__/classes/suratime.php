<?php
class SuraTime {

	public static $niceFormat = '%a, %b %eS %Y, %H:%M';
	public static $niceShortFormat = '%B %d, %H:%M';

	public static function convert($timestamp, $timezone) {

	}

	public static function fromString($dateString, $timezone = null) {
		if (empty($dateString)) {
			return false;
		}

		if (is_int($dateString) || is_numeric($dateString)) {
			$date = intval($dateString);
		} elseif ($dateString instanceof DateTime && $dateString->getTimezone()->getName() != date_default_timezone_get()) {
			$clone = clone $dateString;
			$clone->setTimezone(new DateTimeZone(date_default_timezone_get()));
			$date = (int)$clone->format('U') + $clone->getOffset();
		} elseif ($dateString instanceof DateTime) {
			$date = (int)$dateString->format('U');
		} else {
			$date = strtotime($dateString);
		}

		if ($date === -1 || empty($date)) {
			return false;
		}

		//if ($timezone === null) {
		//    $timezone = 
		//}

		//if ($dateString !== null) {
		//    return self::convert($date, $timezone);
		//}

		return $date;
	}

	/**
	 * 是否是今天？
	 */
	public static function isToday($dateString, $timezone = null) {
		$timestamp = self::fromString($dateString, $timezone);

		return date('Y-m-d', $timestamp) == date('Y-m-d', time());
	}

	/**
	 * 是否是明日
	 */
	public static function isTomorrow($dateString, $timezone = null) {
		$timestamp = self::fromString($dateString, $timezone);

		return date('Y-m-d', $timestamp) == date('Y-m-d', strtotime('tomorrow'));
	}

	/**
	 * 是否是后天
	 */
	public static function isBermorgen($dateString, $timezone = null) {
		$timestamp = self::fromString($dateString, $timezone);

		return date('Y-m-d', $timestamp) == date('Y-m-d', strtotime('+2 day'));
	}

	public static function nice($dateString = null, $timezone = null, $format = null) {
		if (!$dateString) {
			$dateString = time();
		}
		$date = self::fromString($dateString, $timezone);

		if (!$format) {
			$format = self::$niceFormat;
		}

		return date($format, $date);
	}

	public static function niceShort($dateString = null, $timezone = null) {
		if (!$dateString) {
			$dateString = time();
		}

		$date = self::fromString($dateString, $timezone);

	}

	public static function format($date, $format = null, $default = false, $timezone = null) {
		$time = self::fromString($format, $timezone);
		if ($time === false) {
			//TODO
		}

		return date($date, $time);
	}
}

?>
