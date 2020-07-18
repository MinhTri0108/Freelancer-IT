<?php

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

use Illuminate\Support\Facades\Auth;

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('master', 'HomeController@showMaster')->name('master');

Route::get('login', 'HomeController@getLogin')->name('login');
Route::post('login', 'HomeController@postLogin')->name('login');

Route::get('logout', 'HomeController@getLogout')->name('logout');

Route::get('register', 'HomeController@getRegister')->name('register');
Route::post('register', 'HomeController@postRegister')->name('register');

Route::get('forgotpass', 'HomeController@getForgotPass')->name('forgotpass');
Route::post('forgotpass', 'HomeController@postForgotPass')->name('forgotpass');

Route::get('resetpassword/{token}', 'HomeController@getresetpassword')->name('resetpassword');
Route::post('resetpassword/{token}', 'HomeController@postResetPassword')->name('resetpassword');

Route::get('resendemail', 'HomeController@getResendEmail')->name('resendemail');
Route::post('resendemail', 'HomeController@postResendEmail')->name('resendemail');

Route::get('profile/{id}', 'HomeController@getProfile')->name('profile');
Route::post('profile/{id}', 'HomeController@updateProfile')->name('profile');

Route::post('education', 'HomeController@postEducation')->name('education');
Route::get('education/{id}', 'HomeController@delEducation')->name('education.del');

Route::post('experience', 'HomeController@postExperience')->name('experience');
Route::get('experience/{id}', 'HomeController@delExperience')->name('experience.del');

Route::post('qualification', 'HomeController@postQualification')->name('qualification');
Route::get('qualification/{id}', 'HomeController@delQualification')->name('qualification.del');

Route::get('daskboard', 'HomeController@getDaskboard')->name('daskboard');

Route::get('myproject', 'HomeController@getMyProject')->name('myproject');

Route::get('allproject', 'HomeController@getAllProject')->name('allproject');

Route::get('postproject', 'HomeController@getPostProject')->name('postproject');
Route::post('postproject', 'HomeController@postProject')->name('postproject');

Route::get('projectdetail/{id}', 'HomeController@getProjectDetail')->name('projectdetail');
Route::post('projectdetail/{id}', 'HomeController@postBidding')->name('projectdetail');

Route::get('editbidding/{id}', 'HomeController@getEditBidding')->name('editbidding');
Route::post('editbidding/{id}', 'HomeController@postEditBidding')->name('editbidding');

Route::get('deleteproject/{id}', 'HomeController@deleteProject')->name('deleteproject');

Route::get('deletebidding/{id}', 'HomeController@deleteBidding')->name('deletebidding');

Route::post('milestone', 'HomeController@getMilestone')->name('milestone');

Route::post('choosefl', 'HomeController@chooseFreelancer')->name('choosefl');

Route::post('complete', 'HomeController@postComplete')->name('complete');

Route::post('pay', 'HomeController@postPay')->name('pay');

Route::get('account/{id}', 'HomeController@getAccount')->name('account');
Route::post('account/{id}', 'HomeController@postAccount')->name('account');

Route::get('finace', 'HomeController@getFinace')->name('finace');

Route::post('avatar', 'HomeController@postAvatar')->name('avatar');

Route::prefix('search')->group(function () {
    Route::get('user', 'HomeController@getSearchUser')->name('searchu');
    Route::post('user', 'HomeController@postSearchUser')->name('searchu');
    Route::get('project', 'HomeController@getSearchProject')->name('searchp');
    Route::post('project', 'HomeController@postSearchProject')->name('searchp');
});

Route::post('rating/{id}', 'HomeController@postRating')->name('rating');

Route::post('deposit', 'HomeController@postDeposit')->name('deposit');

Route::get('udbalances/{user_id}/{amount}', 'HomeController@updateUserBalances')->name('udbalances');

Route::post('withdraw', 'HomeController@sendWithdrawRequest')->name('withdraw');

Route::get('checkproject/{id}', 'HomeController@checkCompleteProject')->name('checkproject');

Route::get('testemail', 'HomeController@sendEmail')->name('testmail');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//Admin route
Route::prefix('admin')->group(function () {
    Route::get('master', function () {
        return view('admin.master');
    })->name('admin.master');
    Route::get('login', 'AdminController@getLogin')->name('admin.login');
    Route::post('login', 'AdminController@postLogin')->name('admin.login');

    Route::get('logout', 'AdminController@getLogout')->name('admin.logout')->middleware('checkadminlogin', 'checkchangepw');

    Route::get('changepw', 'AdminController@getChangepw')->name('admin.changepw')->middleware('checkadminlogin');
    Route::post('changepw', 'AdminController@postChangepw')->name('admin.changepw')->middleware('checkadminlogin');

    Route::get('adminmgmt', 'AdminController@getAdminmgmt')->name('admin.adminmgmt')->middleware('checkadminlogin', 'checkchangepw');
    Route::post('adminmgmt', 'AdminController@postAdminmgmt')->name('admin.adminmgmt')->middleware('checkadminlogin', 'checkchangepw');

    Route::get('usermgmt', 'AdminController@getUsermgmt')->name('admin.usermgmt')->middleware('checkadminlogin', 'checkchangepw');
    Route::post('usermgmt', 'AdminController@postUsermgmt')->name('admin.usermgmt')->middleware('checkadminlogin', 'checkchangepw');

    Route::get('projectmgmt', 'AdminController@getProjectmgmt')->name('admin.projectmgmt')->middleware('checkadminlogin', 'checkchangepw');
    Route::post('projectmgmt', 'AdminController@postProjectmgmt')->name('admin.projectmgmt')->middleware('checkadminlogin', 'checkchangepw');

    Route::get('pricemgmt', 'AdminController@getPricemgmt')->name('admin.pricemgmt')->middleware('checkadminlogin', 'checkchangepw');
    Route::post('pricemgmt', 'AdminController@postPricemgmt')->name('admin.pricemgmt')->middleware('checkadminlogin', 'checkchangepw');

    Route::get('skillmgmt', 'AdminController@getSkillmgmt')->name('admin.skillmgmt')->middleware('checkadminlogin', 'checkchangepw');
    Route::post('skillmgmt', 'AdminController@postSkillmgmt')->name('admin.skillmgmt')->middleware('checkadminlogin', 'checkchangepw');

    Route::get('feemgmt', 'AdminController@getFeemgmt')->name('admin.feemgmt')->middleware('checkadminlogin', 'checkchangepw');
    Route::post('feemgmt', 'AdminController@postFeemgmt')->name('admin.feemgmt')->middleware('checkadminlogin', 'checkchangepw');

    Route::get('txnmgmt', 'AdminController@getTxnmgmt')->name('admin.txnmgmt')->middleware('checkadminlogin', 'checkchangepw');
    Route::post('txnmgmt', 'AdminController@postTxnmgmt')->name('admin.txnmgmt')->middleware('checkadminlogin', 'checkchangepw');
});
