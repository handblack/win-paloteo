<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use App\Models\VlUserAd;
use App\Models\VlUserConfig;
use App\Models\VlUserConfigGroup;
use App\Models\VlUserUpload;
use App\Models\VlUserUploadLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
                                $query->orWhere('filename','LIKE',$q);
                            })
                            #->where('datetrx',date('Y-m-d'))
                            ->where(function($query){
                                if(auth()->user()->isadmin == 'N'){
                                    $query->where('created_by',auth()->user()->id);
                                }
                            })
                            ->orderBy('created_at','DESC')
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
        $mod = ['download|Y','download|N'];
        abort_if(!in_array($id,$mod),403,'No se puede descargar');
        $filename = app(VlUserAd::class)->getTable() . date("_Ymd_His");
        $p = explode('|',$id);
        $result = VlUserAd::whereIsactive($p[1])
                            ->get();
        return response()->streamDownload(function () use ($result,$p) {
            $spreadsheet = new Spreadsheet;
            $sheet       = $spreadsheet->getActiveSheet();
            if($p[1] == 'Y'){
                $sheet->setCellValue('B1', 'ACTIVOS');
            }else{
                $sheet->setCellValue('B1', 'ELIMINADOS');
            }
            $sheet->setCellValue('A2', 'TDOC');
            $sheet->setCellValue('B2', 'NRO_DOC');
            $sheet->setCellValue('C2', 'PATERNO');
            $sheet->setCellValue('D2', 'MATERNO');
            $sheet->setCellValue('E2', 'NOMBRES');
            $sheet->setCellValue('F2', 'CAMPAÑA');
            $key=2;
            foreach($result as $item){
                $key++;
                #$sheet->setCellValue("A$key", $item->id);
                $sheet->setCellValue("A$key", $item->doctype);
                $sheet->setCellValue("B$key", $item->documentno);
                $sheet->setCellValue("C$key", $item->paterno);
                $sheet->setCellValue("D$key", $item->materno);
                $sheet->setCellValue("E$key", $item->nombre);
                $sheet->setCellValue("F$key", $item->campaign);
            }
            $cols = explode(',','A,B,C,D,E,F');
            foreach($cols as $col){
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            (new Xlsx($spreadsheet))->save('php://output');
        }, "{$filename}.xlsx");
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

    public function user_create_upload_excel(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $error = 0;
        $file  = $request->file('file');
        $index = 0;
        
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheetData   = $spreadsheet->getActiveSheet()->toArray();
        // Hacemos validacion
        $msg = 'Hay error';
        foreach ($sheetData as $index => $row) {
            if ($index == 0) {
                continue;
            }
            if(isset($row[0])){
                $dt = strtolower($row[0]);
                $dn = $row[1];
                $ap = strtoupper($row[2]);
                $am = strtoupper($row[3]);
                $no = strtoupper($row[4]);
                $cp = $row[5];
                //empezamos la validacion
                if(!in_array($dt,['dni','ce'])){
                    $msg = 'En el tipo de documento debes especificad dni o ce';
                    $error++;
                    break;
                }else{
                    switch($dt){
                        case 'dni':
                            if(strlen($dn) != 8){
                                $msg = 'El dni debe tener 8 digitos';
                                $error++;
                            }
                            break;
                        case 'ce':
                            if(strlen($dn) != 9){
                                $msg = 'El ce debe tener 9 digitos';
                                $error++;
                            }
                            break;
                    }
                    if($error){
                        break;
                    }
                }
                if(!trim($ap)){
                    $msg = 'Debes especificar el apellido paterno';
                    $error++;
                    break; 
                }
                if(!trim($no)){
                    $msg = 'Debes especificar el nombre';
                    $error++;
                    break; 
                }
                if(!trim($cp)){
                    $msg = 'Debes especificar la campaña';
                    $error++;
                    break; 
                }
                $cam = VlUserConfig::whereConfigname($cp)
                                    ->whereIsactive('Y')
                                    ->first();
                if(!$cam){
                    $msg = 'La campaña especificada no existe';
                    $error++;
                    break;
                }
            }
            $index++;
        }
        if($error){
            $index++;
            return redirect()->route('ad.index')->with('error', "Fila {$index} => {$msg}");        
        }
        // Procesamos todo        
        $head = VlUserUpload::create([
            'created_by'    => auth()->user()->id,
            'datetrx'       => Carbon::now(),
            'mode'          => 'I',
            'isactive'      => 'Y',
            'filename'      => $file->getClientOriginalName(),
            'size'          => $file->getSize(),
        ]);
        foreach ($sheetData as $index => $row) {
            // Ignorar la primera fila si contiene encabezados
            if ($index == 0) {
                continue;
            }
            $dt = strtolower(trim($row[0]));
            $dn = $row[1];
            $ap = strtoupper($row[2]);
            $am = strtoupper($row[3]);
            $no = strtoupper($row[4]);
            $cp = $row[5];
            
            $un = strtolower(trim($dn).'@contact.com');
            $usr = VlUserAd::whereEmail($un)
                            ->whereDoctype($dt)
                            ->first();
            if($usr){
                $usr->isactive = 'Y';
                $usr->save();
            }else{
                $usr = VlUserAd::create([
                    'doctype'       => $dt,
                    'paterno'       => $ap,
                    'materno'       => $am,
                    'nombre'        => $no,
                    'documentno'    => $dn,
                    'campaign'      => $cp,
                    'accountname'   => substr($no,0,1).strtolower($ap).'_'.$dn,
                    'accountpass'   => 'Peru++2023',
                    'email'         => $un,
                ]);
            }

            $lin = VlUserUploadLine::create([
                'user_upload_id'=> $head->id,
                'doctype'       => $dt,
                'paterno'       => $ap,
                'materno'       => $am,
                'nombre'        => $no,
                'documentno'    => $dn,
                'campaign'      => $cp,
                'email'         => $un,
            ]);
            // Activamos al USUARIO
            if(env('APP_ENV','local') == 'production'){
                $this->ad_create_user($usr);
                $this->ad_member_user($usr);
            }
        }
        // Aqui ejecutamos los FILL para completar otros campos adicionaes en las alertas
        return redirect()->route('ad.index')->with('message', 'Archivo cargado'.($error > 0 ? ", se encontraron {$error} inconsistencias" : ''));
    } 

    private function ad_connect(){
        //Ejecutamos el servicio del AD
        $ldap_host = env('LDAP_CONTACT_HOST','ldap://localhost');
        $ldap_port = env('LDAP_CONTACT_PORT',389);
        $ldap_conn = ldap_connect($ldap_host, $ldap_port);
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);
        return $ldap_conn;
    }

    private function ad_member_user($usr){
    }

    private function ad_create_user($usr){
        $pro = VlUserConfig::whereConfigname($usr->campaign)
                                ->first();
        $prl = VlUserConfigGroup::whereUserConfigId($pro->id)
                                ->whereIstype('P')  //Buscamos el profile
                                ->first();
        if(!$prl){
            return false;
        }
        $ldap_user = env('LDAP_CONTACT_USER','user@domain.ad'); 
        $ldap_pass = env('LDAP_CONTACT_PASS','');
        #$ldap_conn = $this->ad_connect();
        $ldap_host = env('LDAP_CONTACT_HOST','ldap://localhost');
        $ldap_port = env('LDAP_CONTACT_PORT',389);
        $ldap_conn = ldap_connect($ldap_host, $ldap_port);
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

        if (ldap_bind($ldap_conn, $ldap_user, $ldap_pass)) {
            $dn = "CN=Juan Perez,OU=win,OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com";
            //    "CN=LOMBARDINI INGA LUIGI,OU=win,OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com
            $dn = implode(',',[
                "CN={$usr->paterno} {$usr->nombre}",
                "OU={$pro->configname}",
                $prl->groupname
            ]);
            #$dn = "CN=Juan Perez,OU=win,OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com";
            #$dn = "CN={$usr->paterno} {$usr->materno} $usr->nombre,OU=win,OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com";

            // DN del grupo
            #$group_dn = [];
            #$group_dn[0] = "CN=ESTRUCTURA,OU=CONTACT,DC=contact,DC=com";
            #$group_dn[1] = "CN=win,OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com";
            #$group_dn = "CN=ESTRUCTURA,OU=CONTACT,DC=contact,DC=com";

            // Atributos del nuevo usuario
            $info = [];
            $info["cn"]                 = "{$usr->paterno} {$usr->nombre}";
            $info["givenName"]          = "{$usr->nombre}";
            $info["sn"]                 = "{$usr->paterno}";
            $info["objectClass"]        = ["top", "person", "organizationalPerson", "user"];
            $info["sAMAccountName"]     = $usr->accountname;
            $info["userPrincipalName"]  = $usr->email;
            $info["displayName"]        = "{$usr->paterno} {$usr->materno} {$usr->nombre}";
            $info["mail"]               = $usr->email;
            #$info["member"] = $group_dn;

            // Crear el usuario
            #dd($dn);
            if (ldap_add($ldap_conn, $dn, $info)) {
                #echo "Usuario creado correctamente.";
            } else {
                echo "Error al crear usuario: " . ldap_error($ldap_conn);
                die();
            }

            ldap_unbind($ldap_conn);
        } else {
            echo "Error al conectar o autenticar: " . ldap_error($ldap_conn);
        }

    }

    public function user_delete_upload_excel(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $error = 0;
        $file  = $request->file('file');
        $index = 0;
        
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheetData   = $spreadsheet->getActiveSheet()->toArray();
        // Hacemos validacion
        $msg = 'Hay error';
        foreach ($sheetData as $index => $row) {
            if ($index == 0) {
                continue;
            }
            if(isset($row[0])){
                $dt = strtolower($row[0]);
                $dn = $row[1];
                $ap = strtoupper($row[2]);
                $am = strtoupper($row[3]);
                $no = strtoupper($row[4]);
                //$cp = $row[5];
                //empezamos la validacion
                if(!in_array($dt,['dni','ce'])){
                    $msg = 'En el tipo de documento debes especificad dni o ce';
                    $error++;
                    break;
                }else{
                    switch($dt){
                        case 'dni':
                            if(strlen($dn) != 8){
                                $msg = 'El dni debe tener 8 digitos';
                                $error++;
                            }
                            break;
                        case 'ce':
                            if(strlen($dn) != 9){
                                $msg = 'El ce debe tener 9 digitos';
                                $error++;
                            }
                            break;
                    }
                    if($error){
                        break;
                    }
                }
                if(!trim($ap)){
                    $msg = 'Debes especificar el apellido paterno';
                    $error++;
                    break; 
                }
                if(!trim($no)){
                    $msg = 'Debes especificar el nombre';
                    $error++;
                    break; 
                }                 
                $un = strtolower(trim($dn).'@contact.com');
                $usr = VlUserAd::whereEmail($un)
                                ->whereDoctype($dt)
                                ->first();
                if(!$usr){
                    $msg = "El usuario {$dt} {$dn} no existe!";
                    $error++;
                    break;
                }

            }
            $index++;
        }
        if($error){
            $index++;
            return redirect()->route('ad.index')->with('error', "Fila {$index} => {$msg}");        
        }
        // Procesamos todo        
        $head = VlUserUpload::create([
            'created_by'    => auth()->user()->id,
            'datetrx'       => Carbon::now(),
            'mode'          => 'O',
            'isactive'      => 'Y',
            'filename'      => $file->getClientOriginalName(),
            'size'          => $file->getSize(),
        ]);
        foreach ($sheetData as $index => $row) {
            // Ignorar la primera fila si contiene encabezados
            if ($index == 0) {
                continue;
            }
            $dt = strtolower(trim($row[0]));
            $dn = $row[1];
            $ap = strtoupper($row[2]);
            $am = strtoupper($row[3]);
            $no = strtoupper($row[4]);
            //$cp = $row[5];
            
            $un = strtolower(trim($dn).'@contact.com');
            $usr = VlUserAd::whereEmail($un)
                            ->whereDoctype($dt)
                            ->first();
            if($usr){
                $usr->isactive = 'N';
                $usr->save();
            }else{
                /*
                $usr = VlUserAd::create([
                    'doctype'       => $dt,
                    'paterno'       => $ap,
                    'materno'       => $am,
                    'nombre'        => $no,
                    'documentno'    => $dn,
                    'campaign'      => $cp,
                    'email'         => $un,
                ]);
                */
            }

            VlUserUploadLine::create([
                'user_upload_id'=> $head->id,
                'doctype'       => $dt,
                'paterno'       => $ap,
                'materno'       => $am,
                'nombre'        => $no,
                'documentno'    => $dn,
                //'campaign'      => $cp,
                'email'         => $un,
            ]);
            
        }
        // Aqui ejecutamos los FILL para completar otros campos adicionaes en las alertas
        return redirect()->route('ad.index')->with('message', 'Archivo cargado'.($error > 0 ? ", se encontraron {$error} inconsistencias" : ''));
    } 

    


}
