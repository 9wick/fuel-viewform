<?php

Autoloader::add_core_namespace('ViewForm');

Autoloader::add_classes(array(
	'ViewForm\\Fieldset'             => __DIR__.'/classes/fieldset.php',
	'ViewForm\\Fieldset_Field'       => __DIR__.'/classes/fieldset/field.php',
));

