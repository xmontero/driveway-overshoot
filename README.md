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
    virtual$ php demo/demo.php demo/games/game01.php

    // You can change the game01 by any other file.
