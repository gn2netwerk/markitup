<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo4.2
 * @version 1.1
 * @version svn:$Id$
 */

// REDMINE PROJECT
////////////////////////////////////////////////////////////////////////////////
$project_root_url = 'http://www.gn2-code.de/projects/markitup';
$key              = '4372f934b085621f0878e4d8d2dc8b1a4c3fd9dc';

echo a287_redmine_project_feed($project_root_url,$key,rex_request('chapter', 'string'));
