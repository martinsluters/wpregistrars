<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\ArgumentSets;

use martinsluters\WPRegistrars\ArgumentSets\TaxonomyRegistrationArgumentSet;
use WP_Mock;

/**
 * Tests of TaxonomyRegistrationArgumentSet.
 */
class TaxonomyRegistrationArgumentSetTest extends AbstractWPTaxonomyPostTypeArgumentSetTestCase {

	/**
	 * Make sure that registration argument set is valid
	 *
	 * @return void
	 */
	public function testArgumentSetIsValidIfKeyIsStringAndArgumentsIsArrayAndPbjectTypeIsArray(): void {
		$taxonomy_key = 'human_type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertTrue( $registration_argument_set->isArgumentSetValid() );
	}

	/**
	 * Make sure that registration argument array element keyed with 'label' is appended to registration argument
	 * list if:
	 *  - registration argument array element keyed with 'label' is NOT user provided already
	 *
	 * @return void
	 */
	public function testLabelCreatedIfLabelNotUserProvided(): void {
		$taxonomy_key = 'human_type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( false ); // Turn off auto capitalization for the sake of safe test.
		$this->assertArrayHasKey( 'label', $registration_argument_set->getArguments() );
	}

	/**
	 * Make sure that registration argument array element keyed with 'label' is not changed
	 * if:
	 *  - registration argument array element keyed with 'label' is user provided already
	 *
	 * @return void
	 */
	public function testLabelNotChangedIfLabelUserProvided(): void {
		$taxonomy_key = 'human_type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [ 'label' => 'Humanoid Categories' ];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( false ); // Turn off auto capitalization for the sake of safe test.
		$this->assertArrayHasKey( 'label', $registration_argument_set->getArguments() );
		$this->assertSame( 'Humanoid Categories', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that pluralizer's 'pluralize' method is called and with argument provided to it is
	 * equal to post type key. Registration argument element value with key 'label' must be equal to
	 * whatever pluralizer returned if:
	 * 1) pluralizer instance is available at all
	 * 2) registration argument array element keyed with 'label' is NOT user provided already
	 *
	 * @return void
	 */
	public function testLabelGetsPluralized(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( false ); // Turn off auto capitalization for the sake of safe test.
		$registration_argument_set->setPluralizer( $this->pluralizer_mock );
		$this->pluralizer_mock->shouldReceive( 'pluralize' )
			->once()
			->with( $taxonomy_key )
			->andReturn( 'types' ); // <- Can be anything as long as the test pass. Don't care how pluralizer works.

		$this->assertSame( 'types', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that pluralizer's 'pluralize' method is called if
	 * user provided registration arguments but they did not include 'label' array element.
	 *
	 * @return void
	 */
	public function testLabelGetsPluralizedIfUserProvidedArgumentsNotIncludeLabel(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( false ); // Turn off auto capitalization for the sake of safe test.
		$registration_argument_set->setPluralizer( $this->pluralizer_mock );
		$this->pluralizer_mock->shouldReceive( 'pluralize' )
			->once()
			->with( $taxonomy_key )
			->andReturn( 'orks' ); // <- Can be anything as long as the test pass. Don't care how pluralizer works.

		$this->assertSame( 'orks', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that pluralizer's 'pluralize' method is not called if
	 * pluralizer instance is not available:
	 *
	 * @return void
	 */
	public function testPluralizeShouldNotBeCalledIfPluralizerNotAvailable(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->pluralizer_mock->shouldNotReceive( 'pluralize' );
		$this->assertIsArray( $registration_argument_set->getArguments() );
	}

	/**
	 * Make sure that getter of taxonomy_key returns the same value that was set in constructor.
	 *
	 * @return void
	 */
	public function testTaxonomyKeyGetterMethodReturnsTaxonomyKey(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertSame( $taxonomy_key, $registration_argument_set->getKey() );
	}

	/**
	 * Make sure that getter of taxonomy registration arguments returns the same value that was set in constructor merged with default arguments.
	 *
	 * @return void
	 */
	public function testArgumentsGetterMethodReturnsUserArgumentsAndDefaultArguments(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [ 'label' => 'Categories' ];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertSame( [], $registration_argument_set->getDefaultArguments() );
		$this->assertEqualSetsWithIndex( [ 'label' => 'Categories' ], $registration_argument_set->getArguments() );
		$registration_argument_set->setDefaultArguments( [ 'public' => true ] );
		$this->assertEqualSetsWithIndex(
			[
				'label' => 'Categories',
				'public' => true,
			],
			$registration_argument_set->getArguments()
		);
	}

	/**
	 * Make sure that user defined registration arguments takes over default registration arguments
	 *
	 * @return void
	 */
	public function testUserDefinedArgumentTakesOverDefaultArgument(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [
			'label' => 'Categories',
			'show_in_rest' => false,
		];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );

		$this->assertEqualSetsWithIndex(
			[
				'label' => 'Categories',
				'show_in_rest' => false,
			],
			$registration_argument_set->getArguments()
		);

		$registration_argument_set->setDefaultArguments( [ 'label' => 'Ork' ] );
		$this->assertEqualSetsWithIndex(
			[
				'label' => 'Categories',
				'show_in_rest' => false,
			],
			$registration_argument_set->getArguments()
		);
	}

	/**
	 * Make sure that default registration arguments exist and is an empty array by default
	 *
	 * @return void
	 */
	public function testDefaultArgumentsReturned(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [ 'label' => 'Categories' ];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertSame( [], $registration_argument_set->getDefaultArguments() );
	}

	/**
	 * Make sure that it is possible to overwrite default registration arguments
	 *
	 * @return void
	 */
	public function testIsPossibleToSetNewDefaultArguments(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [ 'label' => 'Types' ];

		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertNotSame( [ 'show_in_rest' => false ], $registration_argument_set->getDefaultArguments() );

		$registration_argument_set->setDefaultArguments( [ 'show_in_rest' => false ] );
		$this->assertSame( [ 'show_in_rest' => false ], $registration_argument_set->getDefaultArguments() );
	}

	/**
	 * Make sure that registration arguments element 'label' is auto capitalized by default
	 * if user has not provided registration arguments element 'label' already.
	 *
	 * @return void
	 */
	public function testLabelIsAutoCapitalizedByDefault(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertSame( 'Type', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that registration arguments element 'label' is not capitalized
	 * if user provided registration arguments element 'label' already.
	 *
	 * @return void
	 */
	public function testLabelShouldNotBeAutoCapitalizedIfUserProvidedLabel(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [ 'label' => 'category' ]; // Deliberately lowercase.
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertSame( 'category', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that it is possible to toggle registration
	 * arguments element 'label' value's auto capitalization.
	 *
	 * @return void
	 */
	public function testCanToggleAutoCapitalizeLabel(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );

		$registration_argument_set->setDoAutoCapitalize( false );
		$this->assertSame( 'type', $registration_argument_set->getArguments()['label'] );

		$registration_argument_set->setDoAutoCapitalize( true );
		$this->assertSame( 'Type', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that registration arguments element 'label' value is capitalized if
	 * user provided registration arguments but they did not include 'label' array element.
	 *
	 * @return void
	 */
	public function testLabelShouldBeAutoCapitalizedIfUserProvidedArgumentsDoNotIncludeLabel(): void {
		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [ 'show_in_rest' => true ];

		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertSame( 'Type', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that registration arguments element 'label' value consists of two words if key contains underscore or hyphen.
	 *
	 * @return void
	 */
	public function testLabelShouldContainsTwoWordsIfKeyContainsUnderscoreOrHyphen(): void {
		$taxonomy_key = 'class_spell';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [ 'show_in_rest' => true ];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( false );
		$this->assertSame( 'class spell', $registration_argument_set->getArguments()['label'] );

		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( true );
		$this->assertSame( 'Class Spell', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that default registration arguments are run through a WordPress filter.
	 *
	 * @return void
	 */
	public function testDefaultArgumentsRunThroughWPFilter(): void {
		WP_Mock::onFilter( 'wpregistrars/taxonomy/default_arguments' )
			->with( [], 'type' )
			->reply( [ 'description' => 'Humans and Orks might have some spells' ] );

		$taxonomy_key = 'type';
		$object_type = [ 'human', 'ork' ];
		$taxonomy_registration_arguments = [];
		$registration_argument_set = new TaxonomyRegistrationArgumentSet( $taxonomy_key, $object_type, $taxonomy_registration_arguments );
		$this->assertSame( 'Humans and Orks might have some spells', $registration_argument_set->getArguments()['description'] );

		$registration_argument_set->setDefaultArguments( [ 'show_in_rest' => false ] );
		$this->assertFalse( $registration_argument_set->getArguments()['show_in_rest'] );

		WP_Mock::onFilter( 'wpregistrars/taxonomy/default_arguments' )
			->with( [ 'show_in_rest' => false ], 'type' )
			->reply( [ 'show_in_rest' => true ] );
		$this->assertTrue( $registration_argument_set->getArguments()['show_in_rest'] );
	}
}
