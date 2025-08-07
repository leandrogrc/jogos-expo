@extends('base')
@section('title', 'Menu')
@section('content')
<div class="container">
    <h1>Jogos da Expofeira</h1>

    <div id="buttons">
        <a href="{{ route('memoria') }}" class="game-btn">
            <div class="game-icon">🧠</div>
            <div class="game-title">Jogo da Memória</div>
            <div class="game-desc">Teste sua capacidade de memorização</div>
            <!-- <div class="play-btn">Jogar</div> -->
        </a>

        <a href="{{ route('forca') }}" class="game-btn">
            <div class="game-icon">🤔</div>
            <div class="game-title">Jogo das Palavras</div>
            <div class="game-desc">Adivinhe a palavra antes de ser enforcado</div>
            <!-- <div class="play-btn">Jogar</div> -->
        </a>
    </div>
</div>
@endsection