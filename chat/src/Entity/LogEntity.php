<?php

namespace Lentech\Botster\Entity;

class LogEntity extends Entity
{
	public $id;
	public $log = '';

	/**
	 * Appends text to log, optionally with a newline at the end.
	 *
	 * @param string $text Text to append
	 */
	public function append($text, $append_newline = true)
	{
		$this->log .= $text;

		if ($append_newline)
		{
			$this->log .= "\n";
		}
	}
}