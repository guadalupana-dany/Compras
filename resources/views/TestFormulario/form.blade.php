@extends('layouts.app')

@section('content')

        <form action="{{ route('user/store') }}" method="post"  class="form-horizontal">
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                <div class="col-md-9">
                    <input type="text"  class="form-control" name="name" placeholder="Nombre de usuario" >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                <div class="col-md-9">
                    <input type="email" class="form-control" name="email" placeholder="Enter Email" >
                </div>
            </div>
            <div class="form-group row" v-if="password == '' ">
                <label class="col-md-3 form-control-label" for="email-input">Password</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" name="password" placeholder="Enter Password" >
                </div>
            </div>
            <button type="submit">Enviar</button>

        </form>
        @endsection

