<?php

namespace CliLocalizer;

use CliLocalizer\TranslationLoaders\TranslationLoaderInterface;
use CliLocalizer\TranslationLoaders\JsonTranslationLoader;
use CliLocalizer\TranslationLoaders\MoTranslationLoader;
use CliLocalizer\TranslationLoaders\PhpTranslationLoader;
use CliLocalizer\TranslationLoaders\YamlTranslationLoader;

class Translator
{
	private string $locale;
	private string $fallbackLocale;
	private string $translationsPath;
	private string $format;
	private array $translations = [];
	private TranslationLoaderInterface $loader;

	/**
	 * Translator constructor.
	 *
	 * @param string $translationsPath The path to the directory containing translation files
	 * @param string $format The format of translation files (json, yaml, php, mo)
	 * @param string|null $locale The locale to use. If null, it will be auto-detected
	 * @param string $fallbackLocale The fallback locale to use if the specified locale is not available
	 */
	public function __construct(
		string $translationsPath,
		string $format = 'json',
		?string $locale = null,
		string $fallbackLocale = 'en_US'
	) {
		$this->translationsPath = rtrim($translationsPath, '/');
		$this->format = strtolower($format);
		$this->locale = $locale ?: $this->detectSystemLocale();
		$this->fallbackLocale = $fallbackLocale;
		$this->loader = $this->getLoader($this->format);
		$this->loadTranslations();
	}

	/**
	 * Translate a given key with optional parameter interpolation.
	 *
	 * @param string $key The translation key to look up
	 * @param array $params An associative array of parameters to interpolate into the translation
	 *
	 * @return string The translated string, or the original key if no translation is found
	 */
	public function translate(string $key, array $params = []): string {
		$translation = $this->translations[$key] ?? $key;
		return $this->interpolate($translation, $params);
	}

	/**
	 * Detect the system locale.
	 *
	 * @return string The detected locale, or the fallback locale if detection fails
	 */
	private function detectSystemLocale(): string {
		$locales = array_filter([
			getenv('LC_ALL'),
			getenv('LC_MESSAGES'),
			getenv('LANG'),
			getenv('LANGUAGE'),
		]);

		if (!empty($locales)) {
			return reset($locales);
		}

		if (extension_loaded('intl')) {
			return \Locale::getDefault();
		}

		return $this->fallbackLocale;
	}

	/**
	 * Load translations for the current locale.
	 *
	 * @return void
	 */
	private function loadTranslations(): void {
		$this->loadLocaleFile($this->locale);

		if (empty($this->translations) && $this->locale !== $this->fallbackLocale) {
			$this->loadLocaleFile($this->fallbackLocale);
		}
	}

	/**
	 * Load translations from a specific locale file.
	 *
	 * @param string $locale The locale to load translations for
	 *
	 * @return void
	 */
	private function loadLocaleFile(string $locale): void {
		$file = $this->getTranslationFilePath($locale);
		if (file_exists($file)) {
			$this->translations = $this->loader->load($file);
		} else {
			$languageCode = substr($locale, 0, 2);
			$fallbackFile = $this->getTranslationFilePath($languageCode);
			if (file_exists($fallbackFile)) {
				$this->translations = $this->loader->load($fallbackFile);
			}
		}
	}

	/**
	 * Get the file path for a specific locale's translations.
	 *
	 * @param string $locale The locale to get the file path for
	 *
	 * @return string The full file path to the translation file
	 */
	private function getTranslationFilePath(string $locale): string {
		return $this->translationsPath . "/{$locale}.{$this->format}";
	}

	/**
	 * Interpolate parameters into a message string.
	 *
	 * @param string $message The message string containing placeholders
	 * @param array $params An associative array of parameters to interpolate
	 *
	 * @return string The interpolated message
	 */
	private function interpolate(string $message, array $params): string
	{
		foreach ($params as $key => $value) {
			$message = str_replace(":{$key}", $value, $message);
		}
		return $message;
	}

	/**
	 * Get the appropriate loader for the specified format.
	 *
	 * @param string $format The format of translation files
	 *
	 * @return TranslationLoaderInterface The loader for the specified format
	 * @throws \InvalidArgumentException If an unsupported format is specified
	 */
	private function getLoader(string $format): TranslationLoaderInterface {
		switch ($format) {
			case 'json':
				return new JsonTranslationLoader();
			case 'yaml':
				return new YamlTranslationLoader();
			case 'php':
				return new PhpTranslationLoader();
			case 'mo':
				return new MoTranslationLoader();
			default:
				throw new \InvalidArgumentException("Unsupported translation file format: $format");
		}
	}
}