<?php

// comment out the following three lines when deployed to production
defined('YII_DEBUG') || define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') || define('YII_TRACE_LEVEL', 3);
defined('YII_ENV') || define('YII_ENV', 'dev'); // 'prod' is for production environment

defined('DISABLE_CACHE') || define('DISABLE_CACHE', true);

if (YII_DEBUG) {
    error_reporting(E_ALL);
}
// BEGIN GLOBAL CONSTANTS of this web application --------------------------------------------------

defined('ACTION_CREATE') || define('ACTION_CREATE', 'create');
defined('ACTION_DELETE') || define('ACTION_DELETE', 'delete');
defined('ACTION_REMOVE') || define('ACTION_REMOVE', 'remove');
defined('ACTION_INDEX')  || define('ACTION_INDEX', 'index');
defined('ACTION_UPDATE') || define('ACTION_UPDATE', 'update');
defined('ACTION_VIEW')   || define('ACTION_VIEW', 'view');
defined('ACTION_LOGOUT')   || define('ACTION_LOGOUT', 'logout');
defined('ACTIONS') || define('ACTIONS', 'actions');
defined('ACTIVE') || define('ACTIVE', 'active');
defined('ALLOW')   || define('ALLOW', 'allow');
defined('ATTRIBUTES') || define('ATTRIBUTES', 'attributes');
defined('ATTRIBUTE') || define('ATTRIBUTE', 'attribute');
defined('AUTOCOMPLETE') || define('AUTOCOMPLETE', 'autocomplete');
defined('AUTOFOCUS') || define('AUTOFOCUS', 'autofocus');
defined('BREADCRUMBS') || define('BREADCRUMBS', 'breadcrumbs');
defined('COLSM1') || define('COLSM1', 'col-sm-1');
defined('ERROR')   || define('ERROR', 'error');

defined('DATA_PROVIDER')   || define('DATA_PROVIDER', 'dataProvider');
defined('DATEFORMAT')   || define('DATEFORMAT', 'dd-MM-yyyy');
defined('HEADER') || define('HEADER', 'header');
defined('HTML_ROW_DIV6') || define('HTML_ROW_DIV6', '<div class="row"><div class="col-sm-6">');
defined('HTML_DIV_CLOSE_DIV6_OPEN') || define('HTML_DIV_CLOSE_DIV6_OPEN', '</div><div class="col-sm-6">');
defined('HTML_DIV_CLOSEX2') || define('HTML_DIV_CLOSEX2', '</div></div>');
defined('HTML_DIV_CLOSE') || define('HTML_DIV_CLOSE', '</div>');

defined('FILTER') || define('FILTER', 'filter');
defined('FORMAT') || define('FORMAT', 'format');
defined('GRIDVIEW_CSS') || define('GRIDVIEW_CSS', 'table maxwidth items table-striped table-condensed');
defined('ITEMS') || define('ITEMS', 'items');
defined('INFO') || define('INFO', 'info');
defined('INPUT_OPTIONS') || define('INPUT_OPTIONS', 'inputOptions');
defined('INPUT_TEMPLATE') || define('INPUT_TEMPLATE', 'inputTemplate');
defined('LABEL') || define('LABEL', 'label');
defined('LABELOPTIONS') || define('LABELOPTIONS', 'labelOptions');
defined('LANGUAGE') || define('LANGUAGE', 'label');
defined('LENGTH') || define('LENGTH', 'length');
defined('MAXLENGTH') || define('MAXLENGTH', 'maxlength');
defined('MODEL')   || define('MODEL', 'model');
defined('METHOD') || define('METHOD', 'method');

// Begin Status declaracion variables
// see table app\models\Status.php subroutines:  getStatusName, getStatusBadge, getStatusList
// app\components\UiComponent.php subroutine: badgetStatus
defined('MSG_INFO')    || define('MSG_INFO', 10);
defined('MSG_SUCCESS')    || define('MSG_SUCCESS', 20);
defined('MSG_WARNING') || define('MSG_WARNING', 30);
defined('MSG_ERROR')   || define('MSG_ERROR', 40);
defined('MSG_SECURITY_ISSUE')   || define('MSG_SECURITY_ISSUE', 50);
// End Status declaracion variables

defined('OPTIONS') || define('OPTIONS', 'options');
defined('PAGE_SIZE') || define('PAGE_SIZE', 'pageSize');

defined('PATTERN') || define('PATTERN', 'pattern');
defined('PLACEHOLDER') || define('PLACEHOLDER', 'placeholder');
defined('PATTERN_PHONE') || define('PATTERN_PHONE', '[0-9]{3}-[0-9]{4}-[0-9]{4}');
defined('PATTERN_DATETIME') || define('PATTERN_DATETIME', 'Y-m-d H:m:i');
defined('PATTERN_DATE') || define('PATTERN_DATE', 'm-d-Y');

defined('PROMPT') || define('PROMPT', 'prompt');
defined('RANGE')   || define('RANGE', 'range');
defined('ROLES')   || define('ROLES', 'roles');
defined('REQUIRED') || define('REQUIRED', 'required');

defined('SEARCH_MODEL')   || define('SEARCH_MODEL', 'searchModel');
defined('STR_CLASS')   || define('STR_CLASS', 'class');
defined('STR_DEFAULT') || define('STR_DEFAULT', 'default');
defined('STRING')   || define('STRING', 'string');
defined('SUCCESS')   || define('SUCCESS', 'success');
defined('TABINDEX') || define('TABINDEX', 'tabindex');
defined('TYPE') || define('TYPE', 'type');
defined('TITLE') || define('TITLE', 'title');
defined('UNCHECK') || define('UNCHECK', 'uncheck');
defined('WARNING') || define('WARNING', 'warning');
defined('VALUE') || define('VALUE', 'value');
