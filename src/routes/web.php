<?php

Route::namespace('Omatech\AutoFaker\App\Http\Controllers')
    ->group(function ($route) {

    $route->get('/markup/{path}.html', 'MarkupController@path');

});

