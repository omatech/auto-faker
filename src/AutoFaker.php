<?php

namespace Omatech\AutoFaker;

use Symfony\Component\Yaml\Yaml;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Faker;

class AutoFaker
{

    var $yamlString;
    var $results;
    var $fakeSeed = null;
    var $fixedRecords = null;
    var $locale;
    var $fakerFormatArray;

    function __construct($yamlString, $locale = 'es_ES', $fakeSeed = null, $fixedRecords = null)
    {
        $this->yamlString = $yamlString;
        $this->locale = $locale;

        if (isset($fixedRecords)) {
            $this->fixedRecords = $fixedRecords;
        }
        $this->setFaker($fakeSeed);
    }

    function setFaker($fakeSeed = null)
    {
        $faker = Faker\Factory::create($this->locale);
        if (isset($fakeSeed)) {
            $this->fakeSeed = $fakeSeed;
            $faker->seed($fakeSeed);
        }
    }

    function getData()
    {
        $yamlContents = Yaml::parse($this->yamlString);
        $data = $this->addRecordsAndFakeData($yamlContents);
        $this->results = $data;
        return $data;
    }

    function addRecordsAndFakeData($yamlContents)
    {
        $data = [];
        foreach ($yamlContents as $key => $val) {
            $res = false;
            if (is_array($val)) {
                $res = $this->addRecordsAndFakeData($val);
            }

            $modChar = substr($key, -1, 1);
            $cleanKey = strtolower(substr($key, 0, -1));

            if (in_array($modChar, ['*', '+'])) {
                if (isset($this->fixedRecords)) {
                    $limit = $this->fixedRecords;
                } else {
                    $limit = rand(3, 6);
                }

                for ($i = 0; $i < $limit; $i++) {
                    $data[$cleanKey][$i] = $this->getFakeRecord();
                    if ($res) $data[$cleanKey][$i][key($res)] = $res[key($res)];
                }

                if ($modChar === '+') {
                    $items = collect($data[$cleanKey]);
                    $count = $items->count();
                    $data[$cleanKey] = new Paginator($items, $count, $limit = 2, $page = 1, [
                        'path' => Paginator::resolveCurrentPath()
                    ]);
                }
            } else {
                $data[$key] = $this->getFakeRecord();
                if ($res) $data[$key][key($res)] = $res[key($res)];
            }
        }

        return $data;
    }


    function setFakerFormat($string)
    {
        $rows = json_decode($string, true);
        $this->fakerFormatArray = $rows;

    }

    function getFaker()
    {
        $faker = Faker\Factory::create($this->locale);
        if (isset($this->fakeSeed)) {
            $faker->seed($this->fakeSeed);
        }

        return $faker;
    }


    function getFakeRecord()
    {
        $faker = $this->getFaker();

        $row = [];
        if (isset($this->fakerFormatArray)) {
            foreach ($this->fakerFormatArray as $key => $val) {
                switch ($val['type']) {
                    case 'date':
                        $row[$key] = date('d/m/Y', $faker->unixTime('today'));
                        break;
                    case 'datetime':
                        $row[$key] = date('d/m/Y H:i:s', $faker->unixTime('today'));
                        break;
                    case "datestring":
                        setlocale(LC_ALL, 'es_ES');
                        $row[$key] = strftime('%e de %B del %Y', $faker->unixTime('today'));
                        break;
                    default:
                        $row[$key] = call_user_func_array([$faker, $val['type']], $val['params'] ?? []);
                }
            }
        } else {
            // Default format
            $row['full_name'] = $faker->name;
            $row['name'] = $faker->firstName;
            $row['surname'] = $faker->lastName;
            $row['address'] = $faker->address;
            $row['phone'] = $faker->phoneNumber;
            $row['claim'] = $faker->catchPhrase;
            $row['created_at'] = date('Y-m-d H:i:s', $faker->unixTime('today'));
            $row['updated_at'] = date('Y-m-d H:i:s', $faker->unixTime('today'));
            $row['url'] = $faker->url;
            $row['text'] = $faker->text;
            $row['description'] = $faker->text;
            $row['email'] = $faker->email;
            $row['title'] = $faker->sentence;
            $row['small_image'] = $faker->imageUrl($width = 50, $height = 50);
            $row['medium_image'] = $faker->imageUrl($width = 640, $height = 480);
            $row['big_image'] = $faker->imageUrl($width = 2400, $height = 960);
        }

        return $row;
    }

}
