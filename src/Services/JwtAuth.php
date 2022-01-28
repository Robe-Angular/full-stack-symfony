<?php
namespace App\Services;

use Firebase\JWT\JWT;
use App\Entity\User;

class JwtAuth{
    public $manager;
    public $key;
    
    public function __construct($manager){
        $this->manager = $manager;
        $this->key = 'hola_que_tal_este_es_el_master_fullstack55345353353';
    }
    
    public function signup($email, $password, $gettoken = null){
        //Comprobar si el usuario existe
        $user = $this->manager->getRepository(User::class)->findOneBy([
           'email' => $email,
           'password' => $password
        ]);
        
        $signup = is_object($user) ? true:false;
        
        if($signup){
            $token = [
                'sub' => $user->getId(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'iat' => time(),
                'exp' => time()+ 7 * 24 * 60 *60,
            ];
            //Comprobar el flag gettoken, condicional, si existe, generar el token JWT
            $jwt = JWT::encode($token, $this->key, 'HS256');
            
            if(!empty($gettoken)){
                $data = $jwt;
            }else{
                //Importante!! El array
                $decoded =  JWT::decode($jwt, $this->key, ['HS256']);
                $data = $decoded;
            }           
        }else{
            $data = [
                'status' => 'error',
                'message' => 'Login incorrecto'
            ];
        }
        
        
        
        //DEvolver datos
        return $data;
    }
    
    public function checkToken($jwt, $identity = false) {
        $auth = false;
        try{
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }
        if(isset($decoded) && !empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }
        if($identity){
            return $decoded;
        }else{
            return $auth;
        }
        
    }
    public function edit($email){
        //Comprobar si el usuario existe
        $user = $this->manager->getRepository(User::class)->findOneBy([
           'email' => $email           
        ]);
        
        $edit_user = is_object($user) ? true:false;
        
        if($edit_user){
            $token = [
                'sub' => $user->getId(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'iat' => time(),
                'exp' => time()+ 7 * 24 * 60 *60,
            ];
            //Comprobar el flag gettoken, condicional, si existe, generar el token JWT
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $data = $jwt;
                     
        }else{
            $data = [
                'status' => 'error',
                'message' => 'Update incorrecto'
            ];
        }
        //DEvolver datos
        return $data;
    }
}