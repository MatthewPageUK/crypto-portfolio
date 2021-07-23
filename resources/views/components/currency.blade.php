@props(['amount'])

<a title="{{ $amount->get() }}">{{ $amount->humanReadable() }}</a>
