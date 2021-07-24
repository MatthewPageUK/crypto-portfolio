@props(['quantity'])

<a title="{{ $quantity->getValue() }}">{{ $quantity->humanReadable() }}</a>
