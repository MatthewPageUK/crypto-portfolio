<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Bot;
use App\Models\BotHistory;
use Carbon\Carbon;
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
     * Display the individual bot
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Bot $bot)
    {
        return view('bot.show')
            ->with('bot', $bot);
    }

    /**
     * Display the individual bot memories - wrong controller :)
     *
     * @return \Illuminate\Http\Response
     */
    public function memories(Bot $bot)
    {
        return view('bot.memories')
            ->with('bot', $bot);
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
        $request->merge( array(
            'name' => $faker->firstName(),
            'status' => 'waiting',
            'started' => Carbon::now() ) );

        $bot = Bot::create( $request->all() );
        $bot->stop_price = $bot->stopPrice();
        $bot->save();

        $bh = BotHistory::create([
            'bot_id' => $bot->id,
            'target_price' => $bot->targetPrice(),
            'stop_loss' => $bot->stop_price,
            'price' => 0,
            'note' => 'Bot started....',
        ]);

        return redirect()
            ->route('bot.index')
            ->with('success', 'Bot created');
    }


}
