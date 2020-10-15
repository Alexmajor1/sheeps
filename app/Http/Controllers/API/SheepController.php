<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Sheep;
use App\History;

class SheepController extends Controller
{

    public function index(Request $request)
    {
        $paddocks = Sheep::where('user_id', auth()->user()->id)->get();
		
		$sheeps = array();
		foreach($paddocks as $sheep){
			$sheeps[$sheep['paddock_num']][] = $sheep['name'];
		}
		
		return array($sheeps, 200);
    }

    public function show(Request $request)
    {
        $sheep = Sheep::where('user_id', auth()->user()->id)
			->where('name', $request->post('name'))->get();
		
		return array($sheep, 200);
    }

    public function store(Request $request)
    {
        $sheep = new Sheep();
		$sheep->user_id = auth()->user()->id;
		$sheep->paddock_num = $request->post('paddock_num');
		$sheep->name = $request->post('name');
		if(!$sheep->save()){
			return array(
				array(
					'error' => true,
					'message' => 'sheep not added'
				),
				500
			);
		}
		
		$history = new History();
		$history->sheep = $request->post('name');
		$history->operation = 'добавлена в загон '.$request->post('paddock_num');
		if(!$history->save()){
			return array(
				array(
					'error' => true,
					'message' => 'history event not created'
				),
				500
			);
		}
		
		return array(
			array(
				'error' => false,
				'message' => 'sheep added'
			),
			200
		);
    }

    public function update(Request $request)
    {
        $sheep = Sheep::where('user_id', '=', auth()->user()->id)
			->where('name', '=', $request->post('name'));
			
		$from = $sheep->get()[0]['paddock_num'];
		
		if(!$sheep->update(['paddock_num' => $request->post('paddock_num')])){
			return array(
				array(
					'error' => true,
					'message' => 'sheep not moved'
				),
				500
			);
		}
		
		$history = new History();
		$history->sheep = $request->post('name');
		$history->operation = 'пересажена из загона '.$from.' в загон '.$request->post('paddock_num');
		if(!$history->save()){
			return array(
				array(
					'error' => true,
					'message' => 'history event not created'
				),
				500
			);
		}
		
		return array(
			array(
				'error' => false,
				'message' => 'sheep moved'
			),
			200
		);
    }
	
	public function destroy(Request $request)
	{
		$sheep = Sheep::where('user_id', '=', auth()->user()->id)
			->where('name', '=', $request->post('name'));
			
		$from = $sheep->get()[0]['paddock_num'];
		
		$sheep = $sheep->first();
		if(!$sheep->delete()){
			return array(
				array(
					'error' => true,
					'message' => 'sheep not removed'
				),
				500
			);
		}
		
		$history = new History();
		$history->sheep = $request->post('name');
		$history->operation = 'забрана из загона '.$from;
		if(!$history->save()){
			return array(
				array(
					'error' => true,
					'message' => 'history event not created'
				),
				500
			);
		}
		
		return array(
			array(
				'error' => false,
				'message' => 'sheep deleted'
			),
			200
        );
	}
}
