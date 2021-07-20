<?php

namespace App\Helpers;

use App\App;

class DatabaseBuilder extends App
{
    public function createUserTable(){
        if($this->db->schema()->hasTable('user')){
            return;
        }

        $this->db->schema()->create
        ('user', function($table){
            $table->increments('pk_id');
            $table->text('username');
            $table->text('email');
            $table->text('password');
        });
    }

    public function createInvoiceTable(){
        if($this->db->schema()->hasTable('invoice')){
            return;
        }

        $this->db->schema()->create
        ('invoice', function($table){
            $table->increments('pk_id');
            $table->integer('fk_user')->unsigned()->nullable();
            $table->text('date'); //data cand s-a creat factura
            $table->text('due_date');//data cand trebuie platita factura
            $table->text('user_info');//informatii de facturare ale persoanei care a creat factura, ex: adresa, nr tel al firmei, etc
            $table->text('client_info');//informatiile de facturare ale persoanei care primeste factura
            $table->longText('image');
            $table->unsignedDecimal('tax',8,2);
            $table->text('note');
            $table->text('terms');
        });
    }

    public function createAuthTokensTable(){
        if($this->db->schema()->hasTable('authTokens')){
            return;
        }

        $this->db->schema()->create
        ('authTokens', function($table){
            $table->increments('pk_id');
            $table->integer('fk_user')->unsigned()->nullable();
            $table->text('key');
            $table->timestamps(0);
        });
    }

    public function createProductsTable(){
        if($this->db->schema()->hasTable('products')){
            return;
        }

        $this->db->schema()->create
        ('products', function($table){
            $table->increments('pk_id');
            $table->integer('fk_invoice')->unsigned()->nullable();
            $table->text('description');
            $table->unsignedDecimal('quantity',8,2);
            $table->unsignedDecimal('price',8,2);
        });
    }

    public function createForeignKeys(){
        $this->db->schema()->table('authTokens', function ($table) 
        {
            $table->foreign('fk_user')
                  ->references('pk_id')
                  ->on('user');
        }
        );

        $this->db->schema()->table('products', function ($table) 
        {
            $table->foreign('fk_invoice')
                  ->references('pk_id')
                  ->on('invoice');
        }
        );

        $this->db->schema()->table('invoice', function ($table) 
        {
            $table->foreign('fk_user')
                  ->references('pk_id')
                  ->on('user');
        }
        );
    }
}