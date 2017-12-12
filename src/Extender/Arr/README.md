# Array Extenders

Working with PHP arrays and especially deeply nested arrays can be tedious.  This extender module provides some handy helpers to reduce your code and make it incredibly easy to get the data you need.

## Dot Notation

Deeply nested arrays can be tedious to work with, as you need to add, remove, and access the different levels.  Dot Notation gives you a simplified approach to filter down to where you need to work with the subject array. 

The syntax is:
    `keyLevel1.keyLevel2.keyLevel3`
    
Notice that each key is separated by a dot (.).  

Let's say you are working with a user's dataset and you need to get this person's twitter handle out of the array.  You would do:  `array_get( $user, 'social.twitter' )`;
    
```
$user = array(
	'user_id'   => 504,
	'name'      => 'Bob Jones',
	'social'    => array(
		'twitter' => '@bobjones',
	),
	'languages' => array(
		'php'        => array(
			'procedural' => true,
			'oop'        => false,
		),
		'javascript' => true,
		'ruby'       => false,
	),
);
```
What gets returned then is `'@bobjones'`;
  
>Credit: Taylor Otwell brought us dot notation in the [Laravel framework](https://laravel.com/).  Using his concept, Fulcrum adapts it to fit WordPress running on PHP 5.6 and up.

### API Helpers

Each of these API helpers is documented in the [Wiki](https://github.com/wpfulcrum/extender/wiki/Array-API-Functionality).

<sup>*</sup> indicates this function works with dot notation.

[`array_add`](https://github.com/wpfulcrum/extender/wiki/array_add)<sup>*</sup>

[`array_exists`](https://github.com/wpfulcrum/extender/wiki/array_exists)

[`array_flatten`](https://github.com/wpfulcrum/extender/wiki/array_flatten)

[`array_flatten_into_delimited_list`](https://github.com/wpfulcrum/extender/wiki/array_flatten_into_delimited_list)
 
[`array_flatten_into_dots`](https://github.com/wpfulcrum/extender/wiki/array_flatten_into_dots)<sup>*</sup>

[`array_has`](https://github.com/wpfulcrum/extender/wiki/array_has)<sup>*</sup>

[`array_prepend`](https://github.com/wpfulcrum/extender/wiki/array_prepend)

[`array_remove`](https://github.com/wpfulcrum/extender/wiki/array_remove)<sup>*</sup>

[`array_set`](https://github.com/wpfulcrum/extender/wiki/array_set)<sup>*</sup>

#### Selection Functions

These functions allow you to select the subset of elements you want from the subject array:

[`array_get`](https://github.com/wpfulcrum/extender/wiki/array_get)<sup>*</sup>

[`array_get_except`](https://github.com/wpfulcrum/extender/wiki/array_get_except)<sup>*</sup>

[`array_filter_with_keys`](https://github.com/wpfulcrum/extender/wiki/array_filter_with_keys)

[`array_get_first_element`](https://github.com/wpfulcrum/extender/wiki/array_get_first_element)

[`array_get_last_element`](https://github.com/wpfulcrum/extender/wiki/array_get_last_element)

[`array_get_first_match`](https://github.com/wpfulcrum/extender/wiki/array_get_first_match)

[`array_get_last_match`](https://github.com/wpfulcrum/extender/wiki/array_get_last_match)

[`array_get_only`](https://github.com/wpfulcrum/extender/wiki/array_get_only)<sup>*</sup>

[`array_pluck`](https://github.com/wpfulcrum/extender/wiki/array_pluck)<sup>*</sup>

[`array_pull`](https://github.com/wpfulcrum/extender/wiki/array_pull)<sup>*</sup>

#### Escaping Functions

[`array_esc_attr`](https://github.com/wpfulcrum/extender/wiki/array_esc_attr)

[`array_strip_tags`](https://github.com/wpfulcrum/extender/wiki/array_strip_tags)

[`array_trim`](https://github.com/wpfulcrum/extender/wiki/array_trim)
