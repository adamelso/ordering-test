FeelUnique Technical Test - Ordering CLI Application
====================================================

Overview
--------

This project is a command line application that will read a shop order from
XML data to calculate the total cost of the order.

Details of the assignment are found in the file `TASK.md`.


Architecture
------------

This isn't built on the Symfony Standard Edition, nor is it a `Bundle`, but the components
have been structured together to bear some similarities with Symfony Bundles and
to illustrate how Symfony is put together, minus the Kernel.

The Symfony `HttpKernel` component is not used as this is a command line
application and does not need to handle HTTP `Request`s or send a `Response`.

Instead, `Symfony\Component\Console\Application` is treated as the kernel with
input and output handling delegated to a `Command` child class.

    Symfony\Component\Console\Command\Command::execute($input, $output);

would be analogous to

    Symfony\Component\HttpKernel\HttpKernelInterface::handle($request, $response);



This diagram below shows how this project depends on Symfony.


                                                   ┌──────────────┐          ┌─────────────┐
    Config                                         │ services.yml │          │ config.yml  │
                                                   └───────┬──────┘          └──────┬──────┘
                                                           │                        │
                           ┏━━━━━━━━━━━━━━━┓       ┏━━━━━━━┷━━━━━━━┓        ┏━━━━━━━┷━━━━━━━┓
    DependencyInjection    ┃   Container   ┣━━━━━━━┫   Extension   ┣━━━━━━━━┫ Configuration ┃
                           ┗━━━━━━━┳━━━━━━━┛       ┗━━━━━━━━━━━━━━━┛        ┗━━━━━━━━━━━━━━━┛
                                   ┃
                           ┏━━━━━━━┻━━━━━━━┓       ┏━━━━━━━━━━━━━━━┓
    Console                ┃  Application  ┣━━━━━━━┫ OrderCommand  ┃
                           ┗━━━━━━━━━━━━━━━┛       ┗━━━━━━━┳━━━━━━━┛
                                                           ┃
                                                           ┃
                                                           ┃
                                                   ┏━━━━━━━┻━━━━━━━┓
    FeelUnique                                     ┃  XmlImporter  ┃
                                                   ┗━━━━━━━┳━━━━━━━┛
                                                           ┃
                                                   ┏━━━━━━━┻━━━━━━━━┓
                                                   ┃ OrderProcessor ┃
                                                   ┗━━━━━━━┳━━━━━━━━┛
                                                           ┃
                                                    ( other classes )


Usage
-----

To use, instead dependencies first:

    $ composer install


Then run the app

    $ ./order


You can specify an XML file to import beforehand, otherwise you will be prompted at runtime.

    $ ./order src/Resources/data/order.xml


You should get the following output:

    $ ./order
    Please specify the XML file to import: src/Resources/data/order.xml
    Reading XML file "src/Resources/data/order.xml"
    Order total: £ 9.98


Automated Tests
---------------

Integration tests are created for Behat. To test, run:

    $ bin/behat

You can see the scenario in `features/order.feature` and its Context in `features/Context/OrderContext.php`.

An integration test has also been written for PHPUnit at `src/Tests`. To test, run

    $ bin/phpunit
