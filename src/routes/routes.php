<?php

use Illuminate\Http\Request;

Route::get('/markup/{path}.html', function (Request $request, $path) {
    $autofaker=new Omatech\AutoFaker\AutoFaker(file_get_contents(config_path("/fake-data/$path.yaml")));
    $autofaker->setFakerFormat(file_get_contents(config_path("/fake-data/fake_record_format.yaml")));
    $data=$autofaker->getData();
    if ($request->input('debug-data')=='true') dd($data);
    return view("markup.$path", $data);
});