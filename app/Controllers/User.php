<?php

namespace App\Controllers;

class User extends BaseController
{

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        if(!$this->session->has('LoggedIn'))
        {
            return redirect()->to('/auth/login');
        }
        else
        {
            if($this->session->get('level') == 1)
            {
              
                return view('user/index', );
            }
            return redirect()->to('/auth/login');
        }
        
    }

}