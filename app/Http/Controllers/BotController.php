<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Bot;
use Illuminate\Http\Request;

class BotController extends Controller
{
    /**
     * Display the bot home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bots = Bot::all();

        return view('bot.home')
            ->with('bots', $bots);
    }

    /**
     * Create a new bot.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tokens = Token::all()->sortBy('symbol');

        return view('bot.add')
            ->with('tokens', $tokens);
    }

    /**
     * Store a newly created Bot in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(StoreTransactionRequest $request)
    public function store(Request $request)
    {


        $faker = \Faker\Factory::create();
        $request->merge( array( 'name' => $faker->firstName(),
            'status' => 'waiting' ) );

        Bot::create( $request->all() );

        return redirect()
            ->route('bot.index')
            ->with('success', 'Bot created');
    }


}
