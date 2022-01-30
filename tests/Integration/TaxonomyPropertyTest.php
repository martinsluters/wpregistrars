<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Integration;

/**
 * Tests of Registered Taxonomies' properties.
 */
class TaxonomyPropertyTest extends TestCase {

	/**
	 * Custom arguments should take over default arguments
	 *
	 * @return void
	 */
	public function testCustomArgsUsed(): void {
		$this->assertSame( 'List of Divisions', get_taxonomy( 'division' )->label );
	}

	/**
	 * By default label is converted to plural form (english)
	 * and each word starts with capital letter.
	 *
	 * @return void
	 */
	public function testLabelConvertedToEnglishPluralAndCapitalFirstLetterOfWordsByDefault(): void {
		$this->assertSame( 'Classes', get_taxonomy( 'class' )->label );
		$this->assertSame( 'Distinctions', get_taxonomy( 'distinction' )->labels->menu_name );
		$this->assertSame( 'Animal Families', get_taxonomy( 'animal_family' )->label );
		$this->assertSame( 'Animal Families', get_taxonomy( 'animal_family' )->labels->menu_name );
	}

	/**
	 * It is possible to turn off label auto conversion into plural form.
	 *
	 * @return void
	 */
	public function testAutoLabelPluralizationCanBeTurnedOff(): void {

		$this->assertSame( 'Classes', get_taxonomy( 'class' )->label );
		unregister_taxonomy( 'class' );

		$this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'class' ] )
			->shouldAutoPluralize( false )
			->create()
			->register();

		$this->assertSame( 'Class', get_taxonomy( 'class' )->label );
	}

	/**
	 * Default label should be respected if provided and there is not label provided at
	 * single taxonomy argument level.
	 *
	 * @return void
	 */
	public function testDefaultLabelShouldBeAppliedDisablingAutoPluralizationAndCapitalization(): void {

		$this->bulk_registrar_taxonomy_factory
			->withDefaultRegistrationArguments( [ 'label' => 'sauron_class' ] )
			->withArguments( [ 'middlearth_class' ] )
			->create()
			->register();

		$this->assertSame( 'sauron_class', get_taxonomy( 'middlearth_class' )->label );
	}

	/**
	 * Default label should NOT be respected if provided and there IS label provided at
	 * single taxonomy argument level.
	 *
	 * @return void
	 */
	public function testDefaultLabelShouldNotBeAppliedIfLabelAtSingleTaxonomyLevel(): void {

		$this->bulk_registrar_taxonomy_factory
			->withDefaultRegistrationArguments( [ 'label' => 'smurf' ] )
			->withArguments( [ 'middlearth_class' => [ 'label' => 'sauron_class' ] ] )
			->create()
			->register();

		$this->assertSame( 'sauron_class', get_taxonomy( 'middlearth_class' )->label );
	}

	/**
	 * It is possible to turn off label auto conversion capital word letters.
	 *
	 * @return void
	 */
	public function testAutoLabelCapitalizationCanBeTurnedOff(): void {
		$this->assertSame( 'Classes', get_taxonomy( 'class' )->label );
		unregister_taxonomy( 'class' );

		$this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'class' ] )
			->shouldAutoCapitalize( false )
			->create()
			->register();

		$this->assertSame( 'classes', get_taxonomy( 'class' )->label );
	}

	/**
	 * It is possible set default arguments applied "globally"
	 *
	 * @return void
	 */
	public function testCanSetDefaultArguments(): void {

		$wp_taxonomy_class_vars = get_class_vars( \WP_Taxonomy::class );
		$this->assertTrue( $wp_taxonomy_class_vars['public'] );

		$this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'kingdom', 'genus' ] )
			->withDefaultRegistrationArguments( [ 'public' => false ] )
			->create()
			->register();

		$this->assertFalse( get_taxonomy( 'kingdom' )->public );
		$this->assertFalse( get_taxonomy( 'genus' )->public );
	}

	/**
	 * It is possible to overwrite default arguments applied "globally"
	 *
	 * @return void
	 */
	public function testCanOverWriteDefaultArguments(): void {

		$this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'kingdom', 'genus' ] )
			->withDefaultRegistrationArguments( [ 'description' => 'Lorem Ipsum' ] )
			->withDefaultRegistrationArguments( [ 'description' => 'Short Description' ] )
			->create()
			->register();

		$this->assertSame( 'Short Description', get_taxonomy( 'kingdom' )->description );
		$this->assertSame( 'Short Description', get_taxonomy( 'genus' )->description );
	}

	/**
	 * Default argument 'object_type' should be used for all taxonomies.
	 * 'object_type' is a special array element that passes object types to register
	 * taxonomy(ies) to.
	 *
	 * @return void
	 */
	public function testObjectTypeDefaultArgumentArrayElementUsedForAllTaxonomies(): void {

		$this->bulk_registrar_taxonomy_factory
			->withDefaultRegistrationArguments( [ 'object_type' => [ 'dwarf', 'hobbit' ] ] )
			->withArguments( [ 'kingdom', 'genus' ] )
			->create()
			->register();
		$this->assertContains( 'dwarf', get_taxonomy( 'kingdom' )->object_type );
		$this->assertContains( 'hobbit', get_taxonomy( 'kingdom' )->object_type );
		$this->assertContains( 'dwarf', get_taxonomy( 'genus' )->object_type );
		$this->assertContains( 'hobbit', get_taxonomy( 'genus' )->object_type );
	}

	/**
	 * It is possible to overwrite default argument 'object_type' if applied on individual elements.
	 * 'object_type' is a special array element that passes object types to register
	 * taxonomy(ies) to.
	 *
	 * @return void
	 */
	public function testObjectTypeDefaultArgumentArrayElementCanBeOverruled(): void {

		$this->bulk_registrar_taxonomy_factory
			->withDefaultRegistrationArguments( [ 'object_type' => [ 'dwarf', 'hobbit' ] ] )
			->withArguments(
				[
					'kingdom' => [ 'object_type' => [ 'elf' ] ],
					'genus',
					'language' => [ 'object_type' => [] ],
				]
			)
			->create()
			->register();

		// Use per taxonomy provided.
		$this->assertContains( 'elf', get_taxonomy( 'kingdom' )->object_type );
		$this->assertCount( 1, get_taxonomy( 'kingdom' )->object_type );

		// Use default only.
		$this->assertContains( 'hobbit', get_taxonomy( 'genus' )->object_type );
		$this->assertContains( 'dwarf', get_taxonomy( 'genus' )->object_type );
		$this->assertCount( 2, get_taxonomy( 'genus' )->object_type );

		// Use per taxonomy provided which is empty.
		$this->assertCount( 0, get_taxonomy( 'language' )->object_type );
	}
}
