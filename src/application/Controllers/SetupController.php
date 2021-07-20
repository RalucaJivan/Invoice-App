<?php

namespace App\Controllers;

use App\App;
//SetupController se ocupa de rute
class SetupController extends App {
    public function createDatabase($request, $response, $args){
        $this->databaseBuilder->createUserTable();
        $this->databaseBuilder->createInvoiceTable();
        $this->databaseBuilder->createAuthTokensTable();
        $this->databaseBuilder->createProductsTable();
        $this->databaseBuilder->createForeignKeys();//atribul databaseBuilderul
    }
}