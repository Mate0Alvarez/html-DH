CREATE TABLE `connections` (
`id` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `strength` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `conversations` (
`id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `user_agent` text NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `logs` (
`id` int(11) NOT NULL,
  `log` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `messages` (
`id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `author_id` tinyint(4) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `online` (
  `session_id` varchar(100) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `user_agent` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `utterances` (
`id` int(11) NOT NULL,
  `text` varchar(100) NOT NULL,
  `said` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `words` (
`id` int(11) NOT NULL,
  `text` varchar(100) NOT NULL,
  `definition` text NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


ALTER TABLE `connections`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `input` (`from`,`to`);

ALTER TABLE `conversations`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `messages`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `online`
 ADD PRIMARY KEY (`session_id`);

ALTER TABLE `utterances`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `text_unique` (`text`), ADD FULLTEXT KEY `text_fulltext` (`text`);

ALTER TABLE `words`
 ADD PRIMARY KEY (`id`);


ALTER TABLE `connections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `conversations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `utterances`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `words`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
