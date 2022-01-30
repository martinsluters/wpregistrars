<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

use IteratorAggregate;
use Countable;

/**
 * Interface of ArgumentSetContainer
 */
interface ArgumentSetContainerInterface extends IteratorAggregate, Countable {

	/**
	 * Class constructor
	 *
	 * @param ArgumentSetInterface ...$argument_sets List of ArgumentSet(s).
	 */
	public function __construct( ArgumentSetInterface ...$argument_sets );

	/**
	 * Add argument set
	 *
	 * @param ArgumentSetInterface $argument_set Argument set to add.
	 */
	public function addArgumentSet( ArgumentSetInterface $argument_set );

	/**
	 * Validity of container content
	 *
	 * @return boolean
	 */
	public function isValidContainerContent(): bool;
}
