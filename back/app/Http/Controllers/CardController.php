<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::all();

        return $this->sendResponse($cards->toArray(), 'Получили список карточек');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'subtitle' => 'required',
            'content' => 'required',
            'image' => 'required|mimes:jpeg,bmp,png'
        ]);



        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации.', $validator->errors());
        }

        $image = $request->file('image');
        $imageName = $image->hashName();
        $destinationPath = public_path() . '/upload_images';
        $image->move($destinationPath, $imageName);

        $card = new Card();
        $card->title = $input['title'];
        $card->subtitle = $input['subtitle'];
        $card->content = $input['content'];
        $card->image = '/upload_images/'.$imageName;
        $card->save();

        return $this->sendResponse($card->toArray(), 'Карточка успешно создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        if (is_null($card)) {
            return $this->sendError('Карточка не найдена');
        }
        return $this->sendResponse($card->toArray(), 'Карточка успешно получена');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {

        $input = $request->all();


        $validator = Validator::make($input, [
            'title' => 'required',
            'subtitle' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Ошибка валидации.', $validator->errors());
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $destinationPath = public_path() . '/upload_images';
            $image->move($destinationPath, $imageName);
            $card->image = '/upload_images/'.$imageName;
        }
        $card->title = $input['title'];
        $card->subtitle = $input['subtitle'];
        $card->content = $input['content'];

        $card->save();
        return $this->sendResponse($card->toArray(), 'Карточка изменена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return $this->sendResponse($card->toArray(), 'Удаление выполнено.');
    }
}
