**"Bosun" (CLI Php Microframework) - Version 0.2alpha - (C) 2018 by Angelo Fonzeca**

**What is Bosun?**

Bosun is a microframework for easily developing "console applications" written in PHP OOP. It is quite different from other similar
frameworks/libraries (Like Symfony Console component, Silly, etc.) because Bosun uses "Convention Over Configuration" for
managing commands and associated code (your console application). 

The Bosun Microframework has an "out-of-the-box" native support for Dependency Injection by using Containers and Autowire 
(thanks to PHP-DI) and an organized tree for easily write your code in a simple way.

_DISCLAIMER: This product is a prototype at early stage of development and could have security issues... DO NOT USE IN PRODUCTION ENVIRONMENTS_

**It's magic!** 

You create a class in a specific directory (e.g. "app/Managers/Cmd/CommandNameDomain.php") which extends a specific
"DomainCommand" class, then you define your methods with your code inside, type hinting every parameter (recommended), set a default value for optional parameters... and you have a new command that you can call from CLI as follow:

```bash
./bosun YOURCLASSNAME:YOURMETHOD param1 param2 \[param3\] \[param4=withdefaultvalue\]
```

You can also use options (-i --u=username --password=something --check ).

_NOTE: YOURCLASSNAME is called "DOMAIN" and YOURMETHOD is called "ACTION" in the Bosun universe..._

Bosun will do all the job for you... When invoked, it instantiate an object which corresponds to "DOMAIN", and calls
the Method of the class that corresponds to "ACTION". 

Every parameter from CLI is directly mapped to each parameter of the DOMAIN/ACTION method itself thanks to the PHP reflection. 
The parameters are also "casted" according to the specified type during the method declaration.

Another magic inside Bosun is that you have a Dependency Container support (Php-Di) so you can easily inject Services inside your classes.

The last but not the least, Bosun can generate an help file "Automatically" for each DOMAIN. You can also add additional information
thanks to a simple array that can be configured with optional parameters (in the next release it will be included the "Doctrine Annotation"
support so the use of the descriptions inside the array will be deprecated).


**Why Bosun was born?**

Bosun was born as a tool for creating a full working framework called "Sensuikan" on which I'm working on. Anyway during development I realized
that Bosun could be used as component a-part from the main project. So I made the "Bosun Microframework Package".

It can be useful when:

1) You want write command line code with minimal dependencies in pure PHP OOP style (but you want autoloading composer support, dependency injection, 
well organized project... Out of the box!)

2) You need to re-organize/aggregate your cli legacy code inside a more robust project without spending your time for managing command line,
parameters, value mapping, etc.

3) As base for writing your own framework... Bosun can be a good candidate for realizing tools like "Composer", "Laravel Artisan", etc.

4) You want to write workers or services in PHP that can be called from your cron, command line scripts, etc.

Anyway... Too much words... Now Let's some code... ;-)

**How to install**

You need php 7.2, Composer and Git installed on your machine (for now tested only on Linux/Ubuntu/Mint).

Git clone the repository 

```bash
git clone https://github.com/afonzeca/bosun.git
```

and run 

```bash
composer install 
```

inside the created "bosun" folder!

In the next releases I will split the bosun core from the framework "boilerplate" and I will publish
everything on Packagist.org so Composer can be used for creating Bosun Projects!
 
_DISCLAIMER: This product is a prototype at early stage of development and could have security issues... DO NOT USE IN PRODUCTION ENVIRONMENTS_


**Example 1 - Basic application** 

We want to implement the following command:

```bash
./bosun table:create users
```

so you need to do the following actions:

_Step 1_
1) Edit the config/whiteList.php for adding your Domain and its Actions to the whiteList.

You will find something like this:

```php
return [
    "default" => [
           "general" => [
               "description" => "A Convention Over Configuration CLI Micro-Framework"
           ],
           "actions" => [
               "help" => [],
           ],
       ], 
];
```
To add your domain (class containing a set of command), copy and past default entry and modify as this:


```php
<?php

return [
   "default" => [
              "general" => [
                  "description" => "A Convention Over Configuration CLI Micro-Framework"
              ],
              "actions" => [
                  "help" => [],
              ],
          ], 
    "table" => [
           "general" => [
                  "description" => "Set of commands for manipulating DB Tables"
           ],
           "actions" => [
               "create" => ["This command allows models creation","[--add-key] [--i] [--primary-key='']"],
               // add other entries here...
           ],
       ],  
];
```

Now the DOMAIN "table" is enabled and can be called within its actions like "create" (drop, etc... if you add it in the file above).

At this stage of development you need the create the structure above, but it is not mandatory to set action descriptions (used for help only).

If you like simply things... you can use:

```php
....

    "table" => [
           "general" => [
               "description" => "A Convention Over Configuration CLI Micro-Framework"
           ],
           "actions" => [
               "create" => [],
               // add other entries here...
           ],
       ],  
....
```

NOTE: In the next releases the whitelist will be deprecated in favor of more robust Doctrine Annotations.


_Step 2_

Inside the folder app/Managers/Cmd you need to add a class called TableDomain.php, with namespace App\Managers\Cmd 
, and that extends the DomainCommand base class:

```php
<?php

namespace App\Managers\Cmd;

class TableDomain extends DomainCommand
{

    public function create(string $tableName)
    {
        printf("Creating table %s\r\n", $tableName);
    }

    /**
     * @throws \ReflectionException
     */
    public function help()
    {
        $this->helpGen->makeHelpMessage("table", self::class);
    }
}
```

In other words... 

1) You need to create a class called "DOMAINNAME"Domain as convention and you must extend DomainCommand (abstract class)
2) You need to create methods corresponding to every ACTION defined in the white list for specific DOMAIN(Classname + Domain suffix).
3) The action parameters from CLI will be injected in the same order inside the method parameters.

The parameters values from CLI will be also "casted" according to the type hinting of the method parameters you define! 
(it is not mandatory to type hint every parameter... but suggested to reduce security problems).   

Optionally you can define the showHelp method that will show a "global" help for the domain by inspecting the class,
its methods and parameters.

The help method will be automatically called if parameters for the method are wrong.

Now you can call your new command with

```bash
./bosun table:create users 
```

Bosun will to the job for you...


**Example 2 - Optional Parameters **

It easy to set optional parameters... Type hint your method parameter and set a default value... for example:


```php
   public function create(string $tableName, string $primaryKey="id", string $defaultDb="mydb") 
   {
     // It will print "users" because it is the parameter passed from CLI
     printf("Default Db %s\r\n",$defaultDb);
     // your code here
     printf("Primary key %s\r\n",$primaryKey);
     // your code here
     printf("Creating table...%s\r\n",$tableName);
     // your code here
     //....
     //other code...
     //....
   }
}
```

Now you can call Bosun with one, two, or three parameters...

If you type ./bosun table:create without parameters you will receive an automatic help... like this:

```
Bosun Microframework 0.2alpha - (C) 2018 by Angelo Fonzeca (Apache License 2.0)

Table: Table creation

Usage: 

  table:ACTION [options] [arguments]

Where ACTIONs are:

create
  Description: This command allows models creation
  Parameters : [--add-key] [--i] [--primary-key=''] <tableName> [primaryKey=id] [defaultDb=mydb] 

```

if you type ./bosun without commands, actions, etc. you will receive a "global help" like this:

```
Bosun Microframework 0.2alpha - (C) 2018 by Angelo Fonzeca (Apache License 2.0)

Default: A Convention Over Configuration CLI Micro-Framework

Usage: 

  DOMAIN:ACTION [options] [arguments]

Available DOMAINS (set of aggregate commands) are:

  default: A Convention Over Configuration CLI Micro-Framework

  producer: Generate Producer Classes!

  table: Table creation

Please write ./bosun DOMAIN:help to list the ACTIONS available for a DOMAIN

```


***Example 3 - Let's use Options (like --i --value="xyz" --check-db -u="root")***

Bosun supports options (short and long) but only in the format -i=something and --optionIlike=something, the format
without "=" is not supported (e.g. '-i something').

You can use your options whenever you want (behind, in the middle or the end of DOMAIN:ACTION).

Commands like these are all valid:

```
.\bosun table:create bills id1 db2 -i --pk="id" --create-fks -u="root" 

.\bosun -i --pk="id" --create-fks table:create -i bills id1 db2 -u="root" 

```

The options are global for a specific DOMAIN(class) and will be accessible from every ACTION(method) inside it. 
It's up to you checking if an option is mandatory or pertinent for your methods/actions.

The good notice is that you don't need to setup anything! It works itself.


For accessing the options you can use the following methods from your domain class:

1) hasOption for checking the existence of an Options

2) getOptionValue for getting the Option value (for void option like "-i" you will receive a void string... if the 
option doesn't exists you will receive "false" bool type - so call hasOption or check for !==false - ).

In our previous example add the following code to the "create" method above

```php

  if($this->hasOption("u")){
             printf("The value of -u is %s\r\n",$this->getOptionValue("u"));
         }

```

The output will be:

```
./bosun -i --pk="id" --create-fks -u bills x abc -u="root" 
 
Default Db abc
Primary key x
Creating table...bills
The value of -u is root

```

**Example 4 - How to inject something**

Bosun also supports dependency injection from container.

Inside the folder "containers" you will find:

1) core.php which contains internal reference to internal services that helps Bosun working
2) app.php which allows you to define and use your services (and inject them via di container and php-di)

So, edit the file container/app.php... It contains an empty array... now I explain how to fill it! 

For example... We want a logger inside our application... the best way is to configure it into the container and than inject it
in our constructor (constructor injection).

Difficult? No, it isn't! Let's start!

_Step 1_

Install monlog with Composer

composer require monolog/monolog

_Step 2_

Edit your containers/app.php... and replace the content with the following:

```php
<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

return [
    Psr\Log\LoggerInterface::class => DI\factory(function () {
        $logger = new Logger('mylog');

        $fileHandler = new StreamHandler('app/var/mylog.log', Logger::DEBUG);
        $fileHandler->setFormatter(new LineFormatter());
        $logger->pushHandler($fileHandler);

        return $logger;
    }),
];
```

_Step 3_

Now in your TableDomain.php replace the content with the following code:

```php
<?php

namespace App\Managers\Cmd;

class TableDomain extends DomainCommand
{

    /**
     * TableDomain constructor.
     * @param \Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function create(string $tableName, string $primaryKey = "id", string $defaultDb = "mydb")
    {

        // It will print "users" because it is the parameter passed from CLI
        printf("Default Db %s\r\n", $defaultDb);
        // your code here
        printf("Primary key %s\r\n", $primaryKey);
        // your code here
        printf("Creating table...%s\r\n", $tableName);
        // your code here
        //....
        //other code...
        //....

        if ($this->hasOption("u")) {
            printf("The value of -u is %s\r\n", $this->getOptionValue("u"));
        }

        if ($this->hasOption("l")) {
            $this->logger->error($this->getOptionValue("l"));
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function help()
    {
        $this->helpGen->makeHelpMessage("table", self::class);
    }
}
```

In other word, the LoggerInterface (the right way!) is required inside the constructor and it will injected by Bosun via
 di-container when the application starts! 

Thanks to the constructor, the logger object is stored inside the $logger properties so it accessible from other methods. 

```php
protected $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
```

Thanks to the following code inside the "create" method, every time the bosun is called with "-l", a line into the log
is written with its parameters:

```php
   if ($this->hasOption("l")) {
            $this->logger->error($this->getOptionValue("l"));
        }
```

if you run bosun the result will be:

```
./bosun -i --pk="id" --create-fks -u="root" table:create -p bills x abc -u="root" -l="Test1"

Default Db abc
Primary key x
Creating table...bills
The value of -u is root

```

If you check for your app/var/mylog.log, you will find the falue of the "-l" option (Test1). 

For further information regarding the use of Dependency Injection inside Bosun please refer to https://PHP-DI.org/ 
by Matthieu Napoli and contributors.


**Configuration File Support**

For statical configuration, Bosun uses the config/config.php file accessible via the global function "conf()".

Anyway it supports "out-of-the-box" the ".env" files, thank to the library from PhpDotEnv by VLucas (https://github.com/vlucas/phpdotenv)

In particular refer to .envcli file for configuring Bosun!

If you need to improve the .envcli file with your parameters, use getenv("KEY") inside your code where "KEY" is the key of your env variable.


**Other Information**

Bosun has many undocumented functions, internals and other useful things... ASAP the documentation will be improved! ;-)  


**What about the internal Bosun Engine?**

When you call Bosun, it does the following (It's not Black Magic :D)

1) Configures and get di DI Container, starts the "ConsoleInput" and processes the parameters  
2) Check the white list to verify that the DOMAIN:ACTION received from CLI can be called
3) Inject some dependencies from container 
4) Make some security checks on parameters (e.g. strips some characters and other checks) - CODE WRITTEN BUT NOT SUPPORTED YET
5) By using reflection analyzes the structure of the ACTION requests (e.g. type hinting of parameters and number of them)
6) Gets the class corresponding to DOMAIN (in our case "table"), uses a factory to instantiate it and the call method "ACTION" (e.g. "create")
7) Inside the method ACTION bosun injects the parameters passed (and if required also dependencies from container)
8) The method called "ACTION" is called...
9) If something wrong a contextual help will be displayed (by using reflection it will describe the DOMAIN and ACTIONS according to the parameters passed)

Bosun is written using S.O.L.I.D. principles, some 12factors principles and full OOP approach! Feel free to browse the code, it's full documented!

_Note for developers_

I prepared a phpunit.xml file so you can use PhpUnit and PhpUnitWatcher from Spatie during development out of the box!

I also made some UT, you will find it in the tests directory so you can understand better how Bosun works!


**What's next?**

The next release will hopefully includes:

1) Support for Doctrine Annotations for describing ACTIONS and WhiteList (the whiteList array will be deprecated)
2) Support for full color, tables and fancy things for outputting information
3) Security improvements
4) Some code refactoring
5) Support for creating Bosun Classes (DOMAINs) with internal DOMAIN/ACTIONS... You don't need to copy and paste anymore from this docs!
6) More complete and full Unit Tests


**License Info**

This file is part of "Bosun - CLI Php Microframework" released under the following terms

Copyright 2018 Angelo FONZECA ( https://www.linkedin.com/in/angelo-f-1806868/ )

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.


**Thanks to...**

My lovely wife Carla! ILY

My friend LucaM. from TGG ;-) 

Heavy Metal

Sir Clive Sinclair, Tony Tebby, Adriano Olivetti, Brian Kernighan and Dennis Ritchie... they are the real IT heroes!


**Contacts**

Linkedin contact https://www.linkedin.com/in/angelo-f-1806868/

Project link: https://github.com/afonzeca/bosun

Thank you so much for your interest in Bosun!
Angelo Fonzeca
