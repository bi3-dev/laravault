<div class="h-full text-black w-full flex flex-col"
     x-data="{
         minTemp: 0,
         maxTemp: 2,
         step: 0.5,
         unit: 'mm',
         currentTemp: '...',
         title: 'Loading...',
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
             for (i = this.maxTemp; i >= this.minTemp; i -= this.step) {
                 var scale = this.create('<div class=\'flex justify-start\' > — ' + i + ' </div>');
                 $refs.scales.appendChild(scale);
             }
         },
         init() {
             Livewire.on('update{{ $id }}', (data) => {
                 this.currentTemp = data.latestInfo['pt'];
                 this.title = data.latestInfo['title'];
                 const newheight = (100 - ((this.currentTemp - this.minTemp) / (this.maxTemp - this.minTemp)) * 100) + '%';
                 $refs.precipitation.style.height = newheight;
             })
         }
     
     }"
     x-init="createScales()">
    <div>
        <div class=" text-xl font-bold"
             x-text="title"></div>
        <div class="">

        </div>
    </div>
    <div class="flex py-4 w-full gap-1 justify-start h-full align-middle"
         id="wrapper">
        <div class="w-[50%] h-[100%] flex justify-center relative rounded-b-3xl bg-blue-600 border-slate-950 border-[10px]">
            <div class="relative bg-black bottom-0 w-full h-full transition-all"
                 x-ref="precipitation">
                <div class="absolute text-sm -right-8 -bottom-5 border-pink-600 border-2 py-1 px-2 bg-slate-300 rounded-xl"
                     x-text="currentTemp + ' ' + unit"></div>
            </div>
        </div>
        <div class="flex-col py-2 flex justify-between"
             x-ref="scales">
        </div>
    </div>

</div>
