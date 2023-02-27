<?php

use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Apps\Mail\Application;

if(!defined('INC_MODE')){
    exit;
}
return new Application(
    utilities: ApplicationUtilities::getInstance()
);