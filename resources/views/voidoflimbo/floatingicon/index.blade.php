<div {{ $attributes->merge(['class' => 'invisible absolute select-none opacity-20 active:scale-90 hover:opacity-100']) }}
     :style="`position: absolute; visibility: visible; left: ${iconposition.x}px; top: ${iconposition.y}px; transform: ${isMoving ? 'scale(0)' : 'scale(1)'};`"
     x-ref="dagon"
     x-data="{
         iconposition: $persist($refs.dagon ? { x: $refs.dagon.x, y: $refs.dagon.y } : { x: 0, y: 0 }),
         isMoving: false,
         w: $refs.dagon.getBoundingClientRect().width,
         h: $refs.dagon.getBoundingClientRect().height
     }"
     x-on:dragend="isMoving = true;  
        setTimeout(() => { 
        iconposition.x = Math.min(Math.max($event.clientX, 16), 16); 
        iconposition.y = Math.min(Math.max($event.clientY, 16), window.innerHeight - h - 16); 
        isMoving = false; 
     }, 150)"
     x-on:click="if($event.ctrlKey) { 
        w = $refs.dagon.getBoundingClientRect().width; 
        h = $refs.dagon.getBoundingClientRect().height; 
        isMoving = true; 
        setTimeout(() => { 
            iconposition.x = 16; 
            iconposition.y = window.innerHeight - h - 16; 
            isMoving = false; 
            }, 300); 
        }"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform scale-0"
     x-transition:enter-end="transform scale-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="transform scale-100"
     x-transition:leave-end="transform scale-0"
     draggable="true">
    <div id="content">
        {{ $slot }}
    </div>
    <div id="options">

    </div>
</div>
