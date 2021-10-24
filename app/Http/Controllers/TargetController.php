<?php

namespace App\Http\Controllers;


use App\Models\Target;


use Illuminate\Http\Request;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $targets = Target::orderBy('id', 'DESC')->paginate(10, ['*'], 'page', $request->page);
        return $targets;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newtarget = Target::create([
            'title' => $request['title'], 'url' => $request['url'],
        ]);

        return ["data" => $newtarget];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Target $target)
    {

        return ["data" => $target->urlpatterns];
    }

    public function patterns(Target $target)
    {
        return ["data" => $target->urlpatterns];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $target)
    {
        //


        $target->title = $request->title;
        $target->url = $request->url;
        $target->active = $request->active;
        $target->makeractive = $request->makeractive;



        return ["data" => $target->save()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target)
    {
    
        return ["data" => $target->delete()];
    }
}
