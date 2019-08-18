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

Route::get('/', 'Auth\LoginController@loginpage')->name('login');

Auth::routes();

Route::get('/login', 'Auth\LoginController@loginpage')->name('login');
Route::get('/register', 'Auth\LoginController@registerpage')->name('register');
Route::get('/dashboard', 'DashboardController@index')->name('index')->middleware('auth');


Route::resource('historico', 'HistoricoController')->middleware('auth');
 Route::get('newhistorico', 'HistoricoController@create')->name('newhistorico')->middleware('auth');
 Route::get('edithistorico', 'HistoricoController@edit')->name('edithistorico')->middleware('auth');
 Route::get('excelhistorico', 'HistoricoController@export')->middleware('auth');
 Route::get('pdfhistorico', ['as'=>'HtmlToPDF','uses'=>'HistoricoController@report'])->middleware('auth');

Route::resource('eventos', 'CalendarioController')->middleware('auth');
 Route::get('calendario', 'CalendarioController@agenda')->name('agenda')->middleware('auth');
 Route::get('newcalendario', 'CalendarioController@create')->name('newcalendario')->middleware('auth');
 Route::get('editcalendario', 'CalendarioController@edit')->name('editcalendario')->middleware('auth');
 Route::get('excelcalendario', 'CalendarioController@export')->middleware('auth');
 Route::get('pdfcalendario', ['as'=>'HtmlToPDF','uses'=>'CalendarioController@report'])->middleware('auth');

Route::resource('motorista', 'MotoristaController')->middleware('auth');
 Route::get('newmotorista', 'MotoristaController@create')->name('newmotorista')->middleware('auth');
 Route::get('editmotorista', 'MotoristaController@edit')->name('editmotorista')->middleware('auth');
 Route::get('excelmotorista', 'MotoristaController@export')->middleware('auth');
 Route::get('pdfmotorista', ['as'=>'HtmlToPDF','uses'=>'MotoristaController@report'])->middleware('auth');

Route::resource('user', 'UserController')->middleware('auth');
 Route::get('newuser', 'UserController@create')->name('newuser')->middleware('auth');
 Route::get('edituser', 'UserController@edit')->name('edituser')->middleware('auth');
 Route::get('exceluser', 'UserController@export')->middleware('auth');
 Route::get('pdfuser', ['as'=>'HtmlToPDF','uses'=>'UserController@report'])->middleware('auth');

Route::resource('faturamento', 'FaturamentoController')->middleware('auth');
 Route::get('newfaturamento', 'FaturamentoController@create')->name('newfaturamento')->middleware('auth');
 Route::get('editfaturamento', 'FaturamentoController@edit')->name('editfaturamento')->middleware('auth');
 Route::get('excelfaturamento', 'FaturamentoController@export')->middleware('auth');
 Route::get('pdffaturamento', ['as'=>'HtmlToPDF','uses'=>'FaturamentoController@report'])->middleware('auth');
 Route::get('pdffaturamentocliente', ['as'=>'HtmlToPDF','uses'=>'FaturamentoController@client'])->middleware('auth');

Route::resource('encargo', 'EncargoController')->middleware('auth');
 Route::get('newencargo', 'EncargoController@create')->name('newencargo')->middleware('auth');
 Route::get('editencargo', 'EncargoController@edit')->name('editencargo')->middleware('auth');
 Route::get('excelencargo', 'EncargoController@export')->middleware('auth');
 Route::get('pdfencargo', ['as'=>'HtmlToPDF','uses'=>'EncargoController@report'])->middleware('auth');

Route::resource('imposto', 'ImpostoController')->middleware('auth');
 Route::get('newimposto', 'ImpostoController@create')->name('newimposto')->middleware('auth');
 Route::get('editimposto', 'ImpostoController@edit')->name('editimposto')->middleware('auth');
 Route::get('excelimposto', 'ImpostoController@export')->middleware('auth');
 Route::get('pdfimposto', ['as'=>'HtmlToPDF','uses'=>'ImpostoController@report'])->middleware('auth');

Route::resource('imposto-encargo', 'ImpostoEncargoController')->middleware('auth');
 Route::get('newimposto-encargo', 'ImpostoEncargoController@create')->name('newimposto-encargo')->middleware('auth');
 Route::get('editimposto-encargo', 'ImpostoEncargoController@edit')->name('editimposto-encargo')->middleware('auth');
 Route::get('excelimposto-encargo', 'ImpostoEncargoController@export')->middleware('auth');
 Route::get('pdfimposto-encargo', ['as'=>'HtmlToPDF','uses'=>'ImpostoEncargoController@report'])->middleware('auth');

Route::resource('tag', 'TagController')->middleware('auth');
 Route::get('newtag', 'TagController@create')->name('newtag')->middleware('auth');
 Route::get('edittag', 'TagController@edit')->name('edittag')->middleware('auth');
 Route::get('exceltag', 'TagController@export')->middleware('auth');
 Route::get('pdftag', ['as'=>'HtmlToPDF','uses'=>'TagController@report'])->middleware('auth');

Route::resource('subtag', 'SubtagController')->middleware('auth');
 Route::get('newsubtag', 'SubtagController@create')->name('newsubtag')->middleware('auth');
 Route::get('editsubtag', 'SubtagController@edit')->name('editsubtag')->middleware('auth');
 Route::get('excelsubtag', 'SubtagController@export')->middleware('auth');
 Route::get('pdfsubtag', ['as'=>'HtmlToPDF','uses'=>'SubtagController@report'])->middleware('auth');


Route::get('relatorio', ['as'=>'HtmlToPDF','uses'=>'RelatorioController@report'])->middleware('auth');

Route::get('logout', 'Auth\LoginController@logout');

Route::get('home', function () {
    return redirect('dashboard');
});

 //POSTs  ------------------------------------------------------------------------------------------------
Route::post('newevent', [
    'uses' => 'CalendarioController@newevent'
])->middleware('auth');
Route::post('deleteevent', [
    'uses' => 'CalendarioController@deleteevent'
])->middleware('auth');

