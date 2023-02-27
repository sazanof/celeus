<?php

namespace Vorkfork\Core\Controllers;

class LocaleController extends Controller
{
    protected bool $useTemplateRenderer = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function getTranslation($lang) {
        return $this->filesystem->get('/resources/locales/' . $lang . '.json');
    }

    public function getLocaleList(){
        $locales = $this->filesystem->glob('/resources/locales/');
        $ar = [];
        foreach ($locales as $locale){
            $explodeBaseName = explode('.',$locale->getBasename());
            $ar[] = [
                'code' => $explodeBaseName[0],
                'name' => mb_ucfirst(\Locale::getDisplayName($explodeBaseName[0], $explodeBaseName[0]))
            ];
        }
        return $ar;
    }
}