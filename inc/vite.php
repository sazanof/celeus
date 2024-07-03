<?php

// For a real-world example check here:
// https://github.com/wp-bond/bond/blob/master/src/Tooling/Vite.php
// https://github.com/wp-bond/boilerplate/tree/master/app/themes/boilerplate

// you might check @vitejs/plugin-legacy if you need to support older browsers
// https://github.com/vitejs/vite/tree/main/packages/plugin-legacy


// Prints all the html entries needed for Vite
class Vite
{
	public static ?Vite $instance = null;
	public static ?string $entry = null;
	public static bool $registered = false;
	public ?string $app = null;
	public ?string $directoryPrefix = null;
	public int $port;

	public function __construct(int $port = null)
	{
		$port = is_null($port) ? env('VITE_PORT') : $port;
		$this->port = $port;

		self::$instance = $this;
	}

	public static function register(string $entry, string $app = null, $port = null): string
	{
		if (is_null(self::$instance) || $entry !== self::$entry) {
			self::$instance = new self();
		}
		self::$instance->app = $app;
		self::$instance->directoryPrefix = !is_null($app) ? 'apps/' . $app . '/' : null;

		if (env('VITE_APP_PORT') !== null) {
			self::$instance->port = env('VITE_APP_PORT');
		}

		$out = "\n" . self::$instance->jsTag($entry)
			. "\n" . self::$instance->jsPreloadImports($entry)
			. "\n" . self::$instance->cssPreloadImports($entry)
			. "\n" . self::$instance->cssTag($entry);
		self::$registered = true;

		return $out;
	}

	public static function url()
	{
		return env('VITE_SCHEME', 'http') . '://' . env('VITE_HOST', 'localhost') . ':' . self::$instance->port;
	}


// Some dev/prod mechanism would exist in your project

	public function isDev(string $entry): bool
	{
		// This method is very useful for the local server
		// if we try to access it, and by any means, didn't started Vite yet
		// it will fallback to load the production files from manifest
		// so you still navigate your site as you intended!
		static $exists = null;
		/*if ($exists !== null) {
			return $exists;
		}*/
		$handle = curl_init(self::url() . '/' . $entry);

		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_NOBODY, true);

		curl_exec($handle);
		$error = curl_errno($handle);
		curl_close($handle);

		return $exists = !$error;
	}


// Helpers to print tags

	public function jsTag(string $entry): string
	{
		$url = $this->isDev($entry)
			? self::url() . '/' . $entry
			: $this->assetUrl($entry);
		if (!$url) {
			return '';
		}
		$out = '';
		if ($this->isDev($entry)) {
			//TODO /@vite/client only once
			if (!self::$registered) {
				$out .= '<script type="module" src="' . self::url() . '/@vite/client"></script>' . "\n";
			}
			$out .= '<script type="module" src="' . $url . '"></script>';
		}
		$out .= '<script type="module" src="' . $url . '"></script>';

		return $out;
	}

	public function jsPreloadImports(string $entry): string
	{
		if ($this->isDev($entry)) {
			return '';
		}

		$res = '';
		foreach ($this->importsUrls($entry) as $url) {
			$res .= '<link rel="modulepreload" href="'
				. $url
				. '">';
		}
		return $res;
	}

	public function cssPreloadImports(string $entry): string
	{
		if ($this->isDev($entry)) {
			return '';
		}

		$res = '';
		foreach ($this->importsUrls($entry, true) as $url) {
			$res .= '<link rel="stylesheet" href="'
				. $url
				. '">';
		}
		return $res;
	}

	public function cssTag(string $entry): string
	{
		// not needed on dev, it's inject by Vite
		if ($this->isDev($entry)) {
			return '';
		}

		$tags = '';
		foreach ($this->cssUrls($entry) as $url) {
			$tags .= '<link rel="stylesheet" href="'
				. $url
				. '">';
		}
		return $tags;
	}


// Helpers to locate files

	public function getManifest(): array
	{
		$content = file_get_contents('../' . $this->directoryPrefix . 'public/dist/.vite/manifest.json');
		return json_decode($content, true);
	}

	public function assetUrl(string $entry): string
	{
		$manifest = $this->getManifest();

		return isset($manifest[$entry])
			? '/' . $this->directoryPrefix . 'dist/' . $manifest[$entry]['file']
			: '';
	}

	public function importsUrls(string $entry, $style = false): array
	{
		$urls = [];
		$manifest = $this->getManifest();
		if (!empty($manifest[$entry]['imports'])) {
			foreach ($manifest[$entry]['imports'] as $import) {
				/*$urls[] = '/dist/' . $manifest[$imports]['file'];*/
				if (isset($manifest[$import])) {
					if ($style) {
						if (isset($manifest[$import]['css']) && is_array($manifest[$import]['css'])) {
							//foreach($manifest[$import]['css'] as $css) {
							$urls = array_merge($this->cssUrls($import), $urls);
							//}
						}

					} else {
						$urls[] = $this->assetUrl($import);
					}

				}
			}
		}
		return $urls;
	}

	public function cssUrls(string $entry): array
	{
		$urls = [];
		$manifest = $this->getManifest();
		//dd($manifest, $entry);
		if (!empty($manifest[$entry]['css'])) {
			foreach ($manifest[$entry]['css'] as $file) {
				$urls[] = '/' . $this->directoryPrefix . 'dist/' . $file;
			}
		}

		return $urls;
	}
}


