<?php

use Illuminate\Http\Request;
use Omatech\AutoFaker\AutoFaker;

Route::get('/markup/{path}.html', function (Request $request, $path) {

    $configFile = config_path("autofaker/$path.yaml");

    if (!file_exists($configFile)) return view("markup.$path");

    $autofaker = new AutoFaker(file_get_contents($configFile));
    $autofaker->setFakerFormat(file_get_contents(config_path("autofaker/fake_record_format.json")));
    $data = $autofaker->getData();
    if ($request->input('debug-data') == 'true') dd($data);

    return view("markup.$path", $data);
});
