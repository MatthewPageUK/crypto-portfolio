@props(['token'])

<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
    <div class="tradingview-widget-container__widget"></div>
    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-tickers.js" async>
        {
            "symbols": [
                {
                    "description": "{{ $token->name }}",
                    "proName": "COINBASE:{{ strtoupper($token->symbol) }}GBP"
                }
            ],
            "colorTheme": "light",
            "isTransparent": false,
            "showSymbolLogo": true,
            "locale": "uk"
        }
    </script>
</div>
