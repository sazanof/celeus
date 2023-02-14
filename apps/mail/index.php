<?php

use Celeus\Application\ApplicationUtilities;
use Celeus\Apps\Mail\Application;

if(!defined('INC_MODE')){
    exit;
}
return new Application(
    utilities: ApplicationUtilities::getInstance()
);