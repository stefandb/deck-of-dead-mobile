@props(['card'])

<div class="playing-card {{ $card->suitColor() === 'red' ? 'suit-red' : 'suit-black' }}">
    <div class="corner top-left">
        <span class="value">{{ $card->value }}</span>
        <span class="symbol">{{ $card->suitSymbol() }}</span>
    </div>
    <div class="center-symbol">{{ $card->suitSymbol() }}</div>
    <div class="corner bottom-right rotated">
        <span class="value">{{ $card->value }}</span>
        <span class="symbol">{{ $card->suitSymbol() }}</span>
    </div>
</div>
