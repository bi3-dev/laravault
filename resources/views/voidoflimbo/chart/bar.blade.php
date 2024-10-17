<div class="h-full"
     wire:ignore
     x-data="{
         init() {
             const config = {
                 type: 'bar',
                 data: {
                     labels: [],
                     datasets: [{}],
                 },
                 options: {
                     plugins: {
                         tooltip: {
                             intersect: false,
                         },
                     },
                     maintainAspectRatio: false,
                     scales: {
                         x: {
                             ticks: {
                                 maxRotation: 0,
                                 callback: function(val, index) {
                                     return index % 3 === 0 ? this.getLabelForValue(val) : '';
                                 },
                             }
                         },
                         {{-- y: {
                             min: 0,
                             max: 360,
                             ticks: {
                                 stepSize: 60,
                             }
                         } --}}
                     }
                 }
             }
     
             const {{ $id }} = new Chart(
                 this.$refs.{{ $id }},
                 config
             );
     
             Livewire.on('update{{ $id }}', (data) => {
                 {{ $id }}.data.datasets[0].label = data.latestInfo['title'];
                 {{ $id }}.data.labels = data.latestInfo['labels'];
                 {{ $id }}.data.datasets[0].data = data.latestInfo['dataset'];
                 {{ $id }}.update();
             })
     
         }
     }">
    <div class="w-full h-full p-2">
        <canvas x-ref="{{ $id }}"> </canvas>
    </div>
</div>
