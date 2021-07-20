<?php
namespace App\Controllers;
//AICI ADAUGAM DATELE IN TABELE & lucram cu datele -TOT
use App\App;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Products;
use App\Models\AuthTokens;

class APIController extends App
{
    
    public function actionRegister($request, $response, $args){
        //astea se intampla cand primim request - adica cum ar veni cand trimitem datele dintr-un formular mai departe
        $parsedBody = $request->getParsedBody(); //citesti bodyul

        //verificam daca avem datele primite
        if(!isset($parsedBody['username']) || !isset($parsedBody['email']) || 
        !isset($parsedBody['password']) || !isset($parsedBody['confirmPassword'])){
            return $response->withJson(["error_message" => "Nu sunt toate datele trimise",
                                        "status" => 400], 400);
        }

        $username = $parsedBody['username'];//datele din body intra in arrayul parsedBody
        $email = $parsedBody['email'];
        $password = $parsedBody['password'];
        $confirmPassword = $parsedBody['confirmPassword'];//confirmPassword asa si in register.twig

        // return json_encode([$username, $email, $password, $confirmPassword]); //ca sa am un response

        if ($password!=$confirmPassword){
            return $response->withJson(["error_message" => "Cele 2 parole nu corespund",
                                        "status" => 400], 400);//returnam response de tip json 400 Bad Request
        }

        $userFromDatabase = $this->db->table('user')->where('email','=',$email)->first();
        if (!is_null($userFromDatabase)){
            return $response->withJson(["error_message" => "Emailul este deja folosit",
                                        'status' => 400], 400);
        }

        //acuma daca datele sunt corecte, urm sa le bagam in db!!!!!!!!!!!!!!!!!!!!!!!!
        $user = new User();//obiect al clasei pt a scrie in bd
        $user->create([ //create e de la laravel din models
            'username' => $username,
            'email' => $email, 
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        return $response->withJson(['message' => "Succes",
                                    'status' => 200], 200);
    }

    public function actionLogin($request, $response, $args){
        //astea se intampla cand primim request - adica cum ar veni cand trimitem datele dintr-un formular mai departe
        $parsedBody = $request->getParsedBody(); //citesti bodyul

        //verificam daca avem datele primite
        if(!isset($parsedBody['email']) || !isset($parsedBody['password'])){
            return $response->withJson(["error_message" => "Nu sunt toate datele trimise",
                                        'status' => 400], 400);
        }

        $email = $parsedBody['email'];
        $password = $parsedBody['password'];
       
        $userFromDatabase = $this->db->table('user')->where('email','=',$email)->first();
        if (is_null($userFromDatabase)){
            return $response->withJson(['error_message'=> "emailul nu exista",
                                        'status' => 400], 400);
        }

        // return $response->withJson($userFromDatabase); toate datele despre userul acesta

        if (!password_verify ( $password , $userFromDatabase->password)){
            return $response->withJson(['error_message'=> "Parola nu a fost introdusa corect",
                                        'status' => 400] , 400);
        }

        //acuma daca datele sunt corecte, urm sa le bagam in db
        $authTokens = new AuthTokens();
        $createdObject = $authTokens->create([ //create e de la laravel din models
            'fk_user' => $userFromDatabase->pk_id,
            'key' => $this->staticMethods->getRandomKey(50)
        ]);

        return $response->withJson(
            [
                "key" => $createdObject->key,
                "expiry" => $this->settings['token_expiry'] //configs
            ],200);
    }

    // decode din formatul strig json in Object de php 
    //encode - din arrayList sau Object se face format json
    public function actionCreateInvoice($request, $response, $args){//prima oara intra in requestul de la
        //middleware si daca totul e ok, atunci intra aici (chestiile cu cheia si etc)
        $parsedBody = $request->getParsedBody();

        $apiKey = $request->getHeader("Authorization")[0];
        $idUser = $this->db->table("authTokens")->where("key","=",$apiKey)->first()->fk_user;//preluam id-ul userului din header
       

        $files = $request->getUploadedFiles();
        $dataObject = json_decode($parsedBody['data']);
         
        $image = "";
        if (isset($files['files']) && sizeof($files['files']) > 0){
            $path = $files['files'][0]->file;//path catre imagine temporara, salvata in server temporar
            // return $response->withJson($path); C:\xampp\tmp - aici se salveaza temporar, asta e pathul
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        
        $catreCine = $dataObject->catrecine;
        $date = $dataObject->date;
        $duedate = $dataObject->duedate;
        $finaltotal = $dataObject->finaltotal;
        $firma = $dataObject->firma;
        $notes = $dataObject->notes;
        $ordernumber = $dataObject->ordernumber;
        $subtotal = $dataObject->subtotal;
        $tax = $dataObject->tax;
        $terms = $dataObject->terms;

        $invoice = new Invoice();//obiect al clasei pt a scrie in bd
        $createdInvoice = $invoice->create([ //create e de la laravel din models
            'fk_user' => $idUser,
            'date' => $date,
            'due_date' => $duedate, 
            'user_info' => $firma,
            'client_info' => $catreCine, 
            'image' => $image, 
            'tax' => $tax, 
            'note' => $notes, 
            'terms' => $terms
        ]);

        $dateLinii = [];
        for ($i = 0; $i < sizeof($dataObject->dateLinii); $i++) {
            $dateLinii[] = $dataObject->dateLinii[$i];
        }

        for ($i = 0; $i < sizeof($dateLinii); $i++) {
            $quantity = $dateLinii[$i]->quantity;

            $products = new Products();//obiect al clasei pt a scrie in bd
            $products->create([ //create e de la laravel din models
                'fk_invoice' => $createdInvoice->id,
                'description' => $dateLinii[$i]->descriptionValue, 
                'quantity' => $dateLinii[$i]->quantity,
                'price' => $dateLinii[$i]->totalpriceValue
            ]);
        }

        return $response->withJson(['message' => "Dates in invoice table were created",
        'status' => 200], 200);
    }

    public function actionGetAllInvoices($request, $response, $args){
        $apiKey = $request->getHeader("Authorization")[0];
        $idUser = $this->db->table("authTokens")->where("key","=",$apiKey)->first()->fk_user;//preluam primul id al userului din header

        $invoices = $this->db->table("invoice")->where("fk_user","=",$idUser)->get();

        $responseArray = [];

        foreach($invoices as $invoice){
            $products = $this->db->table("products")->where("fk_invoice","=",$invoice->pk_id)->get();

            $responseArray[] = [
                "pk_id" => $invoice->pk_id,
                "date" => $invoice->date,
                "due_date" => $invoice->due_date,
                "user_info" => $invoice->user_info,
                "client_info" => $invoice->client_info,
                "image" => $invoice->image,
                "tax" => $invoice->tax,
                "note" => $invoice->note,
                "terms" => $invoice->terms,
                "products" => $products
            ];
        }

        return $response->withJson($responseArray, 200);
    }  


}