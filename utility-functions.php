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
	 * @return array Data array keyed to the field names.
	 */
	public function getAcfFieldsAsArray( $fields_names_arr, $option ) {

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
	public function multidimensionalArrayMap( $function, $array ) {

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
	 * @param string|array $classes. An array or comma-delimited string of classes.
	 *
	 * @return array|bool $data_classes_arr|false. Array of trimmed classes, or false if empty.
	 */
	public static function parseClassesAsArray( $classes ) {

		if ( is_string( $classes ) ) {

			// Create an array
			$data_classes_arr = explode( ',', $classes );

			// Trim the input for any whitespace
			$data_classes_arr = array_map( 'trim', $data_classes_arr );

		}

		if ( is_array( $classes ) ) {
			$data_classes_arr = $classes;
		}

		if ( ! empty( $data_classes_arr ) ) {
			return $data_classes_arr;
		} else {
			return false;
		}
	}
}