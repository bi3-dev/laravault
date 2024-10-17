<!-- Separate time display component -->
<div class="flex h-full w-[8vw] items-center justify-center hover:bg-slate-800"
     x-data="{ displayTime: new Date().toLocaleTimeString() }"
     x-init="setInterval(() => { displayTime = new Date().toLocaleTimeString() }, 1000)"
     x-text="displayTime"></div>
