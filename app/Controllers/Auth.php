<?php

namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function __construct()
    {
        //membuat user model untuk konek ke database 
        $this->userModel = new UserModel();
        
        //meload validation
        $this->validation = \Config\Services::validation();
        
        //meload session
        $this->session = \Config\Services::session();
        
    }
    
    public function login()
    {
        if($this->session->has('LoggedIn'))
        {
            if($this->session->get('level') == 0)
            {
                return redirect()->to('/admin');
            }
            if($this->session->get('level') == 1)
            {
                return redirect()->to('/user');
            }
        }
        else
        {
            return view('login');
        }
        
    }

    public function valid_login()
    {
        //ambil data dari form
        $data = $this->request->getPost();
        
        //ambil data user di database yang usernamenya sama 
        $user = $this->userModel->where('nama', $data['nama'])->first();
        
        //cek apakah username ditemukan
        if($user){
            //cek password
            //jika salah arahkan lagi ke halaman login
            if($user['password'] != $data['password']){
            // if($user['user_pass'] != md5($data['user_pass']).$user['salt']){
                
                session()->setFlashdata('password', 'Password salah!');
                return redirect()->to('/auth/login');
            }
            if( ($user['password'] == $data['password']) && ($user['level'] == 0) ){
                //jika benar, arahkan user masuk ke aplikasi 
                $sessLogin = [
                    'LoggedIn' => TRUE,
                    'nama' => $user['nama'],
                    'level' => $user['level']
                    ];
                $this->session->set($sessLogin);
                return redirect()->to('/admin');
            }
            if( ($user['password'] == $data['password']) && ($user['level'] != 0) ){
                //jika benar, arahkan user masuk ke aplikasi 
                $sessLogin = [
                    'LoggedIn' => TRUE,
                    'nama' => $user['nama'],
                    'level' => $user['level']
                    ];
                $this->session->set($sessLogin);
                return redirect()->to('/user');
            }
        }
        else{
            //jika username tidak ditemukan, balikkan ke halaman login
            session()->setFlashdata('nama', 'Username tidak ditemukan!');
            return redirect()->to('/auth/login');
        }
    }
    /*
    public function register()
    {
        //menampilkan halaman register
        return view('register');
    }
    
    public function valid_register()
    {
        //tangkap data dari form 
        $data = $this->request->getPost();
        
        //jalankan validasi
        $this->validation->run($data, 'register');
        
        //cek errornya
        $errors = $this->validation->getErrors();
        
        //jika ada error kembalikan ke halaman register
        if($errors){
            session()->setFlashdata('error', $errors);
            return redirect()->to('/auth/register');
        }
        
        //jika tdk ada error 
        
        //buat salt
        $salt = uniqid('', true);
        
        //hash password digabung dengan salt
        $password = md5($data['password']).$salt;
        
        //masukan data ke database
        $this->userModel->save([
            'username' => $data['username'],
            'password' => $password,
            'salt' => $salt,
            'role' => 2
            ]);
        
        //arahkan ke halaman login
        session()->setFlashdata('login', 'Anda berhasil mendaftar, silahkan login');
        return redirect()->to('/auth/login');
    }
    */
    
    public function logout()
    {
        //hapus session dan kembali ke halaman login
        $this->session->destroy();
        return redirect()->to('/auth/login');
    }
    
}