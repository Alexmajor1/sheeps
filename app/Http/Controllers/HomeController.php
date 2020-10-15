<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Sheep;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$sheeps = Sheep::where('user_id', auth()->user()->id)->get();
		$paddocks = array();
		if(count($sheeps) == 0){
			$n = 0;
			$counts[] = rand(1, 4);
			$n += last($counts);
			$counts[] = rand(1, 3);
			$n += last($counts);
			$counts[] = rand(1, 3);
			$n += last($counts);
			$counts[] = 10-$n;
			$n = 0;
			foreach($counts as $key => $count){
				for($i=1;$i<=$count;$i++){
					$n++;
					$sheep = new Sheep();
					$sheep->user_id = auth()->user()->id;
					$sheep->paddock_num = $key+1;
					$sheep->name = 'овечка '.$n;
					$sheep->save();
					$paddocks[$key+1][] = 'овечка '.$n;
				}
			}
		} else {
			$items=$sheeps->toArray();
			foreach($items as $value){
				$paddocks[$value['paddock_num']][] = $value['name'];
			}
		}
		
		return view('home', compact('paddocks'));
    }
}
