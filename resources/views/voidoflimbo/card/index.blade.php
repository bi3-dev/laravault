<div id={{ $id }}
     {{ $attributes->merge(['class' => 'select-none relative cursor-pointer bg-slate-700 p-1 rounded-xl']) }}
     x-data="{ hoverdata: false }"
     x-on:mousedown.outside="hoverdata = false">
    <div class="relative min-h-11"
         x-on:mousedown="hoverdata = !hoverdata"
         $id={{ $id }}>
        @isset($image)
            {{ $image }}
        @endisset

        <div class="absolute bottom-0 left-0 right-0 p-2 text-lg text-center bg-slate-900/60">
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>

    <div class="absolute bg-transparent"
         style="z-index: 9999;"
         x-show="hoverdata"
         x-cloak
         x-anchor.bottom="document.getElementById('{{ $id }}')">
        <div class="!border-b-white"
             style="width: 0; height: 0; margin:auto;
             border-left: 7px solid transparent;
             border-right: 7px solid transparent;
             border-bottom: 15px solid;">
        </div>
        <div class="p-2 border rounded-lg bg-slate-800">
            @isset($actions)
                {{ $actions }}
            @endisset
        </div>
    </div>
</div>
