<?php

namespace App\Middlewares;//un fel de package
use App\App;

class ApiMiddleware extends App{
    public function __invoke($request, $response, $next){
        if(sizeof($request->getHeader("Authorization")) > 0){//headerele requestului sunt informatii despre acel request
            //daca un user logat trimite request

            $apiKey = $request->getHeader("Authorization")[0];
            //verific daca cheia exista si daca nu a expirat:
            $result = $this->db->table("authTokens")->where("key","=",$apiKey)->first();
            if(!is_null($result)){
                if(time() - strtotime($result->created_at) > $this->settings['token_expiry']){
                    return $response->withJson([
                        "status" => 401,
                        "message" => "Unauthorized"
                    ], 401);
                }
                else{
                    return $next($request, $response);//keyurile se parseaza in Headere care e in request (index aprox linia 300)
                }
            }
        }

        return $response->withJson([
            "status" => 401,
            "message" => "Unauthorized"
        ], 401);
    }
}