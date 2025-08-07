<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class MemoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required|string|max:20'
        ]);

        try {
            $score = Score::create([
                'name' => $validated['name'],
                'time' => $validated['time'],
            ]);
            return back()->with('success', 'Pontuação salva com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao salvar o seu score.');
        }
    }

    public function ranking()
    {

        $scores = Score::orderBy('time', 'asc')->limit(5)->get();

        return view('games.memoria')->with('scores', $scores);
    }
}
