<div class="h-full relative select-none w-full flex justify-center items-center">
    <div class="flex flex-col justify-start w-full h-full"
         x-data="{
             windDirection: 0,
             windSpeed: 0,
             updated: 'loading...',
             title: 'loading...',
             init() {
                 Livewire.on('update{{ $id }}', (data) => {
                     this.windDirection = data.latestInfo['direction'];
                     this.title = data.latestInfo['title'] ?? 'Untitled';
                     this.windSpeed = data.latestInfo['speed'];
                     this.updated = data.latestInfo['updated'];
                     var arrow = document.getElementById('arrowSVG');
                     arrow.style = 'transform: rotate(' + (data.latestInfo['direction'] + 180) + 'deg)';
                 })
             }
         }">
        <div class="text-black">
            <div class="text-xl font-bold"
                 x-text="title">
                Wind Speed and Direction
            </div>
            <div class="text-sm">
                <div class="flex justify-center flex-wrap">
                    <div>
                        {{ __('Updated:') }}
                    </div>
                    <div>
                        <span x-text="updated"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-full flex justify-center relative items-center overflow-hidden"
             x-show="windSpeed"
             x-cloak>
            {{-- fix this and at the same time create custom gause as the library one is not responsive --}}
            <x-voidoflimbo.svg.compass.tick NP>
                <circle r="60"
                        cx="165"
                        cy="165"
                        fill="#00f" />
                <g fill="#000"
                   font-family="Arial-BoldMT, Arial"
                   font-size="36"
                   font-weight="bold">
                    <text transform="translate(60 60)">
                        <tspan x="92.501"
                               y="33">N</tspan>
                    </text>
                    <text transform="translate(60 60)">
                        <tspan x="178.494"
                               y="118">E</tspan>
                    </text>
                    <text transform="translate(60 60)">
                        <tspan x="93.494"
                               y="203">S</tspan>
                    </text>
                    <text transform="translate(60 60)">
                        <tspan x="3.511"
                               y="118">W</tspan>
                    </text>
                </g>
            </x-voidoflimbo.svg.compass.tick>
            <x-voidoflimbo.svg.compass.arrow id="arrowSVG"></x-voidoflimbo.svg.compass.arrow>

            <div class="absolute w-full h-full flex flex-col justify-center items-center text-center">
                <span class="text-lg font-bold"
                      x-text="windSpeed"></span>
                <span class="text-sm">{{ $unit ?? 'MPS' }}</span>
            </div>
        </div>
        <div class="h-full flex justify-center relative items-center text-black text-3xl overflow-hidden"
             x-show="!windSpeed">
            <div>
                {{ 'Requesting Latest Data' }}
            </div>
        </div>
    </div>
</div>
