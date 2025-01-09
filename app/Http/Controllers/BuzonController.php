<?php

namespace App\Http\Controllers;

use App\Models\BuzonVicidialInboundGroups;
use App\Models\User;
use App\Models\VlBuzonLog;
use App\Models\VlvBuzonLog;
use Illuminate\Http\Request;

class BuzonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = BuzonVicidialInboundGroups::whereGroupId('claro')
                                            ->first();
        $result = VlvBuzonLog::paginate(15);
        return view('buzon.index',[
            'row' => $row,
            'result' => $result,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'drop_call_seconds' => 'required|min:5,max:360'
        ]);
        $row = new VlBuzonLog();
        $row->user_id = auth()->user()->id;
        $row->token = User::get_token();
        $row->droptime = $request->drop_call_seconds;
        $row->save();
        // Ahora actualizamos en VICIDIAL
        BuzonVicidialInboundGroups::whereIn('group_id',[
            'claro','entel','movistar','Servicio_0911','VTR','WOM'
        ])->update([
            'drop_call_seconds' => $request->drop_call_seconds
        ]);
        return redirect()->route('buzon.index')->with('success','Tiempo actualizado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
