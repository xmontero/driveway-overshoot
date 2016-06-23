<?php

namespace DrivewayOvershoot\Demo\Views;

class DefaultView
{
    public function render( $data )
    {
        echo(
"+-----------------------------------+
| driveway-overshoot                |
+-----------------------------------+

Files
-----
* The demo.php is the FRONT-CONTROLLER that sets the autoloader up, and then calls the real controller.
* The Controllers/DefaultController.php is the CONTROLLER in the MVC pattern.
* The Views/DefaultView.php is the VIEW in the MVC pattern.
* The the project itself (under src/) is the MODEL in the MVC pattern.

How it works
------------

The CONTROLLER
a) gets access to the MODEL,
b) operates it (ie: controls it),
c) gets its resulting data,
d) transforms the data into a DTO suitable for the VIEW, and
e) renders the view.

Execution
---------

Hello, $data->name!
"
        );
    }
}
