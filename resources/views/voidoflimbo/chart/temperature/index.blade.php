{{-- need to claculate and add a lot of parameters, potentially define some as chart property such as chill, cold, warm, hot --}}
<div class="flex flex-col w-full h-full text-black"
     {{ $attributes }}
     x-data="{
         minTemp: -20,
         maxTemp: 50,
         step: 10,
         unit: '°C',
         currentTemp: '...',
         title: 'Loading...',
         updated: 'Loading...',
         create(htmlStr) {
             const frag = document.createDocumentFragment();
             const temp = document.createElement('div');
             temp.innerHTML = htmlStr;
             while (temp.firstChild) {
                 frag.appendChild(temp.firstChild);
             }
             return frag;
         },
         createScales() {
             x = document.getElementById('{{ $id }}');
             {{-- console.log(x.clientHeight); --}}
             for (i = this.maxTemp; i >= this.minTemp; i -= this.step) {
                 var scale = this.create('<div class=\'flex justify-end\' > ' + i + ' </div>');
                 $refs.scales.appendChild(scale);
             }
         },
         init() {
     
             // Event Listener
             Livewire.on('update{{ $id }}', (data) => {
                 this.currentTemp = data.latestInfo['temp'];
                 this.title = data.latestInfo['title'];
                 this.updated = data.latestInfo['updated'];
                 const newheight = (100 - ((this.currentTemp - this.minTemp) / (this.maxTemp - this.minTemp)) * 100) + '%';
                 $refs.temperature.style.height = newheight;
             })
     
             {{-- const newheight = (100 - ((this.currentTemp - this.minTemp) / (this.maxTemp - this.minTemp)) * 100) + '%';
             $refs.temperature.style.height = newheight; --}}
         }
     
     }"
     x-init="createScales()">
    <div>
        <div class="text-xl font-bold "
             x-text="title"></div>
        <div class="text-sm">
            <div class="flex flex-wrap justify-center">
                <div>
                    {{ __('Updated:') }}
                </div>
                <div>
                    <span x-text="updated"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-start w-full h-full gap-1 py-4 align-middle overflow-clip"
         x-show="currentTemp != '...'">

        <div class="flex flex-col-reverse items-center justify-center w-full align-middle">
            <div class="w-[20px] rounded-full border-black -translate-y-[40%] border-4 h-[40px] bg-[#fff]">
            </div>
            <div class="w-[30px] h-[90%] flex justify-center relative bg-slate-950 rounded-3xl border-slate-800 border-8"
                 :class="{
                     'bg-gradient-to-t from-sky-500 to-yellow-800': currentTemp < 0,
                     'bg-gradient-to-t from-yellow-500 to-orange-800': currentTemp > 0 && currentTemp <= 20,
                     'bg-gradient-to-t from-orange-500 to-red-800': currentTemp > 0
                 }">
                <div class="relative w-full h-full transition-all bg-black rounded-t-2xl"
                     x-ref="temperature">
                    <div class="absolute text-sm left-[5%] -bottom-5 border-black border-2 py-1 px-2 bg-slate-300/70 rounded-3xl"
                         x-text="currentTemp + '' + unit"></div>
                </div>
                <div class="absolute flex flex-col justify-between h-full -translate-x-8"
                     x-ref="scales"></div>
            </div>

        </div>
    </div>
    <div class="flex items-center justify-center w-full h-full gap-1 py-4 text-2xl text-center align-middle"
         x-show="currentTemp == '...'">
        <div>
            {{ 'Requesting Latest Data' }}
        </div>
    </div>
</div>
