<?php

namespace App;

class Controller {
    protected ReviewStore $store;

    public function __construct(ReviewStore $store) {
        $this->store = $store;
    }

    public function foo() {

    }
}