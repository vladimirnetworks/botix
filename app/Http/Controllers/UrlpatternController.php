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
    public function update(Request $request, Target $target,   $urlpatternid)
    {




        $patoo = urlpattern::find($urlpatternid);





        $patoo->pattern = $request->pattern;
        $patoo->type = $request->type;
        $patoo->savepattern = $request->savepattern;




        return ["datax" => $patoo->save()];



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
