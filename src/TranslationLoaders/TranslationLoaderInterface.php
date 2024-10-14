<?php

namespace CliLocalizer\TranslationLoaders;

/**
 * Interface TranslationLoaderInterface
 *
 * This interface defines the contract for translation file loaders.
 */
interface TranslationLoaderInterface
{
	/**
	 * Load translations from a file.
	 *
	 * @param string $filePath The path to the translation file
	 *
	 * @return array The loaded translations
	 */
	public function load(string $filePath): array;
}