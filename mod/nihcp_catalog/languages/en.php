<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 

/**
 * The core language file is in /languages/en.php and each plugin has its
 * language files in a languages directory. To change a string, copy the
 * mapping into this file.
 *
 * For example, to change the blog Tools menu item
 * from "Blog" to "Rantings", copy this pair:
 * 			'blog' => "Blog",
 * into the $mapping array so that it looks like:
 * 			'blog' => "Rantings",
 *
 * Follow this pattern for any other string you want to change. Make sure this
 * plugin is lower in the plugin list than any plugin that it is modifying.
 *
 * If you want to add languages other than English, name the file according to
 * the language's ISO 639-1 code: http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
 */

return [
	'item:object:services' => "Cloud Services Catalog",
	'item:object:equivalency' => "Cloud Service Guidance",
	'item:object:glossary' => "Glossary of Cloud Terms",

	'nihcp_catalog' => 'Cloud Services Catalog',
	'nihcp_catalog:widget:description' => 'Displays the NIHCP Cloud Services Catalog.',
	'nihcp_catalog:widget:text' => '%s',
	'nihcp_catalog:menu' => 'catalog',
	'nihcp_catalog:none' => 'No %s was found',
	'nihcp_catalog:menu:add' => 'Add New %s',
	'nihcp_catalog:menu:history' => '%s History',

	'nihcp_catalog:save:success' => '%s saved successfully',
	'nihcp_catalog:save:fail' => 'Unable to save %s',

	'nihcp_catalog:delete:success' => '%s deleted successfully',
	'nihcp_catalog:delete:fail' => 'Unable to delete %s',

	'nihcp_catalog:edit:noaccess' => 'You do not have permission to edit this %s',

	'nihcp_catalog:button:delete' => 'Delete %s',
	'nihcp_catalog:delete:confirm' => 'Are you sure you want to delete this %s?',

	'nihcp_catalog:close' => 'Close %s',
];