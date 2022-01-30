# WP Registrars #

## Introduction ##
In a nutshell the WP Registrars is a small PHP library which allows to register WordPress post types and taxonomies in bulk. Labels are made in plural form and uppercase where required to make development faster.

## Minimum Requirements ##
* **PHP:** 7.4
  - PHP 8.0 and 8.1 are supported
* **WordPress:** Tested up to WP 5.9


## Installation ##
You can install the WP Registrars with composer:

    composer require martinsluters/wpregistrars


## Usage ##

It is relatively easy to use the library, you can use factories `martinsluters\WPRegistrars\Factories\BulkRegistrarPostTypesFactory` and `martinsluters\WPRegistrars\Factories\BulkRegistrarTaxonomiesFactory`
to create  new
`martinsluters\WPRegistrars\Registrars\BulkRegistrarPostTypes` and `martinsluters\WPRegistrars\Registrars\BulkRegistrarTaxonomies` registrar instances.

```php
use martinsluters\WPRegistrars\Factories\{ BulkRegistrarPostTypesFactory, BulkRegistrarTaxonomiesFactory };

( new  BulkRegistrarPostTypesFactory() )
    ->withArguments( [ 'report', 'guide', 'resource' ] )
    ->create()
    ->register();

( new  BulkRegistrarTaxonomiesFactory() )
    ->withArguments(
    	[
		'resource_type' => [ 'object_type' => [ 'resource' ] ]
		'guide_language' => [ 'object_type' => [ 'guide' ] ]
		'my-random-taxonomy'
    	]
    )
    ->create()
    ->register();
```


Note: This documentation will not cover the appropriate place and time in WordPress action execution order to use the library.

Hint: Post type and taxonomy registrations should not be hooked before the [‘init’](https://developer.wordpress.org/reference/hooks/init/) action.

## Usage of factory build methods ##
### withArguments( array  $arguments ) ###
It is mandatory to use this method to register custom post type or custom taxonomy.
At the most minimal configuration it requires an indexed array passed with at least one element with a string type value.
`withArguments( [ 'report' ] )` The string is considered as the post type or taxonomy key. You can pass in theory an unlimited number of elements.

It is possible to pass additional registration arguments. By setting an array element with a string type key (to be considered as post type or taxonomy key) and array type element value. Array passed to the value is what you would normally pass when registering a post type and taxonomy.
`withArguments( [ 'report' => [ 'public' => true ] ] )`

To pass object type(s) when registering a taxonomy use the following
`withArguments( [ 'guide_language' => [ 'object_type' => [ 'guide', 'report' ] ] ] )`

Arguments passed to [withArguments](https://stackedit.io/app#witharguments-array--arguments-) takes over arguments passed to [withDefaultRegistrationArguments](https://stackedit.io/app#withdefaultregistrationarguments-array--default_registration_arguments-).

### withDefaultRegistrationArguments( array  $default_registration_arguments ) ###
It is possible to set default registration arguments that will be applied to all post types or taxonomies.
Array passed is what you would normally pass when registering a post type and taxonomy.

    withDefaultRegistrationArguments( [ 'public' => true ] )

An exception is passing taxonomy registration object type(s). You can pass them along with registration arguments array using the following format

    withDefaultRegistrationArguments( [ 'object_type' => [ 'guide', 'report' ] ] )

Arguments passed to [withArguments](#witharguments-array--arguments-) takes over arguments passed to [withDefaultRegistrationArguments](#withdefaultregistrationarguments-array--default_registration_arguments-).

### shouldAutoPluralize( bool  $should_auto_pluralize_label )
By default labels of post type or taxonomy are made in plural form where it is required using post type or taxonomy key as source. It is possible to disable the feature using `shouldAutoPluralize( false ) `.
If `withDefaultRegistrationArguments` or `withArguments` provides array element `label` then `shouldAutoPluralize` has no effect.

### withPluralizer( PluralizerInterface  $pluralizer ) ###
By default labels of post type and taxonomy are made in plural English form using `Doctrine\Inflector\Inflector` that is a dependency for the library.
It is possible to change the language used by providing a different language using `Doctrine\Inflector\Language`

```php
use martinsluters\WPRegistrars\Factories\BulkRegistrarPostTypesFactory;
use martinsluters\WPRegistrars\PluralizerDoctrineInflectorAdapter;
use Doctrine\Inflector\{ InflectorFactory, Inflector, Language };

$doctrine_inflector_spanish = ( new InflectorFactory( Language::SPANISH ) )
	->create()
	->build();
$pluralizer_spanish = new PluralizerDoctrineInflectorAdapter( $doctrine_inflector_spanish );

( new BulkRegistrarPostTypesFactory() )
	->withPluralizer( $pluralizer_spanish )
	->withDefaultRegistrationArguments( ['public' => true] )
	->withArguments( [ 'escuela' ] )
	->create()
	->register();
```
Note: Please see the official documentation of `Doctrine\Inflector` to find the supported languages.

**You can use a different tool to make words in a plural form and disregard `Doctrine\Inflector` completely.
To do so you must provide to `withPluralizer` an adapter that implements `martinsluters\WPRegistrars\PluralizerInterface`.**


### shouldAutoCapitalize( bool  $should_auto_capitalize_label ) ###

By default labels of post type or taxonomy are made in a way that first character of each word in `string` is capitalized, if that character is alphabetic. The source of label is post type or taxonomy key. It is possible to disable the feature using `shouldAutoCapitalize( false )`.
If `withDefaultRegistrationArguments` or `withArguments` provides array element `label` then `shouldAutoCapitalize` has no effect.

### create() ##
Creates registrar instance if it is possible.


## Usage of registrar methods
### register()
Registers either post types or taxonomies.

## License
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
