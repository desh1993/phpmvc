<?php

class Core {

    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];


    public function __construct()
    {
        $url = $this->getUrl();

        if(file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
            // If exists, set as controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 Index
            unset($url[0]);
          }

        require_once '../app/controllers/'. $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        //check for second part of the url which is the method
        if(isset($url[1])) {
            if(method_exists($this->currentController,$url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }
        //check for parameter
        $this->params = $url ? array_values($url) : [] ;

        //call a callback
        call_user_func_array([$this->currentController,$this->currentMethod],$this->params);

        print_r ($this->params);
    }


    public function getUrl()
    {
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'],'/');
            $url = filter_var($url,FILTER_SANITIZE_URL);
            $url = explode('/',$url);
            return $url;
        }
    }
}