<?php

namespace Vorkfork\Core\Controllers;

use Vorkfork\Core\Models\Config;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends Controller
{
    public function getConfigurations(): array
    {
        return $this->em->getRepository(Config::class)->findBy(
            criteria: '',
            orderBy: ['created_at','asc']
        );
    }

    public function addConfiguration(Request $request){
        /*$config = new \Vorkfork\Core\Models\Config();
        $config->setApp('test1');
        $config->setKey('qwe1');
        $config->setValue('qwerty1');

        try {
            $this->em->persist($config);
            $this->em->flush();
        } catch (\Exception $exception){
            dd($exception);
        }*/
    }
}