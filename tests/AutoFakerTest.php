<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Omatech\AutoFaker\AutoFaker;

final class AutoFakerTest extends TestCase
{
    var $simpler_yaml="user:";
    var $multiple_yaml="users*:";
    var $complex_example_yaml="#
user:
    vehicles*:
      revisions*:
news*:
menu:
    groups*:
        links*:
";

    var $faker_format_yaml="#
full_name: name
name: firstName
surname: lastName
address: address
phone: phoneNumber
claim: catchPhrase
created_at: date
updated_at: date
url: url
text: text
description: text
email: email
title: sentence
small_image: imageUrl,50,50
medium_image: imageUrl,640,480
big_image: imageUrl,2400,960
";


    public function testFakeRecord(): void
    {
        $fakeSeed=rand(1, 300);
        $autofaker=new AutoFaker($this->simpler_yaml, 'es_ES', $fakeSeed);
        $autofaker->setFakerFormat($this->faker_format_yaml);
        $fakeRecord=$autofaker->getFakeRecord();

        $faker=$autofaker->getFaker();
        $row=[];
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
    
        //print_r($fakeRecord);die;
        $this->assertEquals($fakeRecord, $row);
    }

    public function testSimplerYamlStructure(): void
    {
        $fakeSeed=rand(1, 300);
        $autofaker=new AutoFaker($this->simpler_yaml, 'es_ES', $fakeSeed);
        $fakeRecord=$autofaker->getFakeRecord();
        $data=$autofaker->getData();
        $this->assertEquals(['user'=>$fakeRecord], $data);
    }

    public function testMultipleYamlStructureWithTwoRecords(): void
    {
        $fakeSeed=rand(1, 300);
        $fixedRecords=2;
        $autofaker=new AutoFaker($this->multiple_yaml, 'es_ES', $fakeSeed, $fixedRecords);
        $fakeRecord=$autofaker->getFakeRecord();
        $data=$autofaker->getData();
        //print_r($data);die;
        $this->assertEquals(['users'=>[
            0=>$fakeRecord,
            1=>$fakeRecord
        ]], $data);
    }

    public function testComplexYamlStructureWithTwoRecords(): void
    {
        $fakeSeed=rand(1, 300);
        $fixedRecords=2;
        $autofaker=new AutoFaker($this->complex_example_yaml, 'es_ES', $fakeSeed, $fixedRecords);
        $fakeRecord=$autofaker->getFakeRecord();
        $data=$autofaker->getData();
        
        $revisions=['revisions'=>[0=>$fakeRecord, 1=>$fakeRecord]];
        $vehicles=['vehicles'=>[0=>array_merge($fakeRecord, $revisions), 1=>array_merge($fakeRecord, $revisions)]];
        $user=['user'=>array_merge($fakeRecord, $vehicles)];

        $news=['news'=>[0=>$fakeRecord, 1=>$fakeRecord]];

        $links=['links'=>[0=>$fakeRecord, 1=>$fakeRecord]];
        $groups=['groups'=>[0=>array_merge($fakeRecord, $links), 1=>array_merge($fakeRecord, $links)]];
        $menu=['menu'=>array_merge($fakeRecord, $groups)];

        $expected=array_merge($user, $news, $menu);
        
        //print_r($expected);die;
        $this->assertEquals($expected, $data);
    }


    public function testMultipleYamlStructureWithFiveRecords(): void
    {
        $fakeSeed=rand(1, 300);
        $fixedRecords=5;
        $autofaker=new AutoFaker($this->multiple_yaml, 'es_ES', $fakeSeed, $fixedRecords);
        $fakeRecord=$autofaker->getFakeRecord();
        $data=$autofaker->getData();
        //print_r($data);die;
        $this->assertEquals(['users'=>[
            0=>$fakeRecord,
            1=>$fakeRecord,
            2=>$fakeRecord,
            3=>$fakeRecord,
            4=>$fakeRecord
        ]], $data);
    }


}