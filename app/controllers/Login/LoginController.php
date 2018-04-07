<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require_once ROOT . '/php-mvc_ORI/app/models/Login/LoginModel.php';
require_once LIBS_ROUTE . 'Session.php';
/**
* 
*/
class LoginController extends Controller
{

  private $model;
  private $session;

  public function __construct()
  {
    $this->model = new LoginModel();    
    $this->session = new Session();    
  }

  public function signin($request_params)
  {
    if($this->verify($request_params))
      return $this->renderErrorMessage('El email y password son obligatorios');

   $result = $this->model->signIn($request_params['email']);

   if(!$result->num_rows)
    return $this->renderErrorMessage("El email {$request_params['email']} no fue encontrado ");    

  $client = $result->fetch_object();
  //var_dump($result);
    if(!password_verify($request_params['password'], $client->password))
      return $this->renderErrorMessage("El password es incorrecto.");        

    //Iniciar sesion 
    $this->session->init();
    $this->session->add('email', $client->email);
    header('location: /php-mvc_ORI/Main');

  }

  public function verify($request_params)
  {
    return empty($request_params['email']) OR empty($request_params['password']);
  }

  public function renderErrorMessage($message)
  {
    $params = array('error_message' => $message);
    $this->render(__CLASS__, $params);
  }

  public function exec()
  {
    $this->render(__CLASS__);
  }
}