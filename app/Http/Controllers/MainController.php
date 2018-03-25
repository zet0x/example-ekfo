<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\Role;
use App\Rank;

class MainController extends Controller
{
    public $posts;

    public function __construct()
    {
    	$posts = Post::get();
        foreach ($posts as $key => $post) {
            $post->rank = $this->get_rank_of_post($post->id);
        }
        $this->posts = $posts;
    }

    protected function get_rank_of_post($post_id)
    {
        $rank = Rank::where('post_id',$post_id)->get();
        $rank_sum = 0;
        for($i=0;$i<count($rank);$i++)
        { 
            $rank_sum = $rank_sum + $rank[$i]->rank;
        }
        if(count($rank) == 0)
        {
            $count = 1;
        }
        else
        {
             $count =count($rank);
        }
        return $rank_sum / $count;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'problem' => 'required|string|max:255',
            'decision' => 'required|string|max:255',
        ]);
    }

    public function index(Request $request)
    {
        if(Auth::user())
        {
           $arResult['user'] = Auth::user();
           $arResult['role'] = Role::where('id',Auth::user()->role_id)->first();
           


           if($arResult['role']->id == 1 and $request->problem and $request->decision)
           {
                $data = $this->validator($request->all());
                
                if ($data->fails())
                {
                    return $validator->errors()->all();
                }
                else
                {
                    $post = new Post;
                    $post->user_id = Auth::user()->id;
                    $post->problem = $request->problem;
                    $post->decision = $request->decision;
                    $post->save();
                    return redirect('/'); 
                }
                
           }    
        }
        $arResult['posts'] = $this->posts; 
    	return view('welcome',[
    			"arResult" => $arResult,
    		]);
    }
    public function rank(Request $request)
    {
        if(Auth::user()->role_id == 2)
        {
            if(!empty($request->elem) and !empty($request->rank) and $request->rank >= 0 and $request->rank <= 5)
            {
                $user = Auth::user();
                $post_id = explode('-', $request->elem)[1];
                $post = Post::where('id',$post_id)->firstOrFail();

                $rank_user = Rank::where('user_id',$user->id)->where('post_id',$post->id)->first();
                if(!empty($rank_user))
                {
                    $rank_user->rank = $request->rank;
                    $rank_user->save();  
                }
                else
                {
                    $rank = new Rank;
                    $rank->post_id = $post->id;
                    $rank->user_id = $user->id;
                    $rank->rank = $request->rank;
                    $rank->save();  
                }

                $result_rank = Rank::where('post_id',$post->id)->first();
                echo $result_rank->rank;
            }
            else
            {
                echo "error";
            }
        }
        else
        {
            echo "error";
        }
        
    }
}
