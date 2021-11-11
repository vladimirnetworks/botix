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
    public function store(Target $target, Request $request)
    {

        $newmaker = new maker();

        $newmaker->urlpattern = $request->urlpattern;
        $newmaker->htmlpattern = $request->htmlpattern;
        $newmaker->maker = $request->maker;
        $newmaker->savetype = $request->savetype;
        $newmaker->remoteapi = $request->remoteapi;

        $target->makers()->save($newmaker);

        return ["data" => $newmaker];
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
    public function update(Request $request, Target $target, Maker $maker)
    {



        $maker->urlpattern = $request->urlpattern;
        $maker->htmlpattern = $request->htmlpattern;
        $maker->maker = $request->maker;
        $maker->active = $request->active;
        $maker->savetype = $request->savetype;
        $maker->remoteapi = $request->remoteapi;
        // $targ->url = $request->url;
        // $targ->save();


        // return ["data" => $targ->save()];


        return ["data" => $maker->save()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target,maker $maker)
    {
        return ["data" => $maker->delete()];
    }
}
