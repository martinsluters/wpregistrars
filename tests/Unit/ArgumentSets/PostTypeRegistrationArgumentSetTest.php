<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\ArgumentSets;

use martinsluters\WPRegistrars\ArgumentSets\PostTypeRegistrationArgumentSet;
use WP_Mock;

/**
 * Tests of PostTypeRegistrationArgumentSet.
 */
class PostTypeRegistrationArgumentSetTest extends AbstractWPTaxonomyPostTypeArgumentSetTestCase {

	/**
	 * Make sure that registration argument set is valid
	 *
	 * @return void
	 */
	public function testArgumentSetIsValidIfKeyIsStringAndArgumentsIsArray(): void {
		$post_type_key = 'human';
		$post_type_registration_arguments = [ 'label' => 'Humans' ];
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
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
		$post_type_key = 'ork';
		$post_type_registration_arguments = [];
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
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
		$post_type_key = 'ork';
		$post_type_registration_arguments = [ 'label' => 'Creatures' ];
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( false ); // Turn off auto capitalization for the sake of safe test.
		$this->assertArrayHasKey( 'label', $registration_argument_set->getArguments() );
		$this->assertSame( 'Creatures', $registration_argument_set->getArguments()['label'] );
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
		$post_type_key = 'ork';
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, [] );
		$registration_argument_set->setDoAutoCapitalize( false ); // Turn off auto capitalization for the sake of safe test.
		$registration_argument_set->setPluralizer( $this->pluralizer_mock );
		$this->pluralizer_mock->shouldReceive( 'pluralize' )
			->once()
			->with( $post_type_key )
			->andReturn( 'orks' ); // <- Can be anything as long as the test pass. Don't care how pluralizer works.

		$this->assertSame( 'orks', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that pluralizer's 'pluralize' method is called if
	 * user provided registration arguments but they did not include 'label' array element.
	 *
	 * @return void
	 */
	public function testLabelGetsPluralizedIfUserProvidedArgumentsNotIncludeLabel(): void {
		$post_type_key = 'ork';
		$post_type_registration_arguments = [ 'show_in_rest' => true ];
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
		$registration_argument_set->setDoAutoCapitalize( false ); // Turn off auto capitalization for the sake of safe test.
		$registration_argument_set->setPluralizer( $this->pluralizer_mock );
		$this->pluralizer_mock->shouldReceive( 'pluralize' )
			->once()
			->with( $post_type_key )
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
		$registration_argument_set = new PostTypeRegistrationArgumentSet( 'ork', [] );
		$this->pluralizer_mock->shouldNotReceive( 'pluralize' );
		$this->assertIsArray( $registration_argument_set->getArguments() );
	}

	/**
	 * Make sure that getter of taxonomy_key returns the same value that was set in constructor.
	 *
	 * @return void
	 */
	public function testPostTypeKeyGetterMethodReturnsPostTypeKey(): void {
		$post_type_key = 'human';
		$post_type_registration_arguments = [ 'label' => 'Humans' ];
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
		$this->assertSame( $post_type_key, $registration_argument_set->getKey() );
	}

	/**
	 * Make sure that getter of post type registration arguments returns the same value that was set in constructor merged with default arguments.
	 *
	 * @return void
	 */
	public function testArgumentsGetterMethodReturnsUserArgumentsAndDefaultArguments(): void {
		$registration_argument_set = new PostTypeRegistrationArgumentSet( 'human', [ 'label' => 'Humans' ] );
		$this->assertSame( [], $registration_argument_set->getDefaultArguments() );
		$this->assertEqualSetsWithIndex( [ 'label' => 'Humans' ], $registration_argument_set->getArguments() );
		$registration_argument_set->setDefaultArguments( [ 'public' => true ] );
		$this->assertEqualSetsWithIndex(
			[
				'label' => 'Humans',
				'public' => true,
			],
			$registration_argument_set->getArguments()
		);
	}

	/**
	 * Make sure that user defined registration arguments takes over default registration arguments.
	 *
	 * @return void
	 */
	public function testUserDefinedArgumentTakesOverDefaultArgument(): void {
		$registration_argument_set = new PostTypeRegistrationArgumentSet(
			'human',
			[
				'label' => 'Humans',
				'show_in_rest' => false,
			]
		);
		$this->assertSame( [], $registration_argument_set->getDefaultArguments() );
		$this->assertEqualSetsWithIndex(
			[
				'label' => 'Humans',
				'show_in_rest' => false,
			],
			$registration_argument_set->getArguments()
		);
		$registration_argument_set->setDefaultArguments( [ 'label' => 'Ork' ] );
		$this->assertEqualSetsWithIndex(
			[
				'label' => 'Humans',
				'show_in_rest' => false,
			],
			$registration_argument_set->getArguments()
		);
	}

	/**
	 * Make sure that default registration arguments exist and is an empty array by default.
	 *
	 * @return void
	 */
	public function testDefaultArgumentsReturned(): void {
		$post_type_key = 'human';
		$post_type_registration_arguments = [ 'label' => 'Humans' ];
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
		$this->assertSame( [], $registration_argument_set->getDefaultArguments() );
	}

	/**
	 * Make sure that it is possible to overwrite default registration arguments.
	 *
	 * @return void
	 */
	public function testIsPossibleToSetNewDefaultArguments(): void {
		$post_type_key = 'human';
		$post_type_registration_arguments = [ 'label' => 'Humans' ];

		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
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
		$registration_argument_set = new PostTypeRegistrationArgumentSet( 'human', [] );
		$this->assertSame( 'Human', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that registration arguments element 'label' is not capitalized
	 * if user provided registration arguments element 'label' already.
	 *
	 * @return void
	 */
	public function testLabelShouldNotBeAutoCapitalizedIfUserProvidedLabel(): void {
		$post_type_key = 'ork';
		$post_type_registration_arguments = [ 'label' => 'bigork' ]; // Deliberately lowercase.
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
		$this->assertSame( 'bigork', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that it is possible to toggle registration
	 * arguments element 'label' value's auto capitalization.
	 *
	 * @return void
	 */
	public function testCanToggleAutoCapitalizeLabel(): void {
		$registration_argument_set = new PostTypeRegistrationArgumentSet( 'human', [] );
		$registration_argument_set->setDoAutoCapitalize( false );
		$this->assertSame( 'human', $registration_argument_set->getArguments()['label'] );
		$registration_argument_set->setDoAutoCapitalize( true );
		$this->assertSame( 'Human', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that registration arguments element 'label' value is capitalized if
	 * user provided registration arguments but they did not include 'label' array element.
	 *
	 * @return void
	 */
	public function testLabelShouldBeAutoCapitalizedIfUserProvidedArgumentsDoNotIncludeLabel(): void {
		$post_type_key = 'ork';
		$post_type_registration_arguments = [ 'show_in_rest' => true ];
		$registration_argument_set = new PostTypeRegistrationArgumentSet( $post_type_key, $post_type_registration_arguments );
		$this->assertSame( 'Ork', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that registration arguments element 'label' value consists of two words if key contains underscore or hyphen.
	 *
	 * @return void
	 */
	public function testLabelShouldContainsTwoWordsIfKeyContainsUnderscoreOrHyphen(): void {
		$registration_argument_set = new PostTypeRegistrationArgumentSet( 'ork_house', [] );
		$registration_argument_set->setDoAutoCapitalize( false );
		$this->assertSame( 'ork house', $registration_argument_set->getArguments()['label'] );

		$registration_argument_set = new PostTypeRegistrationArgumentSet( 'ork_house', [] );
		$registration_argument_set->setDoAutoCapitalize( true );
		$this->assertSame( 'Ork House', $registration_argument_set->getArguments()['label'] );
	}

	/**
	 * Make sure that default arguments are run through a WordPress filter.
	 *
	 * @return void
	 */
	public function testDefaultArgumentsRunThroughWPFilter(): void {
		WP_Mock::onFilter( 'wpregistrars/post_type/default_arguments' )
			->with( [], 'human' )
			->reply( [ 'description' => 'Humans ruled Middle Earth' ] );

		$registration_argument_set = new PostTypeRegistrationArgumentSet( 'human', [] );
		$this->assertSame( 'Humans ruled Middle Earth', $registration_argument_set->getArguments()['description'] );

		$registration_argument_set->setDefaultArguments( [ 'show_in_rest' => false ] );
		$this->assertFalse( $registration_argument_set->getArguments()['show_in_rest'] );
		WP_Mock::onFilter( 'wpregistrars/post_type/default_arguments' )
			->with( [ 'show_in_rest' => false ], 'human' )
			->reply( [ 'show_in_rest' => true ] );
		$this->assertTrue( $registration_argument_set->getArguments()['show_in_rest'] );
	}
}
