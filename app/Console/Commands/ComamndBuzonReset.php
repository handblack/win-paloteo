<?php

namespace App\Console\Commands;

use App\Models\BuzonVicidialInboundGroups;
use App\Models\User;
use App\Models\VlBuzonLog;
use Illuminate\Console\Command;

class ComamndBuzonReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:buzon-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Ejecutando ');
        $droptime = 360;
        //Leemos el valor
        $read = BuzonVicidialInboundGroups::whereGroupId('claro')
                                            ->first();
        if( $read->drop_call_seconds <> $droptime) {
            BuzonVicidialInboundGroups::whereIn('group_id',[
                'claro','entel','movistar','Servicio_0911','VTR','WOM'
            ])->update([
                'drop_call_seconds' => $droptime
            ]);
            // Registramos el LOG 
            $row = new VlBuzonLog();
            $row->user_id   = 1;
            $row->token     = User::get_token();
            $row->droptime  = $droptime;
            $row->save();
        }
        $this->info('droptime=360');
        $this->info('terminado');
    
    }
}
