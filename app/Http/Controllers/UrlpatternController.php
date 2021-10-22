<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Target;
use App\Models\urlpattern;

class UrlpatternController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Target $target)
    {



        if (isset($target->urlpatterns)) {
            return ["data" => $target->urlpatterns];
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
    public function store(Request $request)
    {

        $pat = urlpattern::create([
            'target_id' => $request->target_id,
            'pattern' => $request->pattern,
            'savepattern' => $request->savepattern,
            'type' => $request->type
        ]);

        return ["data" => $pat];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Target $target, urlpattern $urlpattern)
    {
        return $urlpattern;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Target $target,  urlpattern $pattern)
    {




        //$patoo = urlpattern::find($urlpatternid);





        $pattern->pattern = $request->pattern;
        $pattern->type = $request->type;
        $pattern->savepattern = $request->savepattern;




        return ["datax" => $pattern->save()];



        //  $urlpattern->pattern = $request->pattern;


        //  return $urlpattern-save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($targetidm,$id)
    {
        $pattx = urlpattern::find($id);
        return ["data" => $pattx->delete()];
    }
}
