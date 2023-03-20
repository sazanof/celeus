<?php
declare(strict_types=1);

namespace Vorkfork\Core\Controllers;

use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Auth\Auth;
use Vorkfork\DTO\UserDto;

class AppController extends Controller
{
    protected bool $useTemplateRenderer = true;


    public function index(): string
    {
        $this->user = Auth::user()->toDto(UserDto::class);
        $this->data['user'] = $this->user;
        $this->data['apps'] = array_map(function ($item) {
            return $item['name'];
        }, ApplicationUtilities::getInstance()->getApplicationsList());
        //dd($this->data);
        return $this->templateRenderer->loadTemplate('/pages/main', $this->data);
    }
}
