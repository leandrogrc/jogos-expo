<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;

class WordsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required|string|max:20',
            'attempts' => 'required|integer'
        ]);

        $errors = 5 - $request->attempts;

        try {
            $score = Score::create([
                'name' => $validated['name'],
                'time' => $validated['time'],
                'attempts' => $errors,
                'game' => 'words'
            ]);
            return back()->with('success', 'Pontuação salva com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao salvar o seu score.');
        }
    }

    public function ranking()
    {

        $scores = Score::orderBy('attempts', 'desc')->orderBy('time', 'asc')->where('game', 'words')->limit(5)->get();

        return view('games.forca')->with('scores', $scores);
    }
}
