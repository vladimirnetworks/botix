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
    public function store(Target $target , Request $request)
    {


        $pat = new urlpattern();

        $pat->pattern = $request->pattern;
        $pat->savepattern = $request->savepattern;
        $pat->type = $request->type;

        $pat->whereParentpattern = $request->whereParentpattern;
        $pat->htmlpipe = $request->htmlpipe;

        $target->urlpatterns()->save($pat);

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
        $pattern->whereParentpattern = $request->whereParentpattern;
        $pattern->htmlpipe = $request->htmlpipe;

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
    public function destroy(Target $target,urlpattern $pattern)
    {
       
        return ["data" => $pattern->delete()];
    }
}
