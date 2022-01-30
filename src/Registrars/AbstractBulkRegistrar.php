<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Registrars;

use martinsluters\WPRegistrars\{ RegistrableInterface, ArgumentSetInterface, ArgumentSetContainerInterface};

/**
 * Abstract class of bulk registering something.
 */
abstract class AbstractBulkRegistrar implements RegistrableInterface {

	/**
	 * Argument Set container
	 *
	 * @var ArgumentSetContainerInterface
	 */
	private ArgumentSetContainerInterface $argument_set_container;

	/**
	 * Current Argument Set
	 *
	 * @var ArgumentSetInterface|null
	 */
	private ?ArgumentSetInterface $current_argument_set = null;

	/**
	 * Setter of current_argument_set
	 *
	 * @param ArgumentSetInterface|null $current_argument_set New value of current argument set.
	 * @return void
	 */
	private function setCurrentArgumentSet( ?ArgumentSetInterface $current_argument_set ): void {
		$this->current_argument_set = $current_argument_set;
	}

	/**
	 * Setter of argument_set_container
	 *
	 * @param ArgumentSetContainerInterface $argument_set_container New value of argument_set_container.
	 * @return void
	 */
	private function setArgumentsSetContainer( ArgumentSetContainerInterface $argument_set_container ): void {
		$this->argument_set_container = $argument_set_container;
	}

	/**
	 * Getter of current_argument_set
	 *
	 * @return ArgumentSetInterface|null Current value of $current_argument_set.
	 */
	protected function getCurrentArgumentSet(): ?ArgumentSetInterface {
		return $this->current_argument_set;
	}

	/**
	 * Getter of argument_set_container
	 *
	 * @return ArgumentSetContainerInterface
	 */
	protected function getArgumentSetContainer(): ArgumentSetContainerInterface {
		return $this->argument_set_container;
	}

	/**
	 * Class constructor
	 *
	 * @param ArgumentSetContainerInterface $argument_set_container Array of arrays (assoc) of post type registration arguments or array of strings containing post type keys.
	 */
	public function __construct( ArgumentSetContainerInterface $argument_set_container ) {
		$this->setArgumentsSetContainer( $argument_set_container );
	}

	/**
	 * Main registration method
	 *
	 * @return array Returns array containing registration return values.
	 */
	public function register(): array {
		$results = [];

		if ( ! $this->getArgumentSetContainer()->isValidContainerContent() ) {
			return $results;
		}

		foreach ( $this->getArgumentSetContainer() as $argument_set ) {
			$this->setCurrentArgumentSet( $argument_set );
			$results[] = $this->registerSingle();
			$this->setCurrentArgumentSet( null );
		}

		return $results;
	}

	/**
	 * Main template method of registering single registrable.
	 *
	 * @return mixed
	 */
	abstract protected function registerSingle();
}
