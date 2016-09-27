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
    virtual$ tools/phpunit

## To run a plain-PHP demo

    host$ vagrant up
    host$ vagrant ssh
    virtual$ cd /vagrant
    virtual$ tools/demo
    
For the demo use the following numbers:

* 1 - Compatible determinate. The puzzle has a unique solution.
* 2 - Compatible indeterminate. Same of Game1 but with several clues removed. Underspecified. The puzzle has infinite solutions.
* 3 - Incompatible. Same of Game1 but the clue "1" in column 4, row 9, makes the puzzle not solvable, as the unique solution requires there to be a "7".

## TODO:

We should be coherent with the name of some sort of standard.
For example, this one:
https://en.wikipedia.org/wiki/Glossary_of_Sudoku