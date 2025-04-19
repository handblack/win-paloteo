<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VlUserConfig;
use App\Models\VlUserConfigGroup;
use Illuminate\Http\Request;

class ConfigADController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        if($request->has('q')){
            $q = $request->q;
            $q = str_replace('(','',$q);
            $q = str_replace("'",'',$q);
            $q = str_replace(' ','%',$q).'%';
        }else{
            $q = '%';
        }
        $result = VlUserConfig::orWhere('configname','LIKE',$q)
                        ->orderBy('configname','ASC')
                        ->paginate(env('PAGINATE',18));
        return view('system.config_ad_list',[
            'result' => $result,
            'q' => ($request->has('q')) ? $request->q : '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $row = new VlUserConfig();
        return view('system.config_ad_form',[
            'mode'  => 'new',
            'url'   => route('groupad.store'),
            'row'   => $row,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $request->validate([
            'configname'      => 'required',            
        ]);
        $row = new VlUserConfig();
        $row->fill($request->all());
        $row->token = md5(date('YmdHis'));
        $row->save();
        return redirect()->route('groupad.index')->with('message','Registro creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $row = VlUserConfig::whereToken($id)->first();
        if($row){
            session([
                'session_groupad_id'       => $row->id,
                'session_groupad_token'    => $row->token,
            ]);
            //Verificamos si existe el perfil
            $p = VlUserConfigGroup::whereUserConfigId($row->id)->first();
            if(!$p){
                $p = VlUserConfigGroup::create([
                    'user_config_id'   => $row->id,
                    'token'     => User::get_token(),
                ]);
            }
            return redirect()->route('groupadline.index',$p->token);
        }else{
            session_unset('session_groupad_id');
            session_unset('session_groupad_token');
            abort_if(!$row, 403, 'Prohibido');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $row = VlUserConfig::whereToken($id)->first();
        abort_if(!$row,403,'Token no valido');
        return view('system.config_ad_form',[
            'mode'  => 'edit',
            'url'   => route('groupad.update',$id),
            'row'   => $row,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $row = VlUserConfig::whereToken($id)->first();
        abort_if(!$row,403,'Token no valido');
        $row->fill($request->all());
        $row->save();
        return redirect()->route('groupad.index')->with('message','Registro actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $row = VlUserConfig::whereToken($id)->first();
        if($row){
            $data['id'] = 100;
            $data['message'] = 'Registro elminado!';
            $row->delete();
        }else{
            $data['id'] = 101;
            $data['message'] = 'El registro no existe o fue eliminado!';
        }
        return response()->json($data, $data['id'] == 100 ? 200 : 403);
    }
}
