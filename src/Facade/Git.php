<?php
namespace Clippedcode\Git\Facade;

use Illuminate\Support\Facades\Facade;

class Git extends Facade {
    
	public static function getFacadeAccessor()
	{
		return "git";
	}
}