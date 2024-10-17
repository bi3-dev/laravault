{{-- Demendencies, $isPaused, $name --}}

<div class="relative inline-flex h-full w-[3vw] cursor-pointer items-center justify-center gap-2 px-2 font-bold"
     x-data="{
         timer: null,
         animation: null,
         countdown: 60,
         currentV: 0,
         isPaused: @entangle('isPaused'),
         resetTimer() {
             this.isPaused = !this.isPaused;
         },
         startCountdown() {
             this.animation = setInterval(() => {
                 if (!this.isPaused && this.countdown > 0) {
                     this.countdown--;
                     this.currentV = (this.currentV > 360) ? 0 : this.currentV + 6;
                 } else if (this.countdown <= 0) {
                     @this.call('{{ $name ?? 'refetchdata' }}');
                     this.countdown = 60;
                     this.currentV = 0;
                 }
             }, 1000);
         }
     }"
     x-init="startCountdown()">
    <div class="flex items-center justify-center">
        <div class="relative flex flex-col items-center justify-center overflow-hidden rounded-full bg-white">
            <div class="relative flex items-center justify-center rounded-full"
                 x-on:click="resetTimer()"
                 :style="`height: 40px; width: 40px; background-image: conic-gradient(#3498db ${currentV}deg, #000 ${currentV}deg 360deg);`">
                <template x-if="!isPaused">
                    <span class="flex h-4/5 w-4/5 items-center justify-center rounded-full bg-black"
                          x-text="countdown"></span>
                </template>
                <template x-if="isPaused">
                    <span class="flex h-4/5 w-4/5 fill-amber-500 items-center justify-center rounded-full bg-black">
                        <x-voidoflimbo.svg icon="pause.filled"></x-voidoflimbo.svg>
                    </span>
                </template>
            </div>
        </div>
    </div>
</div>
