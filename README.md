## "Arun" (CLI Microframework) for PHP7.2+ - Version 0.4-alpha - (C) 2018 by Angelo Fonzeca

### What is Arun?

Arun is a microframework for easily developing "console applications" written in PHP OOP. It is quite different from other similar
frameworks/libraries (Like Symfony Console component, Silly, etc.) because Arun uses "Convention Over Configuration" and Annotations for
managing commands and associated code (your console application). 

The Arun Microframework has an "out-of-the-box" native support for Dependency Injection by using Containers and Autowire 
(thanks to PHP-DI) and an organized tree for easily write your code in a simple way.

_DISCLAIMER: This product is a prototype at early stage of development and could have security issues... DO NOT USE IN PRODUCTION ENVIRONMENTS_

### It is magic! 

You create a class in a specific directory (e.g. "app/Console/Domains/CommandNameDomain.php") which extends a specific
"DomainCommand" class, then you define your methods with your code inside, type hinting every parameter (recommended), 
set a default value for optional parameters... and you have a new command that you can call from CLI as follow:

```bash
./arun YOURCLASSNAME:YOURMETHOD param1 param2 [param3] [param4=withdefaultvalue]
```

(see the Examples paragraph for better understanding how it works...)

You can also use options (-i --u=username --password=something --check ).

_NOTE: YOURCLASSNAME is called "DOMAIN" and YOURMETHOD is called "ACTION" in the Arun universe..._

Arun will do all the job for you... When invoked, it instantiate an object which corresponds to "DOMAIN", and calls
the Method of the class that corresponds to "ACTION". 

Every parameter from CLI is directly mapped to each parameter of the DOMAIN/ACTION method itself thanks to the PHP reflection. 
The parameters are also "casted" according to the specified type during the method declaration.

Another magic inside Arun is that you have a Dependency Container support (Php-Di) so you can easily inject Services inside your classes.

The last but not the least, Arun can generate an help file "Automatically" for each DOMAIN and its ACTIONS. You can also add additional information (help text)
thanks to "annotations" (special comments inside classes). 

By using annotations it is also possible to define options and their help description. 

The whiteList array is not supported anymore.

### Why Arun was born?

Arun was born as tool for creating a full working framework called "Sensuikan" on which I'm working on. Anyway during the development I realized
that Arun could be used as a stand-alone component. So I made the "Arun Microframework Package".

It can be useful when:

1) You want write command line code with minimal dependencies in pure PHP OOP style (but you want autoloading composer support, dependency injection, 
well organized project... Out of the box!)

2) You need to re-organize/aggregate your cli legacy code inside a more robust project without spending your time for managing command line,
parameters, value mapping, etc.

3) As base for writing your own framework... Arun can be a good candidate for realizing tools like "Composer", "Laravel Artisan", etc.

4) You want to write workers or services in PHP that can be called from your cron, command line scripts, etc.

Anyway... Too much words... Now Let's making some code... ;-)

### How to install

You need php 7.2, Composer and Git installed on your machine (for now tested only on Linux/Ubuntu/Mint).

Git clone the repository 

```bash
git clone https://github.com/afonzeca/arun.git
```

inside the created "arun" folder run 

```bash
composer install 
```

In the next releases I will split the Arun core from the framework "boilerplate" and I will publish
everything on Packagist.org so Composer can be used for creating Arun Projects!
 
_DISCLAIMER: This product is a prototype at early stage of development and could have security issues... DO NOT USE IN PRODUCTION ENVIRONMENTS_

### Examples

**Example 1 - Basic application** 

We want to implement the following command:

```bash
./arun table:create users
```

so you need to do the following actions:

_Step 1_

Inside the folder app/Console/Domains you need to add a class called TableDomain.php, with namespace App\Console\Domains 
 that extends the DomainCommand base class like the following example:

```php
<?php

namespace App\Console\Domains;

use ArunCore\Annotations as SET;

/**
 * Class TableDomain
 * 
 * @SET\DomainEnabled(true)
 * @SET\DomainSyn("This Domain allows to interact with tables")
 *
 * @package App\Console\Domains
*/
class TableDomain extends DomainCommand
{
    /**
     *
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("This action allows to create a table with a specified name")
     * @SET\ActionOption("--set-key=<name>:Set the primary key name")
     * @SET\ActionOption("--use-camelCaseForNaming:Use camelCase for defining table name")
     *
     * @param string $tableName
     *
     * @throws \Exception
    */
    public function create(string $tableName)
    {
        printf("Creating table %s\r\n", $tableName);
    }
}
```

In other words... 

1) You need to create a class called "DOMAINNAME"Domain as convention and you must extend DomainCommand (abstract class)
2) You need to create methods corresponding to every ACTION for a specific DOMAIN(Classname + Domain suffix).
3) The action parameters from CLI will be injected in the same order inside the method parameters.

The parameters values from CLI will be also "casted" according to the type hinting of the method parameters you define 
(please use only int, string, float... Array and Objects are not tested! I don't know what happens! :D)
 
Note: it is not mandatory to type hint every parameter... but suggested to reduce security problems.   

Now you can call your new command with

```bash
./arun table:create users 
```

Arun will to the job for you...


### How annotation works? ###

As you can see in the example above, there are @SET\SomeThing inside the comments... they are called "annotations")
(they are also used in PhpDoc if you are familiar with it).

They are directives, that allows Arun to get more information about the class and methods that will be used to define 
some behaviors at run-time.  

When you use Arun some annotations are MANDATORY (Just 2):

```
@SET\DomainEnabled(param) 
```

where param is "true" of "false"

This allows to enable the class to be called as a "DOMAIN" from command line with Arun if set to true. It also enable
the framework to show the command during help.
 
NOTE: this annotation/directive is valid only on the top of a class otherwise you'll receive an exception!

```
@SET\ActionEnabled(param)
```

where param is "true" of "false"

This allows to enable the method to be called from command line whith Arun as "ACTION". The behavior is similar to the previous
annotation.

NOTE: this annotation/directive is valid only on the top of a method otherwise you'll receive an exception!

There will be only one DomainEnabled for the Class, and multiple ActionEnabled for each method that must be considered an "Action" (callable from CLI)


Other annotations are not mandatory:

```
@SET\DomainSyn("some spaced text...")
```

```
@SET\ActionSyn("some spaced text...")
```

define the description when help is required for a Domain or Action.

The last annotation is

```
@SET\ActionOption("--optionName=<something>:description")
```

it allows you to define the options for every Action (NOTE: the Options at the moment are visible to all Actions of a Domain),
this directive is used only for help messages at the moment.
An ActionOption directive can be present multiple time for an Action (Method) so you can define multiple Options.


**Example 2 - Optional Parameters**

It is easy to set optional parameters... Type hint your method parameter and set a default value... for example:


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

Now you can call Arun with one, two, or three parameters...

If you type ./arun table:create without parameters you will receive an automatic help... like this:

```
Arun Microframework 0.4-alpha - (C) 2018 by Angelo Fonzeca (Apache License 2.0)

Table: Table creation

Usage: 

  table:ACTION [options] [arguments]

Where ACTIONs are:

create
  Description: This action allows to create a table with a specified name
  Parameters : <tableName> [primaryKey=id] [defaultDb=mydb] 
  Option     : --set-key=<name> ( Set the primary key )
  Option     : --use-camelCaseForNaming ( Use camelCase for defining table name )
```

if you type ./arun without commands, actions, etc. you will receive a "global help" like this:

```
Arun Microframework 0.4-alpha - (C) 2018 by Angelo Fonzeca (Apache License 2.0)

Default: A Convention Over Configuration CLI Micro-Framework

Usage: 

  DOMAIN:ACTION [options] [arguments]

Available DOMAINS (set of aggregate commands) are:

  default: A Convention Over Configuration CLI Micro-Framework

  producer: Generate Producer Classes!

  table: Table creation

Please write ./arun DOMAIN:help to list the ACTIONS available for a DOMAIN

```


***Example 3 - Let's use Options (like --i --value="xyz" --check-db -u="root")***

arun supports options (short and long) but only in the format -i=something and --optionIlike=something, the format
without "=" is not supported (e.g. '-i something').

You can use your options whenever you want (behind, in the middle or the end of DOMAIN:ACTION).

Commands like these are all valid:

```
.\arun table:create bills id1 db2 -i --pk="id" --create-fks -u="root" 

.\arun -i --pk="id" --create-fks table:create -i bills id1 db2 -u="root" 

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
./arun -i --pk="id" --create-fks -u bills x abc -u="root" 
 
Default Db abc
Primary key x
Creating table...bills
The value of -u is root

```

**Example 4 - How to inject something**

Arun also supports dependency injection from container.

Inside the folder "containers" you will find:

1) core.php which contains internal reference to internal services that helps Arun working
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

namespace App\Console\Domains;

use ArunCore\Annotations as SET;

/**
 * Class TableDomain
 * 
 * @SET\DomainEnabled(true)
 * @SET\DomainSyn("This Domain allows to interact with tables")
 *
 * @package App\Console\Domains
*/
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

    /**
     *
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("This method says hello to a specified name")
     * @SET\ActionOption("--set-key=<name>:Set the primary key name")
     * @SET\ActionOption("--use-camelCaseForNaming:Use camelCase for defining table name")
     * @SET\ActionOption("--u=<value>:Set username to be used for the RDBMS")
     * @SET\ActionOption("--l=<value>:Logs something")
     *
     * @param string $tableName
     *
     * @throws \Exception
    */
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
}
```

In other word, the LoggerInterface (the right way!) is required inside the constructor and it will injected by Arun via
 di-container when the application starts! 

Thanks to the constructor, the logger object is stored inside the $logger propertie so it accessible from other methods. 

```php
protected $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
```

Thanks to the following code inside the "create" method, every time the Arun is called with "-l", a line into the log
is written with its parameters:

```php
   if ($this->hasOption("l")) {
            $this->logger->error($this->getOptionValue("l"));
        }
```

if you run Arun the result will be:

```
./arun -i --pk="id" --create-fks -u="root" table:create -p bills x abc -u="root" -l="Test1"

Default Db abc
Primary key x
Creating table...bills
The value of -u is root

```

If you check for your app/var/mylog.log, you will find the falue of the "-l" option (Test1). 

For further information regarding the use of Dependency Injection inside Arun please refer to https://PHP-DI.org/ 
by Matthieu Napoli and contributors.


**Example 5 **

Inside the Arun package there is a file called "ExampleDomain.php" (under app/Console/Domains) that can be used as base for
your Domain development. It also shows undocumented features.

### Configuration File Support

For statical configuration, Arun uses the config/config.php file accessible via the global function "Conf()".

Anyway it supports "out-of-the-box" the ".env" files, thank to the library from PhpDotEnv by VLucas (https://github.com/vlucas/phpdotenv)

In particular refer to .envcli file for configuring Arun!

If you need to improve the .envcli file with your parameters, use getenv("KEY") inside your code where "KEY" is the key of your env variable.


### Notes

Arun has many undocumented functions, internals and other useful things... ASAP the documentation will be improved! ;-)  


### What about the internal Arun Engine?

When you call Arun, it does the following (It's not Black Magic :D)

1) Configures and get di DI Container, starts the "ConsoleInput" and processes the parameters  
2) Inject some dependencies from container 
3) Make some security checks on parameters (e.g. strips some characters and other checks) - CODE WRITTEN BUT NOT SUPPORTED YET
4) By using reflection analyzes the structure of the ACTION requests (e.g. type hinting of parameters and number of them)
5) Check if the annotations define a Class or Method as "Enabled" (otherwise Help will be showed and the program ends).
6) If check passed, the framework gets the class corresponding to DOMAIN (in our case "table"), it uses a factory to instantiate the class 
8) Inside the method ACTION (e.g. create) Arun injects the parameters passed (and if required also dependencies from container to the constructor)
9) The method "ACTION" (e.g. create) is called...
10) If something goes wrong a contextual help will be displayed (by using reflection the framework will describe the DOMAIN and ACTIONS according to the 
   parameters passed and annotations)

Arun is written respecting S.O.L.I.D. principles (ehm... I try to respect them at my best ;-) ), some 12factors principles and full OOP approach! 

Feel free to browse the code, it's full documented!

_Note for developers_

I prepared a phpunit.xml file so you can use PhpUnit and PhpUnitWatcher from Spatie during development out of the box!

I also made some UT, you will find it in the tests directory so you can understand better how Arun works!


### What's next?

The next release will hopefully includes:

1) Support for full color, tables and fancy things for outputting information
2) Security improvements
3) Support for creating Arun Classes (DOMAINs) with internal DOMAIN/ACTIONS... You don't need to copy and paste anymore from this docs!
4) More complete and full Unit Tests
5) Mandatory or "optional" options

See the "changelog.txt" file inside the package for checking the changes compared to the previous version.

### License Info

This file is part of "Arun - CLI Microframework for Php7.2+" released under the following terms

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


### Thanks to...

My lovely wife Carla! ILY

My friend LucaM. from TGG ;-) 

Heavy Metal

Sir Clive Sinclair, Tony Tebby, Adriano Olivetti, Brian Kernighan and Dennis Ritchie... they are the real IT heroes!

### About releases

Project link: https://github.com/afonzeca/arun

( Previous project link: https://github.com/afonzeca/bosun )

See changelog.txt inside the package for details about framework improvements.

### Contacts

Linkedin contact https://www.linkedin.com/in/angelo-f-1806868/


Thank you so much for your interest in Arun!
Angelo Fonzeca
