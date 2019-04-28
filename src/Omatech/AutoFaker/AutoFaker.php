<?php
namespace Omatech\AutoFaker;
use Symfony\Component\Yaml\Yaml;
use Faker;

class AutoFaker {

    var $yamlString;
    var $results;
    var $fakeSeed=null;
    var $fixedRecords=null;
    var $locale;
    var $fakerFormatArray;

    function __construct ($yamlString, $locale='es_ES', $fakeSeed=null, $fixedRecords=null)
    {
        $this->yamlString=$yamlString;
        $this->locale=$locale;

        if (isset($fixedRecords))
        {
            $this->fixedRecords=$fixedRecords;
        }    
        $this->setFaker($fakeSeed);   
    }

    function setFaker($fakeSeed=null)
    {
        $faker = Faker\Factory::create($this->locale);
        if (isset($fakeSeed))
        {
            $this->fakeSeed=$fakeSeed;
            $faker->seed($fakeSeed);
        }
    }

    function getData()
    {
        $yamlContents = Yaml::parse($this->yamlString);
        $data=$this->addRecordsAndFakeData($yamlContents);
        $this->results=$data;
        return $data;
    }

    function addRecordsAndFakeData ($yamlContents)
    {
        $data=[];
        foreach ($yamlContents as $key=>$val)
        {
            $res=false;
            if (is_array($val))
            {
                $res=$this->addRecordsAndFakeData($val);
            }
            if (substr($key,-1,1)=='*')
            {
                if (isset($this->fixedRecords))
                {
                    $limit=$this->fixedRecords;
                }
                else
                {
                    $limit=rand(3,6);
                }

                for ($i = 0; $i < $limit; $i++) 
                {
                    $data[substr($key,0,-1)][$i]=$this->getFakeRecord();
                    if ($res) $data[substr($key,0,-1)][$i][key($res)]=$res[key($res)];
                }
            }
            else
            {
                $data[$key]=$this->getFakeRecord(); 
                if ($res) $data[$key][key($res)]=$res[key($res)];
            }
        }   

        return $data; 
    }


//yaml per definir els registres

function setFakerFormat($yamlString)
{
    $rows=Yaml::parse($yamlString);
    $this->fakerFormatArray=$rows;
    
}

function getFaker()
{
  $faker = Faker\Factory::create($this->locale);
  if (isset($this->fakeSeed))
  {
      $faker->seed($this->fakeSeed);
  }

  return $faker;
}


function getFakeRecord()
{

    $faker=$this->getFaker();

    $row=[];
    if (isset($this->fakerFormatArray))
    {
        foreach ($this->fakerFormatArray as $key=>$val)
        {
            if (stripos($val, ',')!==false)
            {
                $func_and_params_array=explode(',', $val);
                $function=$func_and_params_array[0];
                switch ($function) {
                    case 'imageUrl':
                        $row[$key]=$faker->$function($func_and_params_array[1], $func_and_params_array[2]);
                        break;
                }
            }
            else
            {
                if ($val=='date')
                {
                    $row[$key]=date('Y-m-d H:i:s', $faker->unixTime('today'));
                }
                else
                {
                    $row[$key]=$faker->$val;
                }
            }
        }
    }
    else{
        // Default format    
        $row['full_name']=$faker->name;
        $row['name']=$faker->firstName;
        $row['surname']=$faker->lastName;
        $row['address']=$faker->address;
        $row['phone']=$faker->phoneNumber;
        $row['claim']=$faker->catchPhrase;
        $row['created_at']=date('Y-m-d H:i:s', $faker->unixTime('today'));
        $row['updated_at']=date('Y-m-d H:i:s', $faker->unixTime('today'));
        $row['url']=$faker->url;
        $row['text']=$faker->text;
        $row['description']=$faker->text;
        $row['email']=$faker->email;
        $row['title']=$faker->sentence;
        $row['small_image']=$faker->imageUrl($width = 50, $height = 50);
        $row['medium_image']=$faker->imageUrl($width = 640, $height = 480);
        $row['big_image']=$faker->imageUrl($width = 2400, $height = 960);
    }

    return $row;
}

}