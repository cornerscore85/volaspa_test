<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Director;
use App\Models\Actor;
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
        
        $api_response=$films['Response'];

        if($api_response=="False"){
            return redirect("cerca_titolo")->withWarning('Attenzione! Il film ricercato non esiste.');
        }
        else{

            $titolo=$films['Title'];
            $id_user=Auth::id();

            
            $esiste=0;
            //RICERCA FILM
            $id_film = DB::table('films')->where('titolo',$titolo)->value('id');
            if ($id_film > 0){
                // Verifica se film esiste già nella collezione privata
                $result = DB::table('user_films')->get()->where('id_user',$id_user)->where('id_film',$id_film);
            
                if ($result->count() > 0)$esiste=1;
            }
            

            
            //return $myArray;
            return view('cerca_film_risultati', ['films' => $films,'esiste' => $esiste]);
        }
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
        
        $api_response=$films['Response'];

        if($api_response=="False"){
            return redirect("cerca_id")->withWarning('Attenzione! Il film ricercato non esiste.');
        }
        else{
            $titolo=$films['Title'];
            $id_user=Auth::id();
            
            $esiste=0;
            //RICERCA FILM
            $id_film = DB::table('films')->where('titolo',$titolo)->value('id');
            if ($id_film > 0){
                // Verifica se film esiste già nella collezione privata
                $result = DB::table('user_films')->get()->where('id_user',$id_user)->where('id_film',$id_film);
            
                if ($result->count() > 0)$esiste=1;
            }
            //return $myArray;
            return view('cerca_film_risultati', ['films' => $films,'esiste' => $esiste]);
        }
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
        $id_user=Auth::id();
        
        $director = new Director;
        $director->nome= $regista;

        //RICERCA REGISTA
        $id_regista = DB::table('directors')->where('nome',$regista)->value('id');
        if ($id_regista > 0){
            
        }
        else{
            $director->save();
            $id_regista=$director->id;

        }
        
        $film= new Film;
        $film->titolo= $titolo;
        $film->anno= $anno;
        $film->genere= $genere;
        $film->regista= $id_regista;
        $film->trama= $trama;
        
        //RICERCA FILM
        $id_film = DB::table('films')->where('titolo',$titolo)->value('id');
        if ($id_film > 0){
            
        }
        else{
            $film->save();
            $id_film=$film->id;

        }
        

        

        // CREO ARRAY ATTORI

        $attori_array=explode(",",$attori);
        $attori_array_ids=array();

        for($i=0;$i<sizeof($attori_array);$i++){
            $nome_attore=$attori_array[$i];
            $id_attore = DB::table('actors')->where('nome',$nome_attore)->value('id');
            if ($id_attore > 0){
                array_push($attori_array_ids,$id_attore);
            }
            else{
                $actor = new Actor;
                $actor->nome= $nome_attore;
                $actor->save();
                $id_attore=$actor->id;
                array_push($attori_array_ids,$id_attore);
    
            }

        }

        for($j=0;$j<sizeof($attori_array_ids);$j++){
            $attore_id=$attori_array_ids[$j];
            $data=array('id_film'=>$id_film,"id_attore"=>$attore_id);
            DB::table('film_actors')->insert($data);
        }

        $data=array('id_user'=>$id_user,"id_film"=>$id_film);
        DB::table('user_films')->insert($data);





     
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
            

            $films =DB::table('films')
                ->join('user_films', 'films.id', '=', 'user_films.id_film')
                ->join('users', 'users.id', '=', 'user_films.id_user')
                ->join('directors', 'films.regista', '=', 'directors.id')
                ->select('films.titolo','films.anno','films.genere','directors.nome as regista','user_films.id as id')
                ->get();

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
        
        
        DB::table('user_films')->where('id', $id)->delete();

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
