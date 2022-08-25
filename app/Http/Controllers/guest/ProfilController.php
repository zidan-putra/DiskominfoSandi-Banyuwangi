<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;


//saya buat supaya lebih mudah untuk mengatur routnya
class ProfilController extends Controller
{
    public function galeri(){
        return view ('guest.pages.profil.galeri',[
            'title' => 'Galeri',
            'galleries' => Gallery::latest()->get()
        ]);
    }

    public function tupoksi(){
        return view ('guest.pages.profil.tupoksi', [
            'title' => 'Tupoksi'
        ]);
    }

    public function strukturOrganisasi(){
        return view('guest.pages.profil.struktur-organisasi', [
            'title' => 'Struktur Organisasi'
        ]);
    }

    public function visiMisi(){
        return view('guest.pages.profil.visi-misi', [
            'title' => "Visi dan Misi"
        ]);
    }
}
