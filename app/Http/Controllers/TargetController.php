<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\urlpattern;
use App\Models\maker;

use Illuminate\Http\Request;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    function addnew(Request $request)
    {


        $tar = Target::create([
            'title' => $request['title'], 'url' => $request['url'],
        ]);

        return ["data" => $tar];
    }


    function list(Request $request)
    {
        $zert = Target::orderBy('id', 'DESC')->paginate(10, ['*'], 'page', $request->page);
        return $zert;
    }



    function viewpatterns(Request $request)
    {


        $tari = Target::find($request->target_id);


        if (isset($tari->urlpatterns)) {
            return ["data" => $tari->urlpatterns];
        } else {
            return ["data" => null];
        }
    }





    function viewmakers(Request $request)
    {


        $tari = Target::find($request->target_id);


        if (isset($tari->urlpatterns)) {
            return ["data" => $tari->makers];
        } else {
            return ["data" => null];
        }
    }

    function addnewmaker(Request $request)
    {


        /* $tar = Target::create([
            'title' => $request['title'], 'url' => $request['url'],
        ]);
        */

        /*
 'target_id',
        'active',
        'urlpattern',
        'htmlpattern',
        'maker', /*
*/

        $pat = maker::create([
            'target_id' => $request->target_id,
            'urlpattern' => $request->urlpattern,
            'htmlpattern' => $request->htmlpattern,
            'maker' => $request->maker
        ]);

        return ["data" => $pat];
    }



    function editmaker(Request $request)
    {


        /* $tar = Target::create([
            'title' => $request['title'], 'url' => $request['url'],
        ]);
        */


        $makoo = maker::find($request->id);

        $makoo->urlpattern = $request->urlpattern;
        $makoo->htmlpattern = $request->htmlpattern;
        $makoo->maker = $request->maker;
        $makoo->active = $request->active;
        // $targ->url = $request->url;
        // $targ->save();


        // return ["data" => $targ->save()];


        return ["data" => $makoo->save()];
    }



    public function deletemaker(Request $request)
    {
        //
        $paz = maker::find($request->id);


        // $targ->save();


        return ["data" => $paz->delete()];
    }




    function addnewpattern(Request $request)
    {


        /* $tar = Target::create([
            'title' => $request['title'], 'url' => $request['url'],
        ]);
        */

        $pat = urlpattern::create([
            'target_id' => $request->target_id,
            'pattern' => $request->pattern,
            'savepattern' => $request->savepattern,
            'type' => $request->type
        ]);

        return ["data" => $pat];
    }

    public function deletepattern(Request $request)
    {
        //
        $paz = urlpattern::find($request->id);


        // $targ->save();


        return ["data" => $paz->delete()];
    }

    function editpattern(Request $request)
    {


        /* $tar = Target::create([
            'title' => $request['title'], 'url' => $request['url'],
        ]);
        */


        $patoo = urlpattern::find($request->id);

        $patoo->pattern = $request->pattern;
        $patoo->type = $request->type;
        $patoo->savepattern = $request->savepattern;

        // $targ->url = $request->url;
        // $targ->save();


        // return ["data" => $targ->save()];


        return ["data" => $patoo->save()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function show(Target $target)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function edit2(Target $target)
    {
        //
    }


    public function edit(Request $request)
    {
        //
        $targ = Target::find($request->id);

        $targ->title = $request->title;
        $targ->url = $request->url;
        $targ->active = $request->active;
        // $targ->save();


        return ["data" => $targ->save()];
    }


    public function delete(Request $request)
    {
        //
        $targ = Target::find($request->id);


        // $targ->save();


        return ["data" => $targ->delete()];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Target $target)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target)
    {
        //
    }
}
