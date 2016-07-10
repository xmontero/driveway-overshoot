# driveway-overshoot

A demo project to test how to do pure DDD in symfony. Model project, independent from symfony.

## A sudoku-solver library

This project is one inside a series of four projects to play around with separating the model in DDD from any dependency, including it's independence from the application framework, like for example Symfony.

So this model is:

* Non dependant on any framework (for example Symfony-independent).
* Unit-testable.
* Complete (resolves the full model).
* Consumable from a standalone plain-PHP program (see the demo folder in this repo).
* Consumable from a Symfony applications (see the cloakroom-dietetics repo).

## Model

This model represents a sudoku solver.

## To run the unit-tests

    host$ vagrant up
    host$ vagrant ssh
    virtual$ cd /vagrant
    virtual$ vendor/phpunit/phpunit/phpunit

## To run a plain-PHP demo

    host$ vagrant up
    host$ vagrant ssh
    virtual$ cd /vagrant
    virtual$ php demo/demo.php 1

    // You can change the 1 by any other number provided there is a file in the demo/Games directory.

## TODO:

We should be coherent with the name of some sort of standard.
For example, this one:
https://en.wikipedia.org/wiki/Glossary_of_Sudoku