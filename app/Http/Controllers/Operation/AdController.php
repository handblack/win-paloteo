<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use App\Models\VlUserAd;
use App\Models\VlUserUpload;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $grantname  = 'us';
    private $table      = 'user_ad';
    public function index(Request $request)
    {
        if($request->has('q')){
            $q = $request->q;
            $q = str_replace('(','',$q);
            $q = str_replace("'",'',$q);
            $q = str_replace("@",' ',$q);
            $q = '%'.str_replace(' ','%',$q).'%';
        }else{
            $q = session("session_{$this->table}_q_search");
        }

        $result = VlUserUpload::where(function($query) use ($q){
                                $query->orWhere('documentno','LIKE',$q);
                            })
                            #->where('datetrx',date('Y-m-d'))
                            ->where(function($query){
                                if(auth()->user()->isadmin == 'N'){
                                    $query->where('created_by',auth()->user()->id);
                                }
                            })
                            ->paginate(env('PAGINATE_MODAL',14))
                            ->withQueryString();
        session([
            "session_{$this->table}_q_search" => $request->q,
        ]);
        return view("operation.{$this->table}_list",[
            'result'    => $result,
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
        //
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
