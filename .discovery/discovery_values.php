<?php
return array (
  'botman/driver-config' => 
  array (
    0 => 'stubs/facebook.php',
  ),
  'botman/driver' => 
  array (
    0 => 'BotMan\\Drivers\\Facebook\\FacebookDriver',
    1 => 'BotMan\\Drivers\\Facebook\\FacebookAudioDriver',
    2 => 'BotMan\\Drivers\\Facebook\\FacebookFileDriver',
    3 => 'BotMan\\Drivers\\Facebook\\FacebookImageDriver',
    4 => 'BotMan\\Drivers\\Facebook\\FacebookLocationDriver',
    5 => 'BotMan\\Drivers\\Facebook\\FacebookVideoDriver',
  ),
  'botman/commands' => 
  array (
    0 => 'BotMan\\Drivers\\Facebook\\Commands\\AddPersistentMenu',
    1 => 'BotMan\\Drivers\\Facebook\\Commands\\AddStartButtonPayload',
    2 => 'BotMan\\Drivers\\Facebook\\Commands\\AddGreetingText',
    3 => 'BotMan\\Drivers\\Facebook\\Commands\\WhitelistDomains',
    4 => 'BotMan\\Drivers\\Facebook\\Commands\\Nlp',
  ),
);
