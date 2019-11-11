# Botster

The friendly, fun, quirky chatbot

## Introduction

Botster is a self-learning chatbot which aims to be able to hold intellectual conversations on a plethora of subjects. The nature of its responses aim to be useful as well as entertaining by providing factual information in some situations. Because Botster's intelligence is not preprogrammed, but developed autonomously from participating in conversations, it (in theory) is constantly getting smarter. The bot's intelligence is still very primitive, however we continue to explore, experiment, and implement new methods in hope of it becoming successfully competent.

## Brain functions

### Copy-cat learning

Botster stores all messages it receives in conversation and creates connections between these messages when one is said in response to another. This allows Botster to see what others replied with to a message so it can use the response for itself. When someone else says the same response to a message, the connection between them is strengthened. This informs Botster on which are the more popular responses and further aids in deciding on a suitable reply.

### Fuzzy search

When a response doesn't exist for an input, Botster does a fuzzy search to find a similar utterance which does have a response. For example: a fuzzy search for "So, what is your name?" could find "What's your name?" and it would then go on to respond to that instead. This allows for Botster to provide a relevant response even when it doesn't have an exact match.

### Spell check

Botster has its own custom database of words which it deems as valid English. To avoid learning spam and typos, Botster will only use responses where all of its words exists in this dictionary database.

## Requirements

 - Apache HTTP server
 - PHP (version >= 5.4)
 - MySQL
 - [Composer](https://getcomposer.org)
 - [Bower](http://bower.io)
 - [Sass](http://sass-lang.com/)

## Installation

 - Fork and clone the project to your local files.
 - Create a new database and import `database/mysql/tables.sql` into it.
 - Add `ft_min_word_len=1` and `ft_stopword_file=""` under `[mysqld]` in your MySQL config file and restart MySQL.
 - Duplicate the `application/config_templates/` directory as `application/config/`.
 - Edit the configuration files in `application/config/` to match your environment.
 - Set `RewriteBase` in `public/.htaccess` to match your environment.
 - Make `application/cache/` and `application/logs/` directories writable to the web server.
 - Run `composer install` to install all PHP package dependencies.
 - Run `bower install` to install all front-end dependencies.
 - Compile all SCSS files in `public/scss/` to `public/css/`.
