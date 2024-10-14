<?php

namespace CliLocalizer;

/**
 * Class CliOutput
 *
 * This class is responsible for outputting translated messages to the command line interface.
 */
class CliOutput
{
	/**
	 * @var Translator The translator instance used for translating messages
	 */
	private Translator $translator;

	/**
	 * CliOutput constructor.
	 *
	 * @param Translator $translator The translator instance to use for translations
	 */
	public function __construct(Translator $translator) {
		$this->translator = $translator;
	}

	/**
	 * Write a translated line to the console.
	 *
	 * This method translates the given key using the translator,
	 * interpolates any provided parameters, and outputs the result to the console.
	 *
	 * @param string $key The translation key to look up
	 * @param array $params An associative array of parameters to interpolate into the translation
	 *
	 * @return void
	 */
	public function writeln(string $key, array $params = []): void {
		$message = $this->translator->translate($key, $params);
		echo $message . PHP_EOL;
	}
}