<?php

namespace Lentech\Botster\Entity;

abstract class Entity
{
	public function __construct(array $data = null)
	{
		// If data array was passed
		if ($data !== null)
		{
			foreach ($data as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}
	
	public function asArray()
	{
		return get_object_vars($this);
	}
}