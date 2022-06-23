@extends('layouts.layout')
  
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
  
                <div class="card-body">
                    @if (Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif
  
                    <h3>Bentornato {{$nome_utente}}- Lista dei tuoi film</h3>

                    
                    <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Titolo</th>
                            <th>Anno</th>
                            <th>Genere</th>
                            <th>Regista</th>
                            <th>Attori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($films as $film )
                        <tr>
                            <td>{{$film->titolo}}</td>
                            <td>{{$film->anno}}</td>
                            <td>{{$film->genere}}</td>
                            <td>{{$film->regista}}</td>
                            <td>{{$film->attori}}</td>
                        </tr>    
                        @endforeach
                        
                    </tbody>
                </table>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection