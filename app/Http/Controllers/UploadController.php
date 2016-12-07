<?php

namespace App\Http\Controllers;

use App\Environment;
use App\Farm;
use Illuminate\Http\Request;
use Storage;
use App\Picture;
use \Comodojo\Zip\Zip;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $files = Storage::disk('public')->files('uploads');
        dd($files);
    }

    public function picture()
    {
        $photo = Picture::all()->toArray();
        dd($photo);
    }

    public function env($all = '')
    {
        if ($all == 'show') {
            $env = Environment::all()->unique('datetime')->sortByDesc('datetime')->take(24)->toArray();
            dd($env);
        } else if ($all == 'all') {
            $env = Environment::all()->unique('datetime')->sortByDesc('datetime')->toJson();
            Storage::disk('public')->put('all.env', $env);
            return response()->download('./' . Storage::disk('public')->url('all.env'));
        } else {
            $env = Environment::all()->unique('datetime')->sortByDesc('datetime')->take(24)->toJson();
            $datetime = date('Y_m_d-H_i_s');
            Storage::disk('public')->put($datetime . '.env', $env);
            return response()->download('./' . Storage::disk('public')->url($datetime . '.env'));
        }
    }

    public function seed($all = '')
    {
        $start = time();

        if ($all == 'all') {
            if (Storage::disk('public')->exists('all.env')) {
                $data = Storage::disk('public')->get('all.env');
                $data = collect(json_decode($data, true));
                $data = $data->unique('datatime')->toArray();
                foreach ($data as $env) {
                    $environment = new Environment();
                    $environment->user_id = $env['user_id'];
                    $environment->datetime = $env['datetime'];
                    $environment->temp = $env['temp'];
                    $environment->pH = $env['pH'];
                    $environment->light = $env['light'];
                    $environment->created_at = $env['created_at'];
                    $environment->updated_at = $env['updated_at'];
                    $environment->save();
                }
            } else {
                return response('all.env is not existed.');
            }
        } else {
            $files = Storage::disk('public')->files();
            $envs = collect([]);
            foreach ($files as $file) {
                if (str_contains($file, '.env')) {
                    $json = Storage::disk('public')->get($file);
                    $data = json_decode($json, true);
                    foreach ($data as $env) {
                        $envs->push($env);
                    }
                }
            }

            $envs->unique('datetime')->toArray();
            foreach ($envs as $env) {
                $environment = new Environment();
                $environment->user_id = $env['user_id'];
                $environment->datetime = $env['datetime'];
                $environment->temp = $env['temp'];
                $environment->pH = $env['pH'];
                $environment->light = $env['light'];
                $environment->created_at = $env['created_at'];
                $environment->updated_at = $env['updated_at'];
                $environment->save();
            }
        }

        $end = time();

        return response('ok, excursion: ' . ($end - $start) . ' seconds');
    }

    public function editFarm(Farm $farm)
    {
        return view('editFarm', ['farm' => $farm]);
    }

    public function saveFarm(Request $request, Farm $farm)
    {
        $farm->user_id = $request->input('user_id');
        $farm->plant_id = $request->input('plant_id');
        $farm->plantname = $request->input('plantname');
        $farm->startdate = $request->input('startdate');
        if ($request->has('enddate')) {
            $farm->enddate = $request->input('enddate');
        }
        
        if(!$farm->save()) {
            return response('error');
        }
        return response('ok');
    }
}
