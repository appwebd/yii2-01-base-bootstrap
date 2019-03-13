<?php

// comment out the following three lines when deployed to production
defined('YII_DEBUG') || define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') || define('YII_TRACE_LEVEL', 3);
defined('YII_ENV') || define('YII_ENV', 'dev'); // 'prod' is for production environment

defined('DISABLE_CACHE') || define('DISABLE_CACHE', true);


// BEGIN GLOBAL CONSTANTS of this web application --------------------------------------------------
//
defined('ACTION_CREATE') || define('ACTION_CREATE', 'create');
defined('ACTION_DELETE') || define('ACTION_DELETE', 'delete');
defined('ACTION_INDEX')  || define('ACTION_INDEX', 'index');
defined('ACTION_LOGOUT')   || define('ACTION_LOGOUT', 'logout');
defined('ACTION_REMOVE') || define('ACTION_REMOVE', 'remove');

defined('ACTION_VIEW_ICON')  || define('ACTION_VIEW_ICON', 'glyphicon glyphicon-eye-open');
defined('ACTION_UPDATE_ICON') || define('ACTION_UPDATE_ICON', 'glyphicon glyphicon-pencil');
defined('ACTION_DELETE_ICON') || define('ACTION_DELETE_ICON', 'glyphicon glyphicon-trash');

defined('ACTIONS') || define('ACTIONS', 'actions');
defined('ACTION_UPDATE') || define('ACTION_UPDATE', 'update');
defined('ACTION_VIEW')   || define('ACTION_VIEW', 'view');
defined('ACTIVE') || define('ACTIVE', 'active');
defined('ALLOW')   || define('ALLOW', 'allow');
defined('ATTRIBUTE') || define('ATTRIBUTE', 'attribute');
defined('ATTRIBUTES') || define('ATTRIBUTES', 'attributes');
defined('AUTOCOMPLETE') || define('AUTOCOMPLETE', 'autocomplete');
defined('AUTOFOCUS') || define('AUTOFOCUS', 'autofocus');
defined('BREADCRUMBS') || define('BREADCRUMBS', 'breadcrumbs');
defined('COLSM1') || define('COLSM1', 'col-sm-1');
defined('CONTENT') || define('CONTENT', 'content');
defined('DATA_PROVIDER')   || define('DATA_PROVIDER', 'dataProvider');
defined('DATEFORMAT')   || define('DATEFORMAT', 'dd-MM-yyyy');
defined('DATETIMEFORMAT')   || define('DATETIMEFORMAT', 'DD-MM-YYYY HH:mm');
defined('ERROR')   || define('ERROR', 'error');
defined('ERROR_MODULE') || define('ERROR_MODULE', 'Failed to {module}, error: {error}');
defined('FILTER') || define('FILTER', 'filter');
defined('FORMAT') || define('FORMAT', 'format');
defined('GRID_DATACOLUMN') || define('GRID_DATACOLUMN', 'yii\grid\DataColumn');
defined('GRIDVIEW_CSS') || define('GRIDVIEW_CSS', 'table maxwidth items table-striped table-condensed');
defined('HEADER') || define('HEADER', 'header');
defined('HTML_BTN_BTN_DEFAULT') || define('HTML_BTN_BTN_DEFAULT', 'btn btn-default');
defined('HTML_CLOSE_DIV_OPEN_COL_SM_8') || define('HTML_CLOSE_DIV_OPEN_COL_SM_8', '</div><div class="col-sm-8">');
defined('HTML_DIV_CLOSE') || define('HTML_DIV_CLOSE', '</div>');
defined('HTML_DIV_CLOSE_DIV6_OPEN') || define('HTML_DIV_CLOSE_DIV6_OPEN', '</div><div class="col-sm-6">');
defined('HTML_DIV_CLOSEX2') || define('HTML_DIV_CLOSEX2', '</div></div>');
defined('HTML_MODEL_LG') || define('HTML_MODEL_LG', 'model-lg');
defined('HTML_OPTION_CLOSE') || define('HTML_OPTION_CLOSE', '</option>');
defined('HTML_OPTION') || define('HTML_OPTION', '<option>');
defined('HTML_ROW_DIV6') || define('HTML_ROW_DIV6', '<div class="row"><div class="col-sm-6">');
defined('HTML_ROW_OPEN_COL_SM_4') || define('HTML_ROW_OPEN_COL_SM_4', '<div class="row"><div class="col-sm-4">');
defined('HTML_SPACEX2') || define('HTML_SPACEX2', '&nbsp; &nbsp;');
defined('HTML_WEBPAGE_CLOSE') || define('HTML_WEBPAGE_CLOSE', '</div></div></div>');
defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_4') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_4', '</div><div class="col-sm-4">');
defined('HTML_WEBPAGE_OPEN_COL_SM_8') || define('HTML_WEBPAGE_OPEN_COL_SM_8', '<div class="webpage"><div class="row"><div class="col-sm-8 box">');
defined('HTML_WEBPAGE_OPEN')  || define('HTML_WEBPAGE_OPEN','<div class=" box">');
defined('ID') || define('ID', 'id');
defined('INFO') || define('INFO', 'info');
defined('INPUT') || define('INPUT', 'input');
defined('INPUT_OPTIONS') || define('INPUT_OPTIONS', 'inputOptions');
defined('INPUT_TEMPLATE') || define('INPUT_TEMPLATE', 'inputTemplate');
defined('ITEMS') || define('ITEMS', 'items');
defined('LABEL') || define('LABEL', 'label');
defined('LABELOPTIONS') || define('LABELOPTIONS', 'labelOptions');
defined('LANGUAGE') || define('LANGUAGE', 'label');
defined('LENGTH') || define('LENGTH', 'length');
defined('MAXLENGTH') || define('MAXLENGTH', 'maxlength');
defined('METHOD') || define('METHOD', 'method');
defined('MODEL')   || define('MODEL', 'model');
defined('MODULE') || define('MODULE', 'module');
defined('MSG_ERROR') || define('MSG_ERROR', 40);
defined('MSG_INFO') || define('MSG_INFO', 10);
defined('MSG_SECURITY_ISSUE')   || define('MSG_SECURITY_ISSUE', 50);
defined('MSG_SUCCESS') || define('MSG_SUCCESS', 20);
defined('MSG_WARNING') || define('MSG_WARNING', 30);
defined('OPTIONS') || define('OPTIONS', 'options');
defined('PAGE_SIZE') || define('PAGE_SIZE', 'pageSize');
defined('PATTERN_DATE') || define('PATTERN_DATE', 'd-m-Y');
defined('PATTERN_DATETIME') || define('PATTERN_DATETIME', 'Y-m-d H:m:i');
defined('PATTERN_DATE_YMD') || define('PATTERN_DATE_YMD', 'Y-m-d');
defined('PATTERN') || define('PATTERN', 'pattern');
defined('PATTERN_PHONE') || define('PATTERN_PHONE', '[0-9]{3}-[0-9]{4}-[0-9]{4}');
defined('PLACEHOLDER') || define('PLACEHOLDER', 'placeholder');
defined('PROMPT') || define('PROMPT', 'prompt');
defined('RANGE')   || define('RANGE', 'range');
defined('REQUIRED') || define('REQUIRED', 'required');
defined('ROLES')   || define('ROLES', 'roles');
defined('SEARCH_MODEL')   || define('SEARCH_MODEL', 'searchModel');
defined('STR_CLASS')   || define('STR_CLASS', 'class');
defined('STR_DEFAULT') || define('STR_DEFAULT', 'default');
defined('STRING')   || define('STRING', 'string');
defined('SUCCESS')   || define('SUCCESS', 'success');
defined('TABINDEX') || define('TABINDEX', 'tabindex');
defined('TEMPLATE') || define('TEMPLATE', 'template');
defined('TITLE') || define('TITLE', 'title');
defined('TRANSACTION_MODULE') || define('TRANSACTION_MODULE', '{module} {method} success');
defined('TYPE') || define('TYPE', 'type');
defined('UNCHECK') || define('UNCHECK', 'uncheck');
defined('VALUE') || define('VALUE', 'value');
defined('WARNING') || define('WARNING', 'warning');
