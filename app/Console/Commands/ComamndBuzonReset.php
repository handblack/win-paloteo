<?php

namespace App\Console\Commands;

use App\Models\BuzonVicidialInboundGroups;
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
        BuzonVicidialInboundGroups::whereIn('group_id',[
            'claro','entel','movistar','Servicio_0911','VTR','WOM'
        ])->update([
            'drop_call_seconds' => 360
        ]);
        $this->info('droptime=360');
        $this->info('terminado');
    }
}
