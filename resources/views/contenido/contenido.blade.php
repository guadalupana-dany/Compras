@extends('principal')
@section('contenido')
 <template v-if="menu==0">
     <principal></principal>
 </template>

 <template v-if="menu==1">
    <h1>Hola estamos en la 1</h1>
 </template>

 <template v-if="menu==2">
    <h1>Hola estamos en la 2</h1>
 </template>

 <template v-if="menu==3">
    <solici :ruta="ruta"></solici>
 </template>

 <template v-if="menu==4">
    <carga :ruta="ruta"></carga>
 </template>

 <template v-if="menu==5">
    <catalogos :ruta="ruta"></catalogos>
 </template>

 <template v-if="menu==6">
     <mostrarsolicitud :ruta="ruta"></mostrarsolicitud>
 </template>

 <template v-if="menu==7">
      <usuario :ruta="ruta"></usuario>
 </template>

 <template v-if="menu==8">
      <rol :ruta="ruta"></rol>
 </template>
 <template v-if="menu==10">
    <reporteconta :ruta="ruta"></reporteconta>
 </template>

 <template v-if="menu==11">
        <productobodega :ruta="ruta"></productobodega>
 </template>

 <template v-if="menu==12">
        <detallesolicitud :ruta="ruta"></detallesolicitud>
 </template>

 <template v-if="menu==13">
    <solicitudesall :ruta="ruta"></solicitudesall>
</template>
<template v-if="menu==14">
    <solicitudesrechazadas :ruta="ruta"></solicitudesrechazadas>
</template>


@endsection
