<div class="w-full h-full text-black"
     x-data="{
         count: 60,
         currentTime: new Date(),
         imagePath: '',
         eventTimestamp: '',
         detectionDates: [],
         color: {},
         formatTime(seconds) {
             this.currentTime = new Date();
             return (new Date(this.currentTime.setSeconds(this.currentTime.getSeconds() - seconds, 0)));
         },
         getFormattedTime(date) {
             const hours = date.getHours().toString().padStart(2, '0');
             const minutes = date.getMinutes().toString().padStart(2, '0');
             const seconds = date.getSeconds().toString().padStart(2, '0');
             return `${hours}:${minutes}:${seconds}`;
         },
         isDetection(date) {
             return this.detectionDates.find(d => new Date(d).getTime() / 1000 === parseInt(date.getTime() / 1000)) !== undefined;
         },
         selectColor(date) {
             if (this.color[date.getTime() / 1000] == 'cow') {
                 return 'blue';
             } else if (this.color[date.getTime() / 1000] == 'person') {
                 return 'orange';
             } else if (this.color[date.getTime() / 1000] == 'vehicle') {
                 return 'green';
             } else if (this.color[date.getTime() / 1000] == undefined) {
                 return 'transparent';
             } else {
                 return 'red';
             }
         },
         init() {
             Livewire.on('update{{ $id }}', (data) => {
                 this.currentTime = new Date();
                 this.imagePath = data.data['image_path'];
                 this.eventTimestamp = data.data['event_timestamp'];
                 this.detectionDates = data.data['detections'];
                 this.color = data.data['color'];
             })
         }
     }">
    <div x-show="imagePath != ''">
        <div class="relative w-full h-full flex flex-col align-middle justify-center items-center">
            <template x-if="imagePath">
                <img class="w-full rounded-t-lg"
                     alt=""
                     :src="imagePath">
            </template>
            <div class="bg-slate-800/60 p-2 absolute top-0 left-0 text-white right-0 text-center z-10 cursor-pointer">
                <span x-text="eventTimestamp"
                      x-on:click="$dispatch('unpause')"></span>
            </div>
            <div {{ $attributes->merge(['class' => 'relative overflow-x-clip rounded-b-lg']) }}>
                <div class="flex h-full items-end p-2">
                    <template x-for="i in Array.from({ length: count + 1 }, (_, index) => index)">
                        <div class="flex w-full relative flex-col h-full justify-start">
                            <div class="absolute top-0 -translate-x-8">
                                <div x-text="formatTime(i).getSeconds() % 30 == 0 ? getFormattedTime(formatTime(i)) : '' "></div>
                            </div>
                            <div class="flex left-0 right-0 absolute bottom-0"
                                 x-key="i">
                                <div class="bg-black min-w-1 "
                                     :class="formatTime(i).getSeconds() % 30 == 0 ? 'h-10' : formatTime(i).getSeconds() % 5 == 0 ? 'h-5' : 'h-1'">
                                </div>
                            </div>
                            <div class="flex left-0 right-0 absolute bottom-0"
                                 x-key="i + ':' + i">
                                <div class="min-w-1 rounded-full cursor-pointer z-10"
                                     x-on:click="$dispatch('pause', { date: formatTime(i) })"
                                     :class="{
                                         'h-14': isDetection(formatTime(i)),
                                         'h-0': !isDetection(formatTime(i)),
                                         'bg-red-600': selectColor(formatTime(i)) == 'red',
                                         'bg-green-600': selectColor(formatTime(i)) == 'green',
                                         'bg-orange-600': selectColor(formatTime(i)) == 'orange',
                                         'bg-blue-600': selectColor(formatTime(i)) == 'blue',
                                         'bg-transparent': selectColor(formatTime(i)) == 'transparent',
                                     }">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-center h-full align-middle text-center gap-2 text-4xl "
         x-show="imagePath == ''">
        <div class="h-max">
            {{ 'Requesting Latest Data' }}
        </div>
    </div>
</div>
