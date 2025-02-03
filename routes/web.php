<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElasticsearchController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\S3Controller;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/elasticsearch/index', [ElasticsearchController::class, 'index']);
Route::get('/elasticsearch/search', [ElasticsearchController::class, 'search']);
Route::get('/elasticsearch/form', function () {
    return view('elasticsearch.form');
});
Route::get('/send-mail-view', [MailController::class, 'sendMailView']);
Route::get('/send-mail', [MailController::class, 'sendMail']);
Route::get('/new-view', function () {
    return view('new-view');
});

Route::get('/s3/upload', [S3Controller::class, 'showUploadForm']);
Route::post('/s3/upload', [S3Controller::class, 'uploadFile']);
Route::get('/s3/files', [S3Controller::class, 'listFiles']);
Route::post('/s3/delete', [S3Controller::class, 'deleteFile']);
