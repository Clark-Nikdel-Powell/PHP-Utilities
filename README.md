# PHP-Utilities
Generic PHP functions for data manipulation.

## Functions

### getAcfFieldsAsArray

Gets a bunch of specific ACF fields at once. Especially useful for option page settings, which only have a way to get one field at a time.

#### Parameters

`$fields_names_arr` (array|required): An array of the field names.

`$option` (boolean|optional): Set to true if the fields are from an options page.

#### Return Values

`$data_arr` (array): Data array keyed to the field names from the input array.

### multidimensionalArrayMap

Like array_map, but for multidimensional arrays.

#### Parameters

`$function` The callback function to apply to each value of the array
 
`$array` The multi-dimensional array

#### Return Values

(array) The formatted array.

### parseClassesAsArray

Take a string or array of classes, trim them and then return classes as an array.

#### Parameters

`$classes` (required): An flat array or comma-delimited string of classes.

#### Return Values

(boolean or array) If the formatted array is empty, returns false. Otherwise, returns array of classes.

### printOnPresent

Like the proverbial weasel, this function pops out markup if data is present. It's a more efficient way of doing an `if ( '' !== $data )` or `if ( !empty( $data ) )` check: 3 lines for the price of one!

If you pass in a function name, the function will be executed. You can use anonymous functions too!

#### Usage

```
<?php CNP\Utility::printOnPresent( $string, '<h1 class="title">'. $string .'</h1>' ); ?>
```

#### Parameters

`$string_or_array` (required): The variable to check for data, which can be a string or an array. Objects are not supported.

`$markup_or_function` (required): What to do with the data if it is present. You can supply a string of markup, or call a function for more complex scenarios.

#### Return Values

(boolean or string) If the data check fails, `printOnPresent` returns false. If the data check is successful, then `printOnPresent` echoes the markup or function output from the `$markup_or_function` variable.
