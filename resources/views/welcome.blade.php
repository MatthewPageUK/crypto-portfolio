<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">

                    <svg xmlns="http://www.w3.org/2000/svg" 
                        viewBox="0 0 349.989 349.989" 
                        xml:space="preserve"
                        width="100"
                        height="100"
                        class="block ">
                        <g id="icon" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(-1.9443833333333203 -1.9443833333333203) scale(3.89 3.89)" >
                            <path d="M 82.355 31.82 c 1.654 0 3 1.346 3 3 c 0 1.654 -1.346 3 -3 3 c -1.654 0 -3 -1.346 -3 -3 C 79.355 33.165 80.701 31.82 82.355 31.82 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 65.728 19.935 c 1.654 0 3 1.346 3 3 c 0 1.654 -1.346 3 -3 3 c -1.654 0 -3 -1.346 -3 -3 C 62.729 21.281 64.074 19.935 65.728 19.935 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 54.337 2.114 c 1.654 0 3 1.346 3 3 s -1.346 3 -3 3 c -1.654 0 -3 -1.346 -3 -3 S 52.682 2.114 54.337 2.114 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <circle cx="34.82" cy="7.64" r="3" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
                            <path d="M 21.271 22.935 c 0 -1.654 1.346 -3 3 -3 s 3 1.346 3 3 c 0 1.654 -1.346 3 -3 3 S 21.271 24.59 21.271 22.935 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 5.114 38.664 c -1.654 0 -3 -1.346 -3 -3 s 1.346 -3 3 -3 c 1.654 0 3 1.346 3 3 S 6.768 38.664 5.114 38.664 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 7.645 58.18 c -1.654 0 -3 -1.346 -3 -3 c 0 -1.654 1.346 -3 3 -3 c 1.654 0 3 1.346 3 3 C 10.645 56.835 9.299 58.18 7.645 58.18 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 24.271 70.065 c -1.654 0 -3 -1.346 -3 -3 c 0 -1.654 1.346 -3 3 -3 s 3 1.346 3 3 C 27.271 68.719 25.926 70.065 24.271 70.065 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 35.664 87.886 c -1.654 0 -3 -1.346 -3 -3 c 0 -1.654 1.346 -3 3 -3 c 1.654 0 3 1.346 3 3 C 38.663 86.54 37.318 87.886 35.664 87.886 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 58.18 82.355 c 0 1.654 -1.346 3 -3 3 c -1.654 0 -3 -1.346 -3 -3 c 0 -1.654 1.346 -3 3 -3 C 56.835 79.355 58.18 80.701 58.18 82.355 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 68.729 67.065 c 0 1.654 -1.346 3 -3 3 c -1.654 0 -3 -1.346 -3 -3 c 0 -1.654 1.346 -3 3 -3 C 67.383 64.065 68.729 65.41 68.729 67.065 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <ellipse cx="45" cy="44.89" rx="18" ry="18" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(0.1602 -0.9871 0.9871 0.1602 -6.5226 82.1218) "/>
                            <path d="M 84.886 57.337 c -1.654 0 -3 -1.346 -3 -3 s 1.346 -3 3 -3 c 1.654 0 3 1.346 3 3 S 86.541 57.337 84.886 57.337 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(252,198,45); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 51.892 48.182 c -0.828 0 -1.5 0.671 -1.5 1.5 c 0 0.956 -0.593 1.765 -1.294 1.765 h -8.196 c -0.701 0 -1.294 -0.809 -1.294 -1.765 v -9.576 c 0 -0.956 0.593 -1.764 1.294 -1.764 h 8.196 c 0.701 0 1.294 0.808 1.294 1.764 c 0 0.829 0.672 1.5 1.5 1.5 s 1.5 -0.671 1.5 -1.5 c 0 -2.627 -1.926 -4.764 -4.294 -4.764 H 46.5 V 33.53 c 0 -0.829 -0.671 -1.5 -1.5 -1.5 s -1.5 0.671 -1.5 1.5 v 1.812 h -2.598 c -2.368 0 -4.294 2.137 -4.294 4.764 v 9.576 c 0 2.627 1.926 4.765 4.294 4.765 H 43.5 v 1.812 c 0 0.828 0.671 1.5 1.5 1.5 s 1.5 -0.672 1.5 -1.5 v -1.812 h 2.599 c 2.368 0 4.294 -2.138 4.294 -4.765 C 53.392 48.853 52.72 48.182 51.892 48.182 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(69,86,60); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path d="M 84.886 49.223 c -1.117 0 -2.143 0.368 -2.984 0.977 l -2.767 -2.25 c -0.268 -0.218 -0.602 -0.336 -0.946 -0.336 H 65.253 c 0.119 -0.892 0.201 -1.795 0.201 -2.719 c 0 -1.144 -0.117 -2.258 -0.298 -3.351 h 10.501 c 0.345 0 0.679 -0.119 0.946 -0.336 l 2.766 -2.25 c 0.841 0.608 1.867 0.977 2.985 0.977 c 2.824 0 5.114 -2.289 5.114 -5.114 c 0 -2.824 -2.289 -5.114 -5.114 -5.114 s -5.114 2.289 -5.114 5.114 c 0 0.613 0.126 1.194 0.324 1.739 l -2.44 1.985 H 64.437 c -0.799 -2.446 -2.036 -4.693 -3.635 -6.636 c 0.001 -0.001 0.001 -0.001 0.002 -0.002 L 64 28.136 c 0.089 -0.106 0.155 -0.222 0.211 -0.342 c 0.482 0.151 0.985 0.255 1.517 0.255 c 2.824 0 5.114 -2.289 5.114 -5.114 c 0 -2.824 -2.289 -5.114 -5.114 -5.114 s -5.114 2.289 -5.114 5.114 c 0 1.219 0.444 2.324 1.156 3.202 c -0.019 0.02 -0.042 0.036 -0.06 0.058 l -3.006 3.545 c -2.289 -2.072 -5.041 -3.633 -8.092 -4.503 V 12.344 l 1.985 -2.44 c 0.545 0.198 1.125 0.324 1.739 0.324 c 2.824 0 5.114 -2.289 5.114 -5.114 S 57.161 0 54.337 0 s -5.114 2.289 -5.114 5.114 c 0 1.118 0.369 2.143 0.977 2.985 l -2.25 2.766 c -0.218 0.268 -0.337 0.602 -0.337 0.947 v 12.814 c -0.858 -0.11 -1.725 -0.185 -2.613 -0.185 c -1.181 0 -2.331 0.122 -3.457 0.314 V 14.342 c 0 -0.345 -0.119 -0.679 -0.336 -0.946 l -2.25 -2.766 c 0.608 -0.841 0.977 -1.867 0.977 -2.984 c 0 -2.824 -2.289 -5.114 -5.114 -5.114 s -5.114 2.289 -5.114 5.114 s 2.289 5.114 5.114 5.114 c 0.614 0 1.194 -0.126 1.739 -0.324 l 1.984 2.44 v 10.621 c -2.717 0.904 -5.172 2.366 -7.248 4.245 l -3.007 -3.546 c -0.018 -0.021 -0.04 -0.037 -0.059 -0.057 c 0.712 -0.879 1.157 -1.983 1.157 -3.203 c 0 -2.824 -2.289 -5.114 -5.114 -5.114 s -5.114 2.289 -5.114 5.114 c 0 2.824 2.289 5.114 5.114 5.114 c 0.532 0 1.035 -0.104 1.517 -0.255 c 0.056 0.12 0.122 0.236 0.211 0.342 l 3.197 3.77 c 0.001 0.001 0.001 0.001 0.002 0.002 c -1.78 2.164 -3.106 4.706 -3.881 7.48 H 12.344 l -2.44 -1.985 c 0.198 -0.545 0.324 -1.125 0.324 -1.739 c 0 -2.824 -2.289 -5.114 -5.114 -5.114 S 0 32.839 0 35.663 s 2.289 5.114 5.114 5.114 c 1.117 0 2.143 -0.368 2.984 -0.977 l 2.766 2.25 c 0.268 0.218 0.602 0.336 0.947 0.336 h 12.904 c -0.101 0.823 -0.169 1.657 -0.169 2.507 c 0 1.218 0.126 2.404 0.33 3.563 H 14.342 c -0.345 0 -0.679 0.119 -0.946 0.336 l -2.766 2.249 c -0.841 -0.608 -1.867 -0.976 -2.984 -0.976 c -2.824 0 -5.114 2.289 -5.114 5.114 c 0 2.824 2.289 5.114 5.114 5.114 c 2.824 0 5.114 -2.289 5.114 -5.114 c 0 -0.613 -0.126 -1.194 -0.324 -1.739 l 2.44 -1.984 h 10.766 c 0.818 2.412 2.06 4.626 3.658 6.539 c -0.034 0.033 -0.071 0.062 -0.102 0.099 L 26 61.864 c -0.089 0.106 -0.156 0.222 -0.211 0.341 c -0.482 -0.151 -0.985 -0.255 -1.517 -0.255 c -2.824 0 -5.114 2.289 -5.114 5.114 s 2.289 5.114 5.114 5.114 s 5.114 -2.289 5.114 -5.114 c 0 -1.22 -0.445 -2.325 -1.158 -3.204 c 0.019 -0.02 0.042 -0.035 0.06 -0.057 l 3.108 -3.665 c 2.269 2.026 4.985 3.554 7.991 4.411 v 13.106 l -1.984 2.44 c -0.545 -0.198 -1.125 -0.324 -1.739 -0.324 c -2.824 0 -5.114 2.289 -5.114 5.114 S 32.839 90 35.664 90 s 5.114 -2.289 5.114 -5.114 c 0 -1.117 -0.368 -2.143 -0.977 -2.984 l 2.25 -2.766 c 0.218 -0.268 0.336 -0.602 0.336 -0.946 V 65.164 c 0.858 0.11 1.726 0.185 2.613 0.185 c 1.181 0 2.331 -0.122 3.457 -0.314 v 10.623 c 0 0.345 0.119 0.679 0.337 0.946 l 2.25 2.766 c -0.608 0.841 -0.977 1.867 -0.977 2.985 c 0 2.824 2.289 5.114 5.114 5.114 s 5.114 -2.289 5.114 -5.114 s -2.289 -5.114 -5.114 -5.114 c -0.614 0 -1.194 0.126 -1.739 0.324 l -1.984 -2.44 V 64.292 c 2.673 -0.89 5.093 -2.318 7.148 -4.153 l 3.107 3.665 c 0.018 0.022 0.041 0.037 0.061 0.057 c -0.713 0.879 -1.157 1.984 -1.157 3.203 c 0 2.824 2.289 5.114 5.114 5.114 s 5.114 -2.289 5.114 -5.114 s -2.289 -5.114 -5.114 -5.114 c -0.532 0 -1.035 0.104 -1.517 0.255 c -0.055 -0.12 -0.121 -0.236 -0.211 -0.341 l -3.196 -3.77 c -0.031 -0.037 -0.069 -0.066 -0.103 -0.099 c 1.784 -2.135 3.13 -4.641 3.927 -7.383 h 13.027 l 2.44 1.986 c -0.198 0.545 -0.324 1.125 -0.324 1.738 c 0 2.824 2.289 5.114 5.114 5.114 c 2.824 0 5.114 -2.289 5.114 -5.114 S 87.711 49.223 84.886 49.223 z M 82.355 32.706 c 1.165 0 2.114 0.948 2.114 2.114 c 0 1.166 -0.948 2.114 -2.114 2.114 c -1.166 0 -2.114 -0.948 -2.114 -2.114 C 80.241 33.654 81.189 32.706 82.355 32.706 z M 65.729 20.822 c 1.166 0 2.114 0.948 2.114 2.114 c 0 1.165 -0.948 2.114 -2.114 2.114 c -1.165 0 -2.114 -0.948 -2.114 -2.114 C 63.615 21.77 64.563 20.822 65.729 20.822 z M 54.337 3 c 1.165 0 2.114 0.948 2.114 2.114 s -0.948 2.114 -2.114 2.114 c -1.166 0 -2.114 -0.948 -2.114 -2.114 S 53.171 3 54.337 3 z M 32.706 7.645 c 0 -1.166 0.948 -2.114 2.114 -2.114 s 2.114 0.948 2.114 2.114 c 0 1.165 -0.948 2.114 -2.114 2.114 S 32.706 8.811 32.706 7.645 z M 22.158 22.935 c 0 -1.166 0.948 -2.114 2.114 -2.114 s 2.114 0.948 2.114 2.114 c 0 1.165 -0.948 2.114 -2.114 2.114 S 22.158 24.101 22.158 22.935 z M 5.114 37.777 C 3.948 37.777 3 36.829 3 35.663 s 0.948 -2.114 2.114 -2.114 c 1.165 0 2.114 0.948 2.114 2.114 S 6.279 37.777 5.114 37.777 z M 7.645 57.294 c -1.166 0 -2.114 -0.948 -2.114 -2.114 c 0 -1.166 0.948 -2.114 2.114 -2.114 c 1.165 0 2.114 0.948 2.114 2.114 C 9.759 56.346 8.811 57.294 7.645 57.294 z M 24.271 69.178 c -1.165 0 -2.114 -0.948 -2.114 -2.114 c 0 -1.165 0.948 -2.114 2.114 -2.114 s 2.114 0.948 2.114 2.114 C 26.385 68.23 25.437 69.178 24.271 69.178 z M 35.664 87 c -1.166 0 -2.114 -0.948 -2.114 -2.114 c 0 -1.166 0.948 -2.114 2.114 -2.114 c 1.165 0 2.114 0.948 2.114 2.114 C 37.777 86.052 36.829 87 35.664 87 z M 57.294 82.355 c 0 1.165 -0.948 2.114 -2.114 2.114 c -1.165 0 -2.114 -0.948 -2.114 -2.114 s 0.948 -2.114 2.114 -2.114 C 56.346 80.241 57.294 81.189 57.294 82.355 z M 67.842 67.065 c 0 1.166 -0.948 2.114 -2.114 2.114 c -1.165 0 -2.114 -0.948 -2.114 -2.114 c 0 -1.165 0.948 -2.114 2.114 -2.114 C 66.894 64.951 67.842 65.899 67.842 67.065 z M 45 62.349 c -9.625 0 -17.455 -7.83 -17.455 -17.455 S 35.375 27.439 45 27.439 s 17.455 7.83 17.455 17.455 S 54.625 62.349 45 62.349 z M 84.886 56.45 c -1.166 0 -2.114 -0.948 -2.114 -2.114 s 0.948 -2.114 2.114 -2.114 c 1.165 0 2.114 0.948 2.114 2.114 S 86.052 56.45 84.886 56.45 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(69,86,60); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        </g>
                    </svg>
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="p-6">
                            <div class="flex items-center">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <div class="ml-4 text-lg leading-7 font-semibold">Crypto Portfolio Tracker v0.1</div>
                            </div>

                            <div class="ml-12">
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                    A handy tool to help you track your crypto losses. Buy and sell assets and see your average price, keep buying those dips :)
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                            <div class="flex items-center">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <div class="ml-4 text-lg leading-7 font-semibold">Start here</div>
                            </div>

                            <div class="ml-12">
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                    @auth
                                        Open your portfolio <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">here</a>.
                                    @else
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a> and 
                                         @endif                                    
                                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a> to start tracking your coins.
                                    @endauth                                    
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                <div class="ml-4 text-lg leading-7 font-semibold">Warning</div>
                            </div>

                            <div class="ml-12">
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                    Buying crypto can be a dangerous hobbey, please be careful.
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
                            <div class="flex items-center">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="ml-4 text-lg leading-7 font-semibold text-gray-900 dark:text-white">More features coming....</div>
                            </div>

                            <div class="ml-12">
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                    Stay tuned for more updates, features and goodies to keep you happy while the bears eat away at your portfolio.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 sm:text-left">
                        <div class="flex items-center">
                            {{-- <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="-mt-px w-5 h-5 text-gray-400">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>

                            <a href="https://laravel.bigcartel.com" class="ml-1 underline">
                                Shop
                            </a> --}}

                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="ml-4 -mt-px w-5 h-5 text-gray-400">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>

                            <a href="http://mjp.co" class="ml-1 underline">
                                By Matt
                            </a>
                        </div>
                    </div>

                    <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
