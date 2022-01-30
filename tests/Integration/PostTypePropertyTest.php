<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Integration;

/**
 * Tests of Registered Post Types' properties.
 */
class PostTypePropertyTest extends TestCase {

	/**
	 * Custom arguments should take over default arguments
	 *
	 * @return void
	 */
	public function testCustomArgsUsed(): void {
		$this->assertSame( 'List of Elves', get_post_type_object( 'elve' )->label );
		$this->assertSame( 'List of Humans', get_post_type_object( 'human' )->label );
	}

	/**
	 * By default label is converted to plural form (english)
	 * and each word starts with capital letter.
	 *
	 * @return void
	 */
	public function testLabelConvertedToEnglishPluralAndCapitalFirstLetterOfWordsByDefault(): void {
		$this->assertSame( 'Orks', get_post_type_object( 'ork' )->label );
		$this->assertSame( 'Orks', get_post_type_object( 'ork' )->labels->menu_name );
		$this->assertSame( 'Dwarf Mountains', get_post_type_object( 'dwarf_mountain' )->label );
		$this->assertSame( 'Dwarf Mountains', get_post_type_object( 'dwarf_mountain' )->labels->menu_name );
	}

	/**
	 * It is possible to turn off label auto conversion into plural form.
	 *
	 * @return void
	 */
	public function testAutoLabelPluralizationCanBeTurnedOff(): void {
		$this->assertSame( 'Orks', get_post_type_object( 'ork' )->label );
		unregister_post_type( 'ork' );

		$this->bulk_registrar_post_type_factory
			->withArguments( [ 'ork' ] )
			->shouldAutoPluralize( false )
			->create()
			->register();

		$this->assertSame( 'Ork', get_post_type_object( 'ork' )->label );
	}

	/**
	 * Default label should be respected if provided and there is not label provided at
	 * single post type argument level.
	 *
	 * @return void
	 */
	public function testDefaultLabelShouldBeAppliedDisablingAutoPluralizationAndCapitalization(): void {

		$this->bulk_registrar_post_type_factory
			->withDefaultRegistrationArguments( [ 'label' => 'cat' ] )
			->withArguments( [ 'dog' ] )
			->create()
			->register();

		$this->assertSame( 'cat', get_post_type_object( 'dog' )->label );
	}

	/**
	 * Default label should NOT be respected if provided and there IS label provided at
	 * single post type argument level.
	 *
	 * @return void
	 */
	public function testDefaultLabelShouldNotBeAppliedIfLabelAtSingleTaxonomyLevel(): void {

		$this->bulk_registrar_post_type_factory
			->withDefaultRegistrationArguments( [ 'label' => 'cat' ] )
			->withArguments( [ 'mouse' => [ 'label' => 'dog' ] ] )
			->create()
			->register();

		$this->assertSame( 'dog', get_post_type_object( 'mouse' )->label );
	}

	/**
	 * It is possible to turn off label auto conversion capital word letters.
	 *
	 * @return void
	 */
	public function testAutoLabelCapitalizationCanBeTurnedOff(): void {
		$this->assertSame( 'Orks', get_post_type_object( 'ork' )->label );
		unregister_post_type( 'ork' );

		$this->bulk_registrar_post_type_factory
			->withArguments( [ 'ork' ] )
			->shouldAutoCapitalize( false )
			->create()
			->register();

		$this->assertSame( 'orks', get_post_type_object( 'ork' )->label );
	}

	/**
	 * It is possible set default arguments applied "globally"
	 *
	 * @return void
	 */
	public function testCanSetDefaultArguments(): void {
		$wp_post_type_class_vars = get_class_vars( \WP_Post_Type::class );
		$this->assertFalse( $wp_post_type_class_vars['public'] );

		$this->bulk_registrar_post_type_factory
			->withArguments( [ 'mountain', 'area' ] )
			->withDefaultRegistrationArguments( [ 'public' => true ] )
			->create()
			->register();

		$this->assertTrue( get_post_type_object( 'mountain' )->public );
		$this->assertTrue( get_post_type_object( 'area' )->public );
	}

	/**
	 * It is possible to overwrite default arguments applied "globally"
	 *
	 * @return void
	 */
	public function testCanOverWriteDefaultArguments(): void {
		$this->bulk_registrar_post_type_factory
			->withArguments( [ 'mountain', 'area' ] )
			->withDefaultRegistrationArguments( [ 'capability_type' => 'tag' ] )
			->withDefaultRegistrationArguments( [ 'capability_type' => 'category' ] )
			->create()
			->register();

		$this->assertSame( 'category', get_post_type_object( 'mountain' )->capability_type );
		$this->assertSame( 'category', get_post_type_object( 'area' )->capability_type );
	}
}
