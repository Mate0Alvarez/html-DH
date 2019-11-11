<?php

namespace Lentech\Botster\Entity;

class MessageEntity extends Entity
{
	/**
	 * Author ID for bot.
	 */
	const BOT = 0;

	/**
	 * Author ID for user.
	 */
	const USER = 1;

	public $id;
	public $conversation_id;
	public $author_id;
	public $text;
	public $time;
}