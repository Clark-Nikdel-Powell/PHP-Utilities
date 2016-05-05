<?php
namespace CNP;

final class Utility {

	/**
	 * getAcfFieldsAsArray.
	 *
	 * Gets a bunch of specific ACF fields at once. Especially useful for option page settings, which only
	 * have a way to get one field at a time.
	 *
	 * @since 0.8.0
	 *
	 * @see get_field
	 * @link https://www.advancedcustomfields.com/resources/get_field/
	 *
	 * @param array $fields_names_arr An array of the fields to get.
	 * @param bool $option Optional. Set to true if the fields are from an options page.
	 *
	 * @return array $data_arr Data array keyed to the field names.
	 */
	public static function getAcfFieldsAsArray( $fields_names_arr, $option ) {

		if ( ! is_array( $fields_names_arr ) || empty( $fields_names_arr ) ) {
			return false;
		}

		$data_arr = [ ];

		$option_arg = '';

		if ( true === $option ) {
			$option_arg = 'option';
		}

		foreach ( $fields_names_arr as $field_name ) {
			$data_arr[ $field_name ] = get_field( $field_name, $option_arg );
		}

		return $data_arr;
	}

	/**
	 * multidimensionalArrayMap.
	 *
	 * Like array_map, but for multidimensional arrays.
	 *
	 * @param $function
	 * @param $array
	 *
	 * @return array
	 */
	public static function multidimensionalArrayMap( $function, $array ) {

		$return = array();

		foreach ( $array as $key => $value ) {

			if ( is_array( $value ) ) {
				$formatted_value = self::MultiDimensionalArrayMap( $function, $value );
			} else {
				$formatted_value = $function( $value );
			}

			$return[ $key ] = $formatted_value;
		}

		return $return;
	}

	/**
	 * parseClassesAsArray.
	 *
	 * Take a string or array of classes, trim them and then return classes as an array.
	 *
	 * @param string|array $classes . An array or comma-delimited string of classes.
	 *
	 * @return array|bool $data_classes_arr|false. Array of trimmed classes, or false if empty.
	 */
	public static function parseClassesAsArray( $classes ) {

		if ( is_string( $classes ) ) {

			if ( '' === $classes ) {
				return false;
			}

			// Create an array
			$data_classes_arr = explode( ',', $classes );

			// Trim the input for any whitespace
			$data_classes_arr = array_map( 'trim', $data_classes_arr );

		}

		if ( is_array( $classes ) ) {

			if ( empty( $classes ) ) {
				return false;
			}

			$data_classes_arr = $classes;
		}

		if ( ! empty( $data_classes_arr ) ) {
			return $data_classes_arr;
		} else {
			return false;
		}
	}

	/**
	 * getSvgIcon.
	 *
	 * Gets an SVG icon. This is geared toward Icomoon, whose icons don't need a viewbox attribute.
	 * The main SVG sprite referenced by the `use` tag is loaded in the head in functions/add_svg_icon_sprite.php.
	 *
	 * @since 1.0.0
	 *
	 * @param string $icon_name The name of the icon
	 * @return string SVG icon markup.
	 */
	public static function getSvgIcon($icon_name) {

		$icon = '<svg class="icon '. $icon_name .'"><use xlink:href="#'. $icon_name .'"></use></svg>';
		return $icon;
	}


	/**
	 * printOnPresent
	 *
	 * A shorthand for checking to see if a string has data or if
	 * an array is not empty. If successful, the function echoes
	 * out markup, either from a string or a function call.
	 *
	 *
	 * @param string|array $string_or_array The variable to check for data.
	 * @param string|function $markup_or_function Markup as a string, or a 'get' function call that returns markup.
	 * @param array $parameters Parameters to pass into anonymous function.
	 *
	 * @return string  Prints out markup if check is successful.
	 **/
	public static function printOnPresent( $string_or_array, $markup_or_function, $parameters = [ ] ) {

		$has_data = false;

		// @EXIT: If an object was supplied, return false.
		if ( is_object( $string_or_array ) ) {
			return false;
		}

		// String check
		if ( is_string( $string_or_array ) ) {

			$string_or_array = trim( $string_or_array );

			if ( '' !== trim( $string_or_array ) ) {
				$has_data = true;
			}
		}

		// Array check
		if ( is_array( $string_or_array ) ) {

			// Trim any whitespace first
			$string_or_array = self::multidimensionalArrayMap( 'trim', $string_or_array );

			if ( ! empty( $string_or_array ) ) {
				$has_data = true;
			}
		}

		// @EXIT: If we don't have any data, exit the function early.
		if ( false === $has_data ) {
			return false;
		}

		// If the second parameter is an anonymous function or a named function, check that
		// the function exists before calling the function
		if ( ( is_object( $markup_or_function ) && $markup_or_function instanceof Closure ) || function_exists( $markup_or_function ) ) {

			call_user_func_array( $markup_or_function, $parameters );

			return true;

		}

		echo $markup_or_function;

		return true;

	}

	public static function &arrayGetPath( &$array, $path, $delimiter = null, $value = null, $unset = false ) {

		$num_args = func_num_args();
		$element  = &$array;

		if ( ! is_array( $path ) && strlen( $delimiter = (string) $delimiter ) ) {
			$path = explode( $delimiter, $path );
		}

		if ( ! is_array( $path ) ) {
			// Exception?
		}

		while ( $path && ( $key = array_shift( $path ) ) ) {

			if ( ! $path && $num_args >= 5 && $unset ) {
				unset( $element[ $key ] );
				unset( $element );
				$element = null;
			} else {
				$element =& $element[ $key ];
			}
		}

		if ( $num_args >= 4 && ! $unset ) {
			$element = $value;
		}

		return $element;
	}

	public static function arraySetPath( $value, &$array, $path, $delimiter = null ) {
		self::arrayGetPath( $array, $path, $delimiter, $value );

		return;
	}

	public static function arrayUnsetPath( &$array, $path, $delimiter = null ) {
		self::arrayGetPath( $array, $path, $delimiter, null, true );

		return;
	}

	public static function arrayHasPath( $array, $path, $delimiter = null ) {
		$has = false;

		if ( ! is_array( $path ) ) {
			$path = explode( $delimiter, $path );
		}

		foreach ( $path as $key ) {

			if ( $has = array_key_exists( $key, $array ) ) {
				$array = $array[ $key ];
			}
		}

		return $has;
	}

	public static function setOrUnset( $string_to_check, $input_array, $key_to_unset, $key_to_set, $backup_string = '' ) {

		$string_to_check = trim( $string_to_check );

		// If the string is empty, we'll either clear out the array key or use a backup (read: default) string.
		if ( '' === $string_to_check ) {

			// If there's a backup, use that. Otherwise, clear out the array key.
			if ( '' !== $backup_string ) {
				self::arraySetPath( $backup_string, $input_array, $key_to_set );
			} else {
				self::arrayUnsetPath( $input_array, $key_to_unset );
			}
			// If the string exists, set it on the specified key.
		} else {
			self::arraySetPath( $string_to_check, $input_array, $key_to_set );
		}

		return $input_array;

	}

}