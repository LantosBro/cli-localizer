<?php

namespace CliLocalizer\TranslationLoaders;

class PhpTranslationLoader implements TranslationLoaderInterface
{
	public function load(string $filePath): array
	{
		$translations = require $filePath;

		if (!is_array($translations)) {
			throw new \RuntimeException("PHP translation file must return an array: $filePath");
		}

		return $translations;
	}
}