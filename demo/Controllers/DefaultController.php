<?php

namespace DrivewayOvershoot\Demo\Controllers;

use DrivewayOvershoot\Demo\Views\DefaultView;

class DefaultController
{
    public function run()
    {
        /*
a) gets access to the MODEL,
b) operates it (ie: controls it),
c) gets its resulting data,
d) transforms the data into a DTO suitable for the VIEW, and
e) renders the view.
        */

        $this->renderTheView();
    }

    private function renderTheView()
    {
        $viewData = new \stdClass();
        $viewData->name = 'world';

        $view = new DefaultView();
        $view->render( $viewData );
    }
}
