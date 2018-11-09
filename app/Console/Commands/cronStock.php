<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\ControlBodega;
use Illuminate\Support\Facades\Mail;
class cronStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificando Stock en Inventario';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mytime = Carbon::now('America/Guatemala');
        \Log::debug('Ejecutandose ' . $mytime);
        \Log::info('********');
        $productoB =  ControlBodega::join('productos','control_bodega_.idProducto','=','productos.id')
        ->select('productos.nombre','control_bodega_.total_stock')
        ->where('control_bodega_.total_stock','<','5')->get();

        $pdf = \PDF::loadView('pdf.controlStock',['productos' => $productoB]);
        $pdfPath = $pdf->download('stock.pdf');
        //SE CREA LA PLANTILLA Y [] DENTRO DE ESTO LE PASAMOS LOS PARAMETROS A LA PLANTILLA PARA QUE SEA DINAMICO
        Mail::send([],[],function($m) use ($pdfPath){
            //AQUIEN MANDA EL CORREO
            $m->from('alerta@micoopeguadalupana.com.gt','MicoopeGuadalupana');
            //AQUIEN LE LLEGA EL CORREO
            $m->to('dany.diaz@micoopeguadalupana.com.gt','Dany Diaz')->subject('Control de Stock');
            //Adjunta el archivo pdf
            $m->attachData($pdfPath,'control_Stock.pdf', [
                'mime' => 'application/pdf',
            ]);

        });
        DB::insert('insert into log (tipoAccion, descripcion, id_Objeto_Alterado, idUser, created_at) values (?, ?, ?, ?, ?)', ['Envio', 'Se envio Correo stock', 0 , 2 , $mytime]);


    }
}
