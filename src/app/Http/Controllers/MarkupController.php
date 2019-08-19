<?php

namespace Omatech\AutoFaker\App\Http\Controllers;

use Omatech\AutoFaker\AutoFaker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarkupController extends Controller
{
    /**
     * @param Request $request
     * @param $path
     * @return mixed
     */
    public function path(Request $request, $path)
    {
        $configFile = config_path("autofaker/$path.yaml");

        if (!file_exists($configFile)) return view("markup.$path");

        $autofaker = new AutoFaker(file_get_contents($configFile));
        $autofaker->setFakerFormat(file_get_contents(config_path("autofaker/fake_record_format.json")));
        $data = $autofaker->getData();
        if ($request->input('debug-data') == 'true') dd($data);

        return view("markup.$path", $data);
    }
}
