<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\VlUserConfig;
use App\Models\VlUserConfigGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfigADGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        if(!Session::has('session_groupad_id')){
            return redirect()->route('groupad.index');
        }
        if($request->has('q')){
            $q = $request->q;
            $q = str_replace('(','',$q);
            $q = str_replace("'",'',$q);
            $q = str_replace(' ','%',$q).'%';
        }else{
            $q = '%';
        }
        $result = VlUserConfigGroup::whereUserConfigId(session('session_groupad_id'))
                                ->paginate(5);
        return view('system.config_ad_group_list',[
            'result'    => $result,
            'url'       => route('groupadline.store'),
            'mode'      => 'new',
            'header'    => VlUserConfig::find(session('session_groupad_id')),
            'q'         => ($request->has('q')) ? $request->q : '',
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
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $request->validate([
            'user_config_id'    => 'required',            
            'groupname'         => 'required',            
        ]);
        $row = new VlUserConfigGroup();
        $row->fill($request->all());
        $row->save();
        return redirect()->route('groupad.index')->with('message','Registro creado');
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
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $row = VlUserConfigGroup::whereToken($id)->first();
        abort_if(!$row,403,'Token no valido');
        return view('system.config_ad_group_form',[
            'mode'  => 'edit',
            'url'   => route('groupadline.update',$id),
            'row'   => $row,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(auth()->user()->isadmin == 'N',403,'Acceso restringido');
        $row = VlUserConfigGroup::whereToken($id)->first();
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
        $row = VlUserConfigGroup::whereToken($id)->first();
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

    public function create_key($id){
        $header = VlUserConfig::whereToken($id)->first();
        return view('system.config_ad_group_form_new',[
            'header' => $header,
            'mode' => 'new',
            'url'   => route('groupadline.store')
        ]);
    }
}
