@extends('layouts.layout')
  
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-4">
                    
                    

                    

                    <form action="{{ route('aggiungi_film.post') }}" method="POST" class="mt-2 mb-4">
                    @csrf
                          <div class="form-group row">
                              <label for="film_titolo" class="col-md-4 col-form-label text-md-right">Titolo</label>
                              <div class="col-md-6">
                                  <input type="text" id="film_titolo" class="form-control" name="film_titolo" readonly value="{{ $films['Title']}}"/>
                                  
                              </div>
                          </div>
                          <div class="form-group row mt-2">
                              <label for="film_anno" class="col-md-4 col-form-label text-md-right">Anno</label>
                              <div class="col-md-6">
                                  <input type="text" id="film_anno" class="form-control" name="film_anno" readonly value="{{ $films['Year']}}"/>
                                  
                              </div>
                          </div>
                          <div class="form-group row mt-2">
                              <label for="film_released" class="col-md-4 col-form-label text-md-right">Data di uscita</label>
                              <div class="col-md-6">
                                  <input type="text" id="film_released" class="form-control" name="film_released" readonly value="{{ $films['Released']}}"/>
                                  
                              </div>
                          </div>
                          <div class="form-group row mt-2">
                              <label for="film_genere" class="col-md-4 col-form-label text-md-right">Genere</label>
                              <div class="col-md-6">
                                  <input type="text" id="film_genere" class="form-control" name="film_genere" readonly value="{{ $films['Genre']}}"/>
                                  
                              </div>
                          </div>
                          <div class="form-group row mt-2">
                              <label for="film_regista" class="col-md-4 col-form-label text-md-right">Regista</label>
                              <div class="col-md-6">
                                  <input type="text" id="film_regista" class="form-control" name="film_regista" readonly value="{{ $films['Director']}}"/>
                                  
                              </div>
                          </div>
                          <div class="form-group row mt-2">
                              <label for="film_attori" class="col-md-4 col-form-label text-md-right">Attori</label>
                              <div class="col-md-6">
                                  <input type="text" id="film_attori" class="form-control" name="film_attori" readonly value="{{ $films['Actors']}}"/>
                                  
                              </div>
                          </div>
                          <div class="form-group row mt-2">
                              <label for="film_trama" class="col-md-4 col-form-label text-md-right">Trama</label>
                              <div class="col-md-6">
                                  <textarea id="film_trama" class="form-control" name="film_trama" rows="5" readonly/>{{ $films['Plot']}}</textarea>
                                  
                              </div>
                          </div>
                          <div class="mt-2">
                            <label class="col-md-4 col-form-label text-md-right">Poster</label>
                            <img src="{{ $films['Poster']}}">
                          </div>
                          <div class="form-group row mt-2">
                            <div class="col-md-6 offset-md-4 mt-2">
                                @if ($esiste==0)
                                    <button type="submit" class="btn btn-success">
                                        Aggiungi ai tuoi film
                                    </button>    
                                
                                @else
                                <button type="submit" class="btn btn-danger" disabled>
                                        Film già presente nella tua collezione
                                    </button>
                                                            
                                @endif
                                
                                
                            </div>
                          </div>
  
                          
                    </form>
                    
        </div>
        
    </div>
</div>
@endsection