<?php

use App\Http\Controllers\AddProduct;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthTokenControoler;
use App\Http\Controllers\CardProducts;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\FrequentlyAskedQuestions;
use App\Http\Controllers\FrequentlyAskedQuestionsTableController;
use App\Http\Controllers\GEtTables;
use App\Http\Controllers\hock2;
use App\Http\Controllers\LinkedProductController;
use App\Http\Controllers\logout;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrgnazationProfile;
use App\Http\Controllers\pointOfSaleController;
use App\Http\Controllers\RefreshController;
use App\Http\Controllers\SallaProduct;
use App\Http\Controllers\SallaProducts;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubscriptionControoller;
use App\Http\Controllers\TestMiddle;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TiketMessageController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Notifications\LowBlance;
use App\Notifications\ReplyTicket;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Facades\Route;

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

Route::resource('FAQ2', FrequentlyAskedQuestionsTableController::class);

Route::resource('FAQ', FrequentlyAskedQuestions::class);

/*
Admin Route ______________________________--
 */

Route::middleware(['auth:client', 'subscription'])->get('role', [TestMiddle::class, 'index']);

Route::prefix('admin')->group(function () {
    Route::get('', [AdminController::class, 'index']);
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('client', ClientController::class);
    // Route::get('ticket-support', [TicketController::class, 'index'])->name('admin.technical.support');
});

Route::post('Salasearch', [SearchController::class, 'Salasearch'])->name('search');
Route::post('Cardsearch', [SearchController::class, 'Cardsearch'])->name('cardsearch');
Route::get('welecome', [GEtTables::class, 'Productscode']);
Route::post('', [GEtTables::class, 'ProductStore'])->name('product.store');

Route::get('home', function () {
    return view('Admin.home');
});

Route::get('salla-product', [SallaProducts::class, 'index'])->name('SallaProduct');

Route::get('notification', function () {
    return view('Admin.notification');
});

Route::get('notification', function () {
    return view('Admin.notification');
});

Route::get('subscription', function () {
    return view('Admin.subscription');
});

Route::get('', [LinkedProductController::class, 'LinkProduct']);

// Route::get('', function () {
//     return view('Admin.SallaProduct.Add New.Add');
// })->;

Route::get('/webhock', [AuthTokenControoler::class, 'getTokenWithCode']);
Route::get('/webhock2', [hock2::class, 'hock2']);
Route::post('/webhock2', [hock2::class, 'hock2']);

Route::get('order-history', [OrderController::class, 'OrderHistory'])->name('OrderHistory');
Route::get('login', [AuthController::class, 'getLoginFrom'])->middleware('guest');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:client')->group(function () {
    Route::get('Add-products', [AddProduct::class, 'index'])->name('Add.product');
    Route::post('add-order', [AddProduct::class, 'store'])->name('add.order');

    Route::get('botagaty-card', [CardProducts::class, 'index'])->name('Card');
    Route::get('/', [Dashboard::class, 'index'])->name('home');
    Route::get('dashboard', [Dashboard::class, 'index'])->name('Dashboard');
    // Link Product                         ############################################
    Route::get('related-product', [SallaProducts::class, 'selectedProduct'])->name('related.Products');
    Route::get('delete-related-product/{id}', [SallaProduct::class, 'DeletedRelatedProduct'])->name('DeletedRelatedProduct');
    Route::get('link-products', [LinkedProductController::class, 'LinkProduct'])->name('link.product');
    // Tickt And Ticknical Support          ########################################
    Route::get('tick-support', [TicketController::class, 'index'])->name('technical.support');
    Route::get('create-ticket', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('store-tickt', [TicketController::class, 'store'])->name('ticket.store');
    Route::get('show-ticket-message/{id}', [TicketController::class, 'show'])->name('ShowMssages');
    Route::post('store-message/{id}', [TiketMessageController::class, 'store'])->name('store.message');

    // Setting Mangment ###################################################
    Route::get('setting', [SettingController::class, 'getSeting'])->name('setting');
    Route::post('saveSetting', [SettingController::class, 'update'])->name('SaveSetting');
    Route::get('point-of-sale', [pointOfSaleController::class, 'index']);
    Route::get('get-products', [pointOfSaleController::class, 'Products']);
    Route::get('getblance', [GEtTables::class, 'getblance']);
    Route::post('GetOneProdectFromPosToSalla', [GEtTables::class, 'GetOneProdectFromPosToSalla'])->name("GetOneProdectFromPosToSalla");
    // Route::get('product-code', [GEtTables::class, 'ProductCode']);
    Route::post('product-code', [GEtTables::class, 'ProductCodeStore'])->name('product.code.store');
    Route::get('refresh-product', [RefreshController::class, 'Product'])->name('refresh.product');
    Route::get('refresh-pos-product', [RefreshController::class, 'PosProduct'])->name('refresh.pos.product');
});
Route::post('logout', [logout::class, 'logout'])->name('logout');

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {
    Route::name('admin.')->prefix('admin')->group(function () {
        Route::get('ticket-support', [TicketController::class, 'index'])->name('technical.support');
        Route::get('create-ticket', [TicketController::class, 'create'])->name('ticket.create');
        Route::post('store-ticket', [TicketController::class, 'store'])->name('ticket.store');
        Route::get('show-ticket-message/{id}', [TicketController::class, 'show'])->name('ShowMssages');
        Route::post('store-message/{id}', [TiketMessageController::class, 'store'])->name('store.message');
        Route::get('subscription', [SubscriptionControoller::class, 'index'])->name('subscription.index');
        Route::get('orgnization-profile', [OrgnazationProfile::class, 'get'])->name('orgnazition.profile');
        Route::post('orgnization-profile', [OrgnazationProfile::class, 'stroe'])->name('store.orgnazition.profile');
        Route::resource('users', UserController::class);
    });
});

Route::middleware('auth')->get('exception', function () {
    $arry = ['sad'];
    throw new Exception('Route Get Exception');
    return $arry[2];
});

Route::get('sendnotification', function () {
    $user = User::first();
    //  FacadesNotification::route('mail', 'me@abdulrehman.pk')->notify(new OrderDoneSuccessfuly(['jksa', '23', '123S']));
    FacadesNotification::send($user, new TickitCreated(1));
    Notification::send($user, new ReplyTicket(1));
});

Route::get('low-blance', function () {
    $user = User::first();
    FacadesNotification::send($user, new LowBlance(2));
});

// Route::get('event', function () {
//     event(new BuyOrderByDashboard('jksa', '22'));
// });
Route::get('soild/{class}', [SolidPrinciple::class, 'index']);

Route::middleware('auth:client')->get('/log', function () {
    throw new Exception('test The Login');
    Log::channel('userLog')->info('jksa alrifnai');
    UserLog('conteont', 'log');
    dd('klsa');
});

Route::get('low-blance', function () {
    $user = User::first();
    FacadesNotification::send($user, new LowBlance(2));
});

// Route::get('event', function () {
//     event(new BuyOrderByDashboard('jksa', '22'));
// });
