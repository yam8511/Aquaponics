<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plant;
use App\Farm;
use App\Contact;
use App\Picture;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

class PlantController extends Controller
{
    /**
     * Home Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('plant.index');
    }

    /**
     * Plant Library
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function library()
    {
        $data['plants'] = Plant::all();
        return view('plant.library', $data);
    }

    /**
     * Display Own Plant
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function myPlant()
    {
        if (!Auth::check()) {
            return redirect('login')->with('warning', '請先登入');
        }

        $data['pictures'] = [];
        $pictures = Picture::all()->unique('datetime')->sortByDesc('datetime')->take(8);
        foreach ($pictures as $picture) {
            $img = base64_decode($picture->photo);
            Storage::disk('public')->put($picture->filename, $img);
            $data['pictures'][$picture->datetime] = Storage::disk('public')->url($picture->filename);
        }

        $data['myPlants'] = Auth::user()->farms;

        return view('plant.my_plant', $data);
    }

    /**
     * Display Analysis AmChart
     * @param null $plant
     * @param null $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showData($plant = null, $user = null)
    {
        $data['environments'] = json_encode([
            'pH' => 'p.H值',
            'temp' => 'Temperature',
            'light' => 'Light',
            'ndvi' => 'NDVI'
        ]);

        $data['plants'] = [];
        $data['share'] = '0';

        if (is_null($plant) && is_null($user)) {
            if (Auth::check()) {
                $data['plants'] = Auth::user()->farms;
            } else {
                return redirect('login')->with('warning', '請先登入');
            }
        } else if (!is_null($plant) && !is_null($user)) {
            $data['share'] = '1';
            $user = Farm::where('user_id', $user)->where('plant_id', $plant)->first();
            if (is_null($user)) {
                return redirect('share/' . $plant);
            }

            $data['user'] = $user->user;
            $data['plants'] = $user->where('plant_id', $plant)->get();
        }

        // dd($data);
        return view('plant.vue_data', $data);
    }

    /**
     * Display Share Plant
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function share()
    {
        return view('plant.share');
    }

    /**
     * Display Share User
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function share_user($id)
    {
        $plant = Plant::find($id);

        if (is_null($plant)) {
            return redirect('share')->with('error', '不存在植物');
        }

        $data['plant'] = $plant;
        return view('plant.share_user', $data);
    }

    /**
     * Process Contact Information
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function contact(Request $request)
    {
        $input = $request->only('name', 'company', 'phone', 'email', 'message');

        if (count($request->all())) {
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|email',
                'message' => 'required',
            ], [
                'required' => ':attribute 為必填',
                'email' => ':attribute 須為Email格式'
            ]);

            DB::beginTransaction();
            try {
                $contact = new Contact();
                foreach ($input as $key => $value) {
                    $contact[$key] = $value;
                }
                if (!$contact->save()) {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect('contact')->with('success', '新增聯絡資訊失敗');
            }
            DB::commit();
            return redirect('contact')->with('success', '新增聯絡資訊成功');
        }

        return view('plant.contact');
    }
}
