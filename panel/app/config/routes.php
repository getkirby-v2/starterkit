<?php

// register all available routes
return array(

  // Authentication
  array(
    'pattern' => 'login',
    'action'  => 'AuthController::login',
    'filter'  => 'isInstalled',
    'method'  => 'GET|POST'
  ),
  array(
    'pattern' => 'logout',
    'action'  => 'AuthController::logout',
    'method'  => 'GET',
    'filter'  => 'auth',
  ),

  // Installation
  array(
    'pattern' => 'install',
    'action'  => 'InstallationController::index',
    'method'  => 'GET|POST'
  ),

  // Dashboard
  array(
    'pattern' => '/',
    'action'  => 'DashboardController::index',
    'filter'  => array('auth', 'isInstalled'),
  ),

  // Search
  array(
    'pattern' => 'search',
    'action'  => 'SearchController::results',
    'method'  => 'GET|POST',
    'filter'  => array('auth'),
  ),
  
  // Options
  array(
    'pattern' => 'options',
    'action'  => 'OptionsController::index',
    'method'  => 'GET|POST',
    'filter'  => 'auth'
  ),

  // Files
  array(
    'pattern' => array(
      'site(/)file/(:any)/edit',
      'pages/(:all)/file/(:any)/edit',
    ),
    'action'  => 'FilesController::edit',
    'filter'  => 'auth',
    'method'  => 'POST|GET',
  ),
  array(
    'pattern' => array(
      'site(/)file/(:any)/context',
      'pages/(:all)/file/(:any)/context',
    ),
    'action'  => 'FilesController::context',
    'filter'  => 'auth',
    'method'  => 'GET',
  ),
  array(
    'pattern' => array(
      'site(/)file/(:any)/thumb',
      'pages/(:all)/file/(:any)/thumb',
    ),
    'action'  => 'FilesController::thumb',
    'filter'  => 'auth',
    'method'  => 'GET',
  ),
  array(
    'pattern' => array(
      'site(/)file/(:any)/delete',
      'pages/(:all)/file/(:any)/delete',
    ),
    'action'  => 'FilesController::delete',
    'filter'  => 'auth',
    'method'  => 'POST|GET',
  ),
  array(
    'pattern' => array(
      'site(/)file/(:any)/replace',
      'pages/(:all)/file/(:any)/replace',
    ),
    'action'  => 'FilesController::replace',
    'filter'  => 'auth',
    'method'  => 'POST',
  ),
  array(
    'pattern' => array(
      'site(/)files',
      'pages/(:all)/files',
    ),
    'action'  => 'FilesController::index',
    'filter'  => 'auth',
    'method'  => 'POST|GET',
  ),

  // Field routes
  array(
    'pattern' => array(
      'site(/)file/(:any)/field/(:any)/(:any)/(:all)',
      'pages/(:all)/file/(:any)/field/(:any)/(:any)/(:all)',
    ),
    'action' => 'FieldController::forFile', 
    'filter' => 'auth',
    'method' => 'GET|POST'
  ),
  array(
    'pattern' => array(
      'site(/)field/(:any)/(:any)/(:all)',
      'pages/(:all)/field/(:any)/(:any)/(:all)',
    ),
    'action' => 'FieldController::forPage', 
    'filter' => 'auth',
    'method' => 'GET|POST'
  ),
  array(
    'pattern' => array(
      'users/(:all)/field/(:any)/(:any)/(:all)',
    ),
    'action' => 'FieldController::forUser', 
    'filter' => 'auth',
    'method' => 'GET|POST'
  ),

  // New Page
  array(
    'pattern' => array(
      'site(/)add',
      'pages/(:all)/add',
    ),
    'action'  => 'PagesController::add',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // URL Settings
  array(
    'pattern' => 'pages/(:all)/url',
    'action'  => 'PagesController::url',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // Toggle visibility
  array(
    'pattern' => 'pages/(:all)/toggle',
    'action'  => 'PagesController::toggle',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // Delete a page
  array(
    'pattern' => 'pages/(:all)/delete',
    'action'  => 'PagesController::delete',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // Keeping page changes
  array(
    'pattern' => array(
      'site(/)keep',
      'pages/(:all)/keep',
    ),
    'action'  => 'PagesController::keep',
    'method'  => 'GET|POST',
    'filter'  => 'auth',
  ),

  // Discarding page changes
  array(
    'pattern' => array(
      'site(/)discard',
      'pages/(:all)/discard',
    ),
    'action'  => 'PagesController::discard',
    'method'  => 'GET|POST',
    'filter'  => 'auth',
  ),

  // Page context menu
  array(
    'pattern' => 'pages/(:all)/context',
    'action'  => 'PagesController::context',
    'method'  => 'GET',
    'filter'  => 'auth',
  ),

  // Upload a file
  array(
    'pattern' => array(
      'site(/)upload',
      'pages/(:all)/upload',
    ),
    'action'  => 'FilesController::upload',
    'filter'  => 'auth',
    'method'  => 'POST'
  ),

  // Subpages
  array(
    'pattern' => array(
      'site(/)subpages',
      'pages/(:all)/subpages',
    ),
    'action'  => 'SubpagesController::index',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // Page
  array(
    'pattern' => 'pages/(:all)/edit',
    'action'  => 'PagesController::edit',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // Users
  array(
    'pattern' => 'users',
    'action'  => 'UsersController::index',
    'filter'  => 'auth'
  ),
  array(
    'pattern' => 'users/add',
    'action'  => 'UsersController::add',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),
  array(
    'pattern' => 'users/(:any)/edit',
    'action'  => 'UsersController::edit',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),
  array(
    'pattern' => 'users/(:any)/delete',
    'action'  => 'UsersController::delete',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // Avatars
  array(
    'pattern' => 'users/(:any)/avatar',
    'action'  => 'AvatarsController::upload',
    'filter'  => 'auth',
    'method'  => 'POST'
  ),
  array(
    'pattern' => 'users/(:any)/avatar/delete',
    'action'  => 'AvatarsController::delete',
    'filter'  => 'auth',
    'method'  => 'POST|GET'
  ),

  // Autocomplete
  array(
    'pattern' => 'api/autocomplete/(:any)',
    'action'  => 'AutocompleteController::index',
    'method'  => 'POST',
    'filter'  => 'auth',
  ),

  // form assets
  array(
    'pattern' => 'plugins/js',
    'action'  => 'AssetsController::js',
    'method'  => 'GET', 
    'filter'  => 'auth'
  ),
  array(
    'pattern' => 'plugins/css',
    'action'  => 'AssetsController::css',
    'method'  => 'GET',
    'filter'  => 'auth'
  ),

);