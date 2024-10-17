<template id="detection-template">
    <div class="w-full container justify-between border-2 border-slate-950 flex text-black rounded-lg hover:scale-105">
        <div class="flex-grow icontext text-xs flex flex-col justify-evenly px-2">
        </div>
        <img class="rounded-r-lg"
             src="https://picsum.photos/seed/image/300/200/"
             alt="preview_image">
    </div>
</template>

<template id="cow">
    <x-voidoflimbo.svg class="bg-green-700 p-2 rounded-l-lg svgicon"
                       icon="cow"></x-voidoflimbo.svg>
</template>

<template id="person">
    <x-voidoflimbo.svg class="bg-blue-700 p-2 rounded-l-lg svgicon"
                       icon="user"></x-voidoflimbo.svg>
</template>

<template id="vehicle">
    <x-voidoflimbo.svg class="bg-red-500 p-2 rounded-l-lg svgicon"
                       icon="vehicle"></x-voidoflimbo.svg>
</template>

<div {{ $attributes->merge(['class' => 'overflow-y-auto select-none overflow-x-clip']) }}
     x-data="{
         detections: [],
         current: {},
         createList() {
             {{-- console.log(this.detections); --}}
             const container = this.$el;
             container.innerHTML = '';
             const template = document.getElementById('detection-template').content;
             this.detections.forEach((detection) => {
                 var select = false;
                 if (this.current && detection.id == this.current.id) {
                     select = true;
                 }
                 height = {{ $height ?? 16 }};
                 const item = document.createElement('div');
                 item.className = 'px-2 py-1.5 cursor-pointer flex flex-col';
                 item.setAttribute('wire:click', '$dispatch(\'changecurrent\', {id: ' + detection.id + ' })');
     
                 const clone = document.importNode(template, true);
                 const listItem = clone.querySelector('.container');
                 const text = clone.querySelector('.icontext');
                 const image = clone.querySelector('img');
     
                 var iconType = document.getElementById(detection.video_path.toLowerCase()).content;
                 var svgIcon = (document.importNode(iconType, true)).querySelector('.svgicon');
                 svgIcon.classList.add('h-16', 'w-16', 'fill-white');
                 listItem.insertBefore(svgIcon, listItem.children[0]);
     
                 if (select) {
                     bg = 'bg-lime-300';
                 } else {
                     bg = 'bg-zinc-300';
                 }
                 const header = document.createElement('div');
                 const footer = document.createElement('div');
                 header.innerHTML = detection.video_path + ' Detected';
                 header.classList.add('font-bold', 'text-lg');
                 footer.innerHTML = 'On: ' + new Date(detection.event_timestamp).toLocaleString();
                 text.classList.add(bg);
                 text.appendChild(header);
                 text.appendChild(footer);
     
                 image.setAttribute('src', detection.image_path);
                 image.classList.add('h-16');
     
                 item.appendChild(clone);
                 container.appendChild(item);
             });
         },
         init() {
             Livewire.on('updateList', (data) => {
                 this.detections = data.data.detections.data;
                 this.current = data.data.current;
                 if (this.detections.length > 0) {
                     this.createList();
                 }
             });
         }
     }">
    <div class="text-2xl bold h-full flex items-center align-middle justify-center"
         x-show="detections.length == 0">
        <div>
            {{ __('Requesting Live Data') }}
        </div>
    </div>
</div>
