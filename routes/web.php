<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\WhatsappUIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function() {
Artisan::call('cache:clear');
Artisan::call('config:cache');
Artisan::call('view:clear');
return "Cleared!";
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('/dashboard/data', 'HomeController@ajaxDashboard')
    ->name('dashboard.data');

Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::get('/notifications', 'NotificationController@index')->name('notifications.index')->middleware('auth');
Route::get('/notifications/filter', [NotificationController::class, 'filter'])->name('notifications.filter');
Route::get('/notifications/list', [NotificationController::class, 'list'])->name('notifications.list');
Route::get('/notifications/delete/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
Route::get('/notifications/delete-all', [NotificationController::class, 'deleteAll']);
Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
Route::post('/notifications/settings', [NotificationController::class, 'storePreferences'])->name('notifications.settings')->middleware('auth');
Route::get('/notifications/settingsget', [NotificationController::class, 'getPreferences'])->name('notifications.get');



Route::delete('/errors/{id}', [ErrorController::class, 'destroy'])->name('errors.destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
Route::get('/countries/{id}','HomeController@countries')->name('countries');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('filtercities','HomeController@cities')->name('filtercities');
Route::get('zone/{id}','HomeController@zones')->name('zones');


Route::get('/profil','UserController@profile')->name('profil');
Route::post('/profil','UserController@updateprofile')->name('updateprofile');

Route::group(['prefix'=>'stores','as'=>'stores.', 'middleware'=>['auth']], function(){
    Route::get('/', 'StoreController@index')->name('index');
    Route::get('/create', 'StoreController@create')->name('create');
    Route::post('/store', 'StoreController@store')->name('store');
    Route::get('/edit/{id}', 'StoreController@edit')->name('edit');
    Route::post('/update/{id}', 'StoreController@update')->name('update');
    Route::get('/delete/{id}', 'StoreController@destroy')->name('delete');
    //Route::delete('/multidelete', 'CitieController@multidelete')->name('multidelete');
});

Route::get('/lastmile/businesses', 'WarehouseController@businessLastMile')
    ->middleware('auth')
    ->name('lastmile.businesses');

Route::group(['prefix'=>'products','as'=>'products.', 'middleware'=>['auth']], function(){
    Route::get('/', 'ProductController@index')->name('index');
    Route::get('/create', 'ProductController@create')->name('create');
    Route::post('/store', 'ProductController@store')->name('store');
    Route::get('/{id}/edit/', 'ProductController@edit')->name('edit');
    Route::post('/update', 'ProductController@update')->name('update');
    Route::get('/delete/{id}', 'ProductController@destroy')->name('delete');
    Route::get('imports/{id}','ProductController@imports')->name('imports');
    Route::get('warehouses/{id}','ProductController@stocks')->name('stocks');
    Route::post('warehouses/store','ProductController@warehousestore')->name('warehousestore');
    Route::get('warehousesstock/{id}','ProductController@warehousesstock')->name('warehousesstock');
    Route::get('stockedit/{id}','ProductController@stockedit')->name('stockedit');
    Route::post('stockupdate/{id}','ProductController@stockupdate')->name('stockupdate');
    Route::post('statusconf/{id}','ProductController@statusconf')->name('statusconf');
    Route::get('stockdelete/{id}','ProductController@stockdelete')->name('stockdelete');
    Route::post('/upsel','ProductController@upsel')->name('upsel');
    Route::get('/upsells/{id}','ProductController@upsells')->name('upsells');
    Route::get('/{id}/upsells/edit','ProductController@editupsells')->name('editupsells');
    Route::post('/upsells/update','ProductController@updateupsells')->name('updateupsells');
    Route::post('/upsells/delete','ProductController@deleteupsells')->name('deleteupsells');
    Route::get('/{id}/price','ProductController@price')->name('price');
    Route::get('/assigned/{id}','ProductController@assignedList')->name('assigned');
    Route::post('/assigned/store','ProductController@assignedstore')->name('assigned.store');
    Route::get('/assigned-delete/{id}','ProductController@assigneddelete')->name('assigneddelete');
    //warehousing
    Route::get('/process-pick','WarehouseController@productfordelivred')->name('productfordelivred');
    Route::get('/search-process','WarehouseController@searchprocess')->name('searchprocess');
    Route::get('/check-process','WarehouseController@check')->name('check');
    Route::post('/out-stock','WarehouseController@outstock')->name('outstock');
    Route::get('/pack','WarehouseController@pack')->name('pack');
    Route::get('/ship','WarehouseController@ship')->name('ship');

    //api
    Route::post('/apiorder', ['uses' => 'WarehouseController@apiorder'])->name('apiorder');
    //api apring
    Route::post('/springapiorder', ['as' => 'springapiorder', 'uses' => 'WarehouseController@springapiorder']);
    //api brt
    Route::post('/brtapiorder', ['as' => 'brtapiorder', 'uses' => 'WarehouseController@brtapiorder']);

     //glsapiorder
    Route::post('/glsapiorder', ['as' => 'glsapiorder', 'uses' => 'WarehouseController@glsapiorder']);

    Route::get('/pack/last-download/{array}','WarehouseController@lastdownload')->name('lastdownload');
    Route::get('/rollBackFromShipping/{id}','WarehouseController@rollBackFromShipping')->name('rollBackFromShipping');
    Route::get('/multi-send-for-shipped','WarehouseController@multisendforshipped')->name('multisendforshipped');
    Route::get('/send-for-shipped','WarehouseController@sendforshipped')->name('sendforshippeds');
    Route::post('/listproduct','WarehouseController@listproduct')->name('listproduct');
    Route::get('/list-product','WarehouseController@listpro')->name('listpro');
    //return in pack
    Route::get('/return-in-pack', 'WarehouseController@returninpack')->name('returninpack');
    //send to delivery
    Route::post('/send-delivery','WarehouseController@sendtodelivery')->name('sendtodelivery');
});


Route::group(['middleware' => ['auth']], function() {
    Route::get('/pack/get-sellers', 'WarehouseController@getSellers')->name('pack.getSellers');
    Route::get('/pack/get-seller-lastmiles', 'WarehouseController@getSellerLastMiles')->name('pack.getSellerLastMiles');
    Route::get('/pack/get-orders', 'WarehouseController@getOrders')->name('pack.getOrders');
    Route::get('/pack/last-mile-modal', 'WarehouseController@lastMileModal')->name('pack.lastMileModal');
});


Route::group(['prefix'=>'leads','as'=>'leads.', 'middleware'=>['auth']], function(){
    Route::get('/', 'LeadController@index')->name('index');
    Route::get('/single', 'LeadController@single')->name('single');
    Route::get('/search', 'LeadController@search')->name('search');
    Route::get('/leadsearch', 'LeadController@leadsearch')->name('leadsearch');
    Route::get('/create', 'LeadController@create')->name('create');
    Route::get('/refresh', 'LeadController@refresh')->name('refresh');
    Route::post('/store', 'LeadController@store')->name('store');
    Route::post('/upsell', 'LeadController@upsellstore')->name('upsellstore');
    Route::post('/multi-upsell', 'LeadController@multiupsell')->name('multiupsell');
    Route::get('/packages', 'LeadController@packages')->name('packages');
    Route::get('/edit/{id}', 'LeadController@edit')->name('edit');
    Route::get('/{id}/details', 'LeadController@details')->name('details');
    Route::get('/{id}/infoupsell', 'LeadController@infoupsell')->name('infoupsell');
    Route::get('/{id}/seacrhdetails', 'LeadController@seacrhdetails')->name('seacrhdetails');
    Route::get('/detailspro', 'LeadController@detailspro')->name('detailspro');
    Route::post('/update', 'LeadController@update')->name('update');
    Route::get('/delete/{id}', 'LeadController@destroy')->name('delete');
    Route::post('/lead/update-customer','LeadController@updateCustomer')->name('updateCustomer');
    Route::post('/status-confirmation', 'LeadController@statuscon')->name('statuscon');
    Route::post('/confirmed', 'LeadController@confirmed')->name('confirmed');
    Route::post('/canceled', 'LeadController@canceled')->name('canceled');
    Route::get('/duplicated/{id}', 'LeadController@duplicated')->name('duplicated');
    Route::get('/horzone/{id}', 'LeadController@horzone')->name('horzone');
    Route::post('/wrong', 'LeadController@wrong')->name('wrong');
    Route::post('/outof-stock', 'LeadController@outofstocks')->name('outofstocks');
    Route::post('/date', 'LeadController@date')->name('date');
    Route::post('/note-status', 'LeadController@notestatus')->name('notestatus');
    Route::get('/settings', 'LeadController@settings')->name('settings');
    Route::get('/seehistory', 'LeadController@history')->name('seehistory');
    Route::post('/statc', 'LeadController@statusc')->name('statusc');
    Route::post('/statctest', 'LeadController@statusctest')->name('statusctest');
    Route::post('/call', 'LeadController@call')->name('call');
    Route::post('/restore-old-price', 'LeadController@restoreOldPrice')->name('restore');

    //list Upsell
    Route::get('/{id}/listupsell', 'LeadController@listupsell')->name('listupsell');
    //delete upsell
    Route::get('/deleteupsell/{id}','LeadController@deleteupsell')->name('deleteupsell');
    //update price
    Route::post('/update-price','LeadController@updateprice')->name('updateprice');
    //no answer call
    Route::get('/another-leads','LeadController@another')->name('another');
    //out of stock
    Route::get('/out-of-stock','LeadController@outofstock')->name('outofstock');
    //export
    Route::post('/exports', 'LeadController@exports')->name('exports');
    Route::get('/export-downloads/{array}', 'LeadController@downloads')->name('downloads');
    //refresh data
    Route::get('/refresh-data/{id}', 'LeadController@refresh')->name('refresh');
    //export cheked

    //call later
    Route::get('/call-later','LeadController@calllater')->name('calllater');
    Route::get('/lead-duplicated','LeadController@leadduplicated')->name('leadduplicated');
    Route::get('/lead-horzone','LeadController@leadhorzone')->name('leadhorzone');
    Route::get('/lead-wrong','LeadController@leadwrong')->name('leadwrong');
    Route::get('/lead-canceled','LeadController@leadcanceled')->name('leadcanceled');
    Route::get('/lead-canceled-by-system','LeadController@canceledbysystem')->name('canceledbysystem');
    //no answer list
    Route::get('/noanswer-list','LeadController@noanswer')->name('noanswer');

    //list order client
    Route::get('/client/{id}','LeadController@client')->name('client');
    //inassigned
    Route::get('/inassigned-lead','LeadController@inassigned')->name('inassigned');
    //discount
    Route::post('/discount','LeadController@discount')->name('discount');

    //orders incident
    Route::get('/orders/incident','LeadController@incident')->name('incident');
    Route::get('/orders/delivery-man','LeadController@deliveryman')->name('orders.deliveryman');
    Route::post('/orders/assigned','WarehouseController@assignedOrder')->name('assignedorder');

    Route::get('/rejected','LeadController@rejected')->name('rejected');
    Route::post('/blacklist','LeadController@blackList')->name('blacklist');
    //prepaid
    Route::get('/prepaid','LeadController@prepaid')->name('prepaid');
    Route::get('/list-confirmed','LeadController@listconfirmed')->name('listconfirmed');

    //roll back to pick
    Route::get('/roll-back-pick/{id}','WarehouseController@rollback')->name('rollback');
    Route::get('/multi-roll-back-pick','WarehouseController@multiRollBack')->name('multirollback');
    //back to call center
    Route::post('/back-to-call','LeadController@call')->name('call');
    Route::get('/rollBacktoShipping/{id}','LeadController@rollBacktoShipping')->name('rollBacktoShipping');


    Route::get('/picking/{id}','LeadController@pickingorder')->name('pickingorder');
    Route::post('/picking-list','LeadController@picklist')->name('picklist');
    Route::post('/outstock','LeadController@outstock')->name('outstock');
});

Route::group(['prefix'=>'analyses','as'=>'analyses.', 'middleware'=>['auth']], function(){
    Route::get('/', 'AnalyseController@index')->name('index');
    // Route::get('/agent-status/history/{agentId}', [AgentStatusController::class, 'getAgentHistory']);
    Route::get('/agent/details','AnalyseController@details')->name('agents.details');
   

});

    //relance lead
Route::group(['prefix'=>'relancements','as'=>'relancements.', 'middleware'=>['auth']], function(){
    Route::get('/{id}', 'RelancementController@index')->name('index');
    Route::get('/create', 'RelancementController@create')->name('create');
    Route::post('/store/{id}', 'RelancementController@store')->name('store');
});

Route::group(['prefix'=>'reclamations','as'=>'reclamations.', 'middleware'=>['auth']], function(){
    Route::get('/', 'ReclamationController@index')->name('index');
    Route::get('/create', 'ReclamationController@create')->name('create');
    Route::post('/store', 'ReclamationController@store')->name('store');
    Route::get('/{id}/edit', 'ReclamationController@edit')->name('edit');
    Route::post('/update/{id}', 'ReclamationController@update')->name('update');
    Route::post('/delete', 'ReclamationController@destroy')->name('delete');
    Route::get('/download', 'ReclamationController@download')->name('download');
    Route::post('/statuscon', 'ReclamationController@statuscon')->name('statuscon');
});

Route::group(['prefix'=>'sheets','as'=>'sheets.', 'middleware'=>['auth']], function(){
    Route::get('/', 'ImportController@index')->name('index');
    Route::get('/create', 'ImportController@create')->name('create');
    Route::post('/store', 'ImportController@store')->name('store');
    Route::get('/edit/{id}', 'ImportController@edit')->name('edit');
    Route::post('/update/{id}', 'ImportController@update')->name('update');
    Route::get('/delete/{id}', 'ImportController@destroy')->name('delete');
    Route::get('/download', 'ImportController@download')->name('download');
});

Route::group(['prefix'=>'imports','as'=>'imports.', 'middleware'=>['auth']], function(){
    Route::get('/', 'ImportController@index')->name('index');
    Route::get('/create', 'ImportController@create')->name('create');
    Route::post('/store', 'ImportController@store')->name('store');
    Route::get('/edit/{id}', 'ImportController@edit')->name('edit');
    Route::post('/update/{id}', 'ImportController@update')->name('update');
    Route::get('/delete/{id}', 'ImportController@destroy')->name('delete');
    Route::get('/download', 'ImportController@download')->name('download');
});

Route::group(['prefix'=>'Staff','as'=>'Staff.', 'middleware'=>['auth']], function(){
    Route::get('/', 'UserController@index')->name('index');
    Route::get('/create', 'UserController@create')->name('create');
    Route::post('/store', 'UserController@store')->name('store');
    Route::post('/inactive', 'UserController@inactive')->name('inactive');
    Route::post('/active', 'UserController@active')->name('active');
    Route::get('/edit/{id}', 'UserController@edit')->name('edit');
    Route::post('/update/{id}', 'UserController@update')->name('update');
    Route::get('/delete/{id}', 'UserController@destroy')->name('delete');
    Route::get('/download', 'UserController@download')->name('download');
    Route::get('/roles', 'UserController@roles')->name('roles');
    Route::post('/roles/store', 'UserController@rolestore')->name('rolestore');
    Route::get('/permission', 'UserController@permissions')->name('permissions');
    Route::post('/permission/store', 'UserController@permissionstore')->name('permissionstore');
    Route::post('/resetpassword', 'UserController@reset')->name('reset');
    Route::get('/performence/{id}', 'UserController@performence')->name('performence');
    Route::post('/export','UserController@export')->name('export');
    Route::get('/export-download/{array}','UserController@exportdownload')->name('exportdownload');
});

Route::group(['prefix'=>'reclamations','as'=>'reclamations.', 'middleware'=>['auth']], function(){
    Route::get('/', 'ReclamationController@index')->name('index');
    Route::get('/create', 'ReclamationController@create')->name('create');
    Route::post('/store', 'ReclamationController@store')->name('store');
    Route::get('/{id}/edit', 'ReclamationController@edit')->name('edit');
    Route::post('/update/{id}', 'ReclamationController@update')->name('update');
    Route::post('/delete', 'ReclamationController@destroy')->name('delete');
    Route::get('/download', 'ReclamationController@download')->name('download');
});

Route::group(['prefix'=>'statistics','as'=>'statistics.', 'middleware'=>['auth']], function(){
    Route::get('/', 'StatisticController@index')->name('index');
    Route::get('/create', 'StatisticController@create')->name('create');
    Route::post('/store', 'StatisticController@store')->name('store');
    Route::get('/{id}/edit', 'StatisticController@edit')->name('edit');
    Route::post('/update/{id}', 'StatisticController@update')->name('update');
    Route::post('/delete', 'StatisticController@destroy')->name('delete');
    Route::get('/download', 'StatisticController@download')->name('download');
});

Route::group(['prefix'=>'settings','as'=>'settings.', 'middleware'=>['auth']], function(){
    Route::get('/', 'SettingController@index')->name('index');
    Route::post('/store', 'SettingController@store')->name('store');
    Route::post('/update', 'SettingController@update')->name('update');
});





Route::group(['prefix'=>'callcenter','as'=>'callcenter.', 'middleware'=>['auth']], function(){
    Route::get('/', 'CallcenterController@index')->name('index');
    Route::get('/details/{id}', 'CallcenterController@details')->name('details');
    Route::post('/store', 'CallcenterController@store')->name('store');
    Route::post('/filter', 'CallcenterController@filter')->name('filter');
    Route::get('/{id}/edit', 'CallcenterController@edit')->name('edit');
    Route::post('/update/{id}', 'CallcenterController@update')->name('update');
});

Route::get('/exports','LeadController@exportall')->name('exportall');

Route::group(['prefix'=>'suivi','as'=>'suivi.', 'middleware'=>['auth']], function(){
    Route::get('/', 'SuiviController@index')->name('index');
});


//whatsapp 

Route::group(['prefix' => 'whatsapp-template','as' => 'whatsapp-template.','middleware' => ['auth']], function() {
    Route::get('/', [WhatsappUIController::class, 'index'])
          ->name('index');
    Route::get('/get-accounts', [WhatsappUIController::class, 'getAccounts'])
        ->name('get-accounts');
    
    Route::get('/get-conversations', [WhatsappUIController::class, 'getConversations'])
        ->name('get-conversations');
    
    Route::get('/get-messages', [WhatsappUIController::class, 'getMessages'])
        ->name('get-messages');
    
    Route::post('/send-message', [WhatsappUIController::class, 'sendMessage'])
        ->name('send-message');
    
    Route::post('/conversations/{conversation}/mark-as-read', [WhatsappUIController::class, 'markAsRead'])
        ->name('mark-as-read');
    
    Route::get('/media/{filename}', [WhatsappUIController::class, 'getFile'])
        ->name('get-file');

    Route::post('/check-conversation', [WhatsappUIController::class, 'checkConversation'])
    ->name('check-conversation');

    Route::post('/check-contact', [WhatsappUIController::class, 'checkContact'])
    ->name('check-contact');

    Route::put('/update-contact/{conversation}', [WhatsappUIController::class, 'updateContact'])
        ->name('update-contact');

    Route::delete('/conversations/{conversation}', [WhatsappUIController::class, 'destroyConversation'])
        ->name('destroy-conversation');

    Route::get('/get-contact-details/{phoneNumber}', [WhatsappUIController::class, 'getContactDetails'])->name('contact-details');
    Route::post('/update-contact-details/{id}', [WhatsappUIController::class, 'updateContactDetails'])->name('update-contact-details');

    Route::post('/messages/delete', [WhatsappUIController::class, 'deleteMessage'])->name('whatsapp-template.messages.delete');
    Route::post('/conversations/{conversation}/block', [WhatsappUIController::class, 'blockConversation']);
    Route::post('/conversations/{conversation}/unblock', [WhatsappUIController::class, 'unblockConversation']);
    Route::get('/labels', [WhatsappUIController::class, 'getLabels']);
    Route::post('/labels', [WhatsappUIController::class, 'createLabel']);
    Route::delete('/labels/{id}', [WhatsappUIController::class, 'deleteLabel']);
    Route::get('/labels/conversations', [WhatsappUIController::class, 'getConversationsByLabel']);
    Route::post('/conversations/{conversation}/labels/assign', [WhatsappUIController::class, 'assignLabels']);
    Route::post('/store-lead', [WhatsappUIController::class,'storeLead'])->name('storeLead');
    Route::post('/send-template', [WhatsappUIController::class, 'sendTemplate'])->name('send-template');
    Route::get('/templates', [WhatsappUIController::class, 'getTemplates'])->name('templates');
});
