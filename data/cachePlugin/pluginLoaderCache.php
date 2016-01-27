<?php
$base_path = dirname(__FILE__) . '/../../application/modules/default/';
$views_base_path = $base_path . 'views/helpers/';

include_once 'Zend/Application/Resource/Multidb.php';
include_once 'Zend/Application/Resource/Modules.php';
include_once 'Zend/Application/Resource/View.php';
include_once 'Zend/Application/Resource/Layout.php';
include_once 'Zend/Application/Resource/Cachemanager.php';
include_once 'Zend/View/Helper/Doctype.php';
include_once 'Zend/View/Helper/HeadMeta.php';
include_once 'Zend/Controller/Action/Helper/ViewRenderer.php';
include_once 'Zend/Filter/Word/CamelCaseToDash.php';
include_once 'Zend/Filter/StringToLower.php';
include_once 'Zend/Controller/Action/Helper/AjaxContext.php';
include_once $views_base_path . 'Request.php';
include_once 'Zend/View/Helper/HeadScript.php';
include_once 'Zend/View/Helper/BaseUrl.php';
include_once $views_base_path . 'LoggedAllowed.php';
include_once $views_base_path . 'BuscaPadrao.php';
include_once 'Zend/View/Helper/Url.php';
include_once 'Zend/View/Helper/HeadLink.php';
include_once 'Zend/View/Helper/HeadStyle.php';
include_once 'Zend/View/Helper/HeadTitle.php';
include_once $views_base_path . 'UserAuth.php';
include_once 'Zend/View/Helper/Layout.php';
include_once 'Zend/Filter/StripTags.php';
include_once 'Zend/Filter/StringTrim.php';
include_once 'Zend/Validate/NotEmpty.php';
include_once 'Zend/Filter/Alnum.php';
include_once $views_base_path . 'HasCurrentCompetition.php';
include_once $views_base_path . 'TerminoEtapas.php';
include_once 'Zend/Paginator/Adapter/DbTableSelect.php';
include_once 'Zend/Controller/Action/Helper/Redirector.php';
include_once 'Zend/Filter/Digits.php';
include_once 'Zend/Validate/Digits.php';
include_once 'Zend/Paginator/ScrollingStyle/Sliding.php';
include_once 'Zend/View/Helper/Partial.php';
include_once $base_path . 'controllers/action/helper/Regional.php';
include_once 'Zend/Paginator/Adapter/Iterator.php';
include_once $views_base_path . 'ConfigRegistry.php';
include_once $views_base_path . 'Acl.php';
include_once $views_base_path . 'SubscriptionConfirmationCheck.php';
include_once 'Zend/Controller/Action/Helper/Json.php';
include_once 'Zend/Filter/Alpha.php';
include_once 'Zend/View/Helper/Action.php';