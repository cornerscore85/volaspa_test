<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * 
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.login');
    }  
      
    /**
     * 
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.register');
    }
      
    /**
     * 
     * 
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            return redirect()->intended('dashboard')
                        ->withSuccess('Login avvenuto con successo');
        }
  
        return redirect("login")->withSuccess('Attenzione! Le credenziali inserite non solo valide');
    }



    /**
     * 
     * 
     * @return response()
     */
    public function postCercaTitolo(Request $request)
    {
        $titolo = $request->input('film_titolo');
        $url="https://www.omdbapi.com/?apikey=".env("OMDB_KEY")."&t=".$titolo;
        $response = Http::acceptJson()->get($url);
        
        $films = json_decode($response, true);

        $titolo=$films['Title'];
        $id_utente=Auth::id();
        // Verifica se film esiste già nella collezione privata
        $result = DB::table('films')->get()->where('id_utente',$id_utente)->where('titolo',$titolo);
        $esiste=0;
        if ($result->count() > 0)$esiste=1;
        //return $myArray;
        return view('cerca_film_risultati', ['films' => $films,'esiste' => $esiste]);
    }

    /**
     * 
     * 
     * @return response()
     */
    public function postCercaId(Request $request)
    {
        $id = $request->input('film_id');
        $url="https://www.omdbapi.com/?apikey=".env("OMDB_KEY")."&i=".$id;
        $response = Http::acceptJson()->get($url);
        
        $films = json_decode($response, true);
        
        $titolo=$films['Title'];
        $id_utente=Auth::id();
        // Verifica se film esiste già nella collezione privata
        $result = DB::table('films')->get()->where('id_utente',$id_utente)->where('titolo',$titolo);
        $esiste=0;
        if ($result->count() > 0)$esiste=1;
        //return $myArray;
        return view('cerca_film_risultati', ['films' => $films,'esiste' => $esiste]);
    }



    /**
     * 
     * 
     * @return response()
     */
    public function postAggiungiFilm(Request $request)
    {
        
        

        $titolo = $request->input('film_titolo');
        $anno = $request->input('film_anno');
        $genere = $request->input('film_genere');
        $regista = $request->input('film_regista');
        $attori = $request->input('film_attori');
        $trama = addslashes($request->input('film_trama'));
        $id_utente=Auth::id();
        
        
        $film= new Film;
        $film->id_utente= $id_utente;
        $film->titolo= $titolo;
        $film->anno= $anno;
        $film->genere= $genere;
        $film->regista= $regista;
        $film->attori= $attori;
        $film->trama= $trama;
        $film->save();

     
        return redirect("dashboard")->withSuccess('Inserimento avvenuto correttamente.');
    }
      
    /**
     * 
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("login")->withSuccess('Registrazione avvenuta con successo! Effettua il login.');
    }
    
    /**
     * 
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            $user = Auth::user();
            $id_utente=Auth::id();
            $nome_utente=$user->name;
            $films = DB::table('films')->get()->where('id_utente',$id_utente);
            return view('dashboard', ['id_utente' => $id_utente, 'nome_utente' => $nome_utente, 'films' => $films]);
        }
  
        return redirect("login")->withSuccess('Attenzione! Accesso non effettuato');
    }


    /**
     * 
     *
     * @return response()
     */
    public function cerca_titolo()
    {
        if(Auth::check()){
            return view('cerca_titolo');
        }
  
        return redirect("login")->withSuccess('Attenzione! Accesso non effettuato');
    }

    /**
     * 
     *
     * @return response()
     */
    public function postEliminaFilm(Request $request)
    {
        $id=$request->input('film_id');
        $film = Film::findOrFail($id);
        $film->delete();

        return redirect("dashboard")->withSuccess('Film eliminato correttamente.');


    }


    

    /**
     * 
     *
     * @return response()
     */
    public function cerca_id()
    {
        if(Auth::check()){
            return view('cerca_id');
        }
  
        return redirect("login")->withSuccess('Attenzione! Accesso non effettuato');
    }





    
    /**
     * 
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * 
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}
