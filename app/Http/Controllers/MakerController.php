<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Target;
use App\Models\maker;

class MakerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Target $target)
    {
       
        if (isset($target->makers)) {
            return ["data" => $target->makers];
        } else {
            return ["data" => "null"];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request ,$target_id)
    {
        $pat = maker::create([
            'target_id' => $request->target_id,
            'urlpattern' => $request->urlpattern,
            'htmlpattern' => $request->htmlpattern,
            'maker' => $request->maker
        ]);

        return ["data" => $pat];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $target_id , $maker_id)
    {
      
        $makoo = maker::find($maker_id);

        $makoo->urlpattern = $request->urlpattern;
        $makoo->htmlpattern = $request->htmlpattern;
        $makoo->maker = $request->maker;
        $makoo->active = $request->active;
        // $targ->url = $request->url;
        // $targ->save();


        // return ["data" => $targ->save()];


        return ["data" => $makoo->save()];


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($targetidm,$id)
    {
        $mak = maker::find($id);
        return ["data" => $mak->delete()];
    }
}
