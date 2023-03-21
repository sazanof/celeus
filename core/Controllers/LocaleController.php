<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\Request;

class LocaleController extends Controller
{
    protected bool $useTemplateRenderer = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets main file translation with app translation
     * @param string $lang
     * @return false|string
     */
    public function getTranslation(string $lang)
    {
        return $this->filesystem->get('/resources/locales/' . $lang . '.json');
    }

    public function getApplicationTranslation(string $lang, string $app = null)
    {
        $ar = json_decode($this->filesystem->get('/apps/' . $app . '/resources/locales/' . $lang . '.json'), true);
        return [
            $app => $ar
        ];
    }

    public function getLocaleList(): array
    {
        $locales = $this->filesystem->glob('/resources/locales/');
        $ar = [];
        foreach ($locales as $locale) {
            $explodeBaseName = explode('.', $locale->getBasename());
            $ar[] = [
                'code' => $explodeBaseName[0],
                'name' => mb_ucfirst(\Locale::getDisplayName($explodeBaseName[0], $explodeBaseName[0]))
            ];
        }
        return $ar;
    }
}