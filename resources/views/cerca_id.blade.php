@extends('layouts.layout')
  
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-4">
                    
                    <h3>Cerca Film per Id</h3>
                    @if (Session::get('warning'))
                        <div class="alert alert-warning" role="alert">
                            {{ Session::get('warning') }}
                        </div>
                    @endif
                    <form action="{{ route('cerca_id.post') }}" method="POST">
                          @csrf
                          <div class="form-group row">
                              <label for="film_id" class="col-md-4 col-form-label text-md-right">ID</label>
                              <div class="col-md-6">
                                  <input type="text" id="film_id" class="form-control" name="film_id" required />
                                  
                              </div>
                          </div>
                          <div class="form-group row mt-2">
                            <div class="col-md-6 offset-md-4 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    Cerca
                                </button>
                            </div>
                          </div>
  
                          
                    </form>
        </div>
    </div>
</div>
@endsection