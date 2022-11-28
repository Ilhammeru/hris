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

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

// route for general user
Route::get('/vacancy/{id}/general', 'RecruitmentController@showForGeneral')->name('employee.recruitment.show.general');
Route::get('/vacancy/apply/{id}/general', 'RecruitmentController@applyForm')->name('employee.recruitment.apply-form');
Route::post('/vacancy/apply/{id}', 'RecruitmentController@applyJob')->name('employee.recruitment.apply-job');
Route::post('/indonesia-region', [GeneralController::class, 'getCityByProvince'])->name('get-city-by-province');
Route::post('/indonesia-region/district', [GeneralController::class, 'getDistrictByCity'])->name('get-district-by-city');
Route::post('/indonesia-region/village', [GeneralController::class, 'getVillageByDistrict'])->name('get-village-by-district');
Route::post('/vacancy/{id}/send-message/general', 'RecruitmentController@sendMessage')->name('employee.recruitment.send.message');
Route::get('/apply-success', function() {
    $pageTitle = 'Success';
    return view('recruitment::apply-success', compact('pageTitle'));
})->name('apply-success');

Route::middleware('auth')->group(function() {
    Route::get('/recruitment/ajax', 'RecruitmentController@ajax')->name('employee.recruitment.ajax');
    Route::post('/recruitment/tag', 'RecruitmentController@storeTag')->name('employee.recruitment.add.tag');
    Route::get('/recruitment/tag/{id}', 'RecruitmentController@getTag')->name('employee.recruitment.get-tag');
    Route::get('/recruitment/publish/{id}', 'RecruitmentController@publish')->name('employee.recruitment.publish');
    Route::get('/recruitment/detail-vacancy-applicant/{id}', 'RecruitmentController@detailVacancyApplicant')->name('employee.recruitment.detail-vacancy-applicant');
    Route::get('/recruitment/detail-vacancy-applicant/ajax/{id}', 'RecruitmentController@ajaxApplicant')->name('employee.recruitment.ajax.applicant');
    Route::post('/recruitment/accept-applicant', 'RecruitmentController@acceptApplicant')->name('employee.recruitment.accept-applicant');
    Route::post('/recruitment/send-notif-to-applicant', 'RecruitmentController@acceptApplicant')->name('employee.recruitment.send-notif-to-applicant');
    Route::get('/recruitment/view-cv/{id}', 'RecruitmentController@viewApplicantCv')->name('employee.recruitment.cv.applicant');
    Route::resource('/recruitment', 'RecruitmentController',[
        'names' => [
            'index' => 'employee.recruitment',
            'store' => 'employee.recruitment.store',
            'show' => 'employee.recruitment.show',
            'create' => 'employee.recruitment.create',
            'edit' => 'employee.recruitment.edit',
            'destroy' => 'employee.recruitment.update',
            'destroy' => 'employee.recruitment.delete',
        ]
    ]);

    // recruitment setting
    Route::get('/recruitment-setting/ajax', 'RecruitmentSettingController@ajax')->name('employee.recruitment-setting.ajax');
    Route::get('/recruitment-setting/get-recruitment-step', 'RecruitmentSettingController@getRecruitmentStep')->name('employee.recruitment-setting.get-recruitment-step');
    Route::get('/recruitment-setting/get-notification-setup/{id}', 'RecruitmentSettingController@getNotificationSetup')->name('employee.recruitment-setting.get-notification-setup');
    Route::resource('/recruitment-setting', 'RecruitmentSettingController', [
        'names' => [
            'index' => 'employee.recruitment-setting',
            'store' => 'employee.recruitment-setting.store',
            'show' => 'employee.recruitment-setting.show',
            'edit' => 'employee.recruitment-setting.edit',
            'update' => 'employee.recruitment-setting.update',
            'destroy' => 'employee.recruitment-setting.delete',
        ]
    ]);
});
