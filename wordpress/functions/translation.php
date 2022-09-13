<?php

/**
 * Translate functions
 */

//Base
__('the sentence I want to translate', 'text-domain');
_e('The sentence I want to translate', 'text-domain');

/**
 * Parameters
 * @param string $single : Text to be used if the number is singular
 * @param string $plural : Text to be used if the number is plural
 * @param int $count : Number to compare against to use either the singular or plural
 * @param string $domain : Text domain for translated strings
 */
_n($single, $plural, $count, $domain);
_n('sentence', 'sentences', 3, 'text-domain');

//Translate with HTML or variables
__('<h1>Title</h1>Something to say.', 'kl_sendinblue_addon');
sprintf('<div><p>%s</p><p>%s</p></div>', __('Sentence', 'text-domain'), __('Sentence 2', 'text-domain'));
sprintf(__('Hello %s, welcome', 'text-domain'), $name);
