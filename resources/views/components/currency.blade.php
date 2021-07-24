@props(['amount'])

<a title="{{ $amount->getValue() }}">{{ $amount->humanReadable() }}</a>
