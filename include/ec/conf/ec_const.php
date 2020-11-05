<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'codecamp38056');
define('DB_PASSWD', 'codecamp38056');
define('DB_NAME', 'codecamp38056');

define('HTML_CHARACTER_SET', 'UTF-8');
define('DB_CHARACTER_SET', 'UTF8');

define('SPACE_DELETE', '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u');
define('ALL_SPACE_DELETE', '/( |　)/');
define('ADD_ID_PASS_CHECK', '/^([a-zA-Z0-9]{6,})$/');
define('INT_CHECK', '/^[0-9]+$/');

define('DATETIME', 'Y-m-d H:i:s');

define('VIEW_PATH','../../include/ec/view');
define('CSS_PATH','./assets/stylesheets');