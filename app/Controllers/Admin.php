<?php

namespace App\Controllers;

class Admin extends BaseController
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
            if($this->session->get('level') == 0)
            {
              
                return view('admin/index', );
            }
            return redirect()->to('/auth/login');
        }
        
    }

}