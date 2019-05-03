# Installation

composer require omatech/auto-faker
php artisan vendor:publish

# Usage

For each page you have to create a markup:

1) Define your data

in the folder config/autofaker create a file describing your data in YAML, see the index.yaml for example. 

```
user:
  vehicles*:
      revisions*:
news*:
menu:
    groups*:
          links*:
```

The * defines multiple records (the system generates between 3 and 10 randomly)

To see the generated sample data use this URL:

/index.html?debug-data=true

If you need an url called users.html then create a file users.html

2) Define your view

You must create a file called resources/views/markup/xxx.blade.php where xxx is the url you are using. See index.blade.php as an example.

3) Create the components needed for your project

4) If you want to modify the default fake data for each record modify the file config/autofaker/fake_record_format.yaml, each row is a key=>value par that generates $row[$key]=>$faker->$value 

5) You're DONE!

If you need to add additional fields to each object generated modify the function getFakeRecord in the web.php file.

The URLs to test the project are:

/markup/index.html

You can use the parameter debug-data to see only the generated data

/markup/index.html?debug-data=true

# Test

./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/AutoFakerTest