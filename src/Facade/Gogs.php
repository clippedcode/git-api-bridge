<?php
namespace Clippedcode\Gogs\Facade;

use Illuminate\Support\Facades\Facade;

class Gogs extends Facade {
    
	public static function getFacadeAccessor()
	{
		return "gogs";
	}
}