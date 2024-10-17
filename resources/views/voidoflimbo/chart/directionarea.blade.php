<div class="h-full"
     wire:ignore
     x-data="{
         init() {
             const config = {
                 type: 'line',
                 data: {
                     labels: [],
                     datasets: [{
                         fill: 'origin',
                     }],
                 },
                 options: {
                     plugins: {
                         tooltip: {
                             intersect: false,
                         },
                     },
                     scales: {
                         x: {
                             ticks: {
                                 maxRotation: 0,
                                 callback: function(val, index) {
                                     return index % 3 === 0 ? this.getLabelForValue(val) : '';
                                 },
                             }
                         },
                         y: {
                             min: 0,
                             max: 360,
                             ticks: {
                                 stepSize: 90,
                                 callback: (value) => {
                                     return value + '°';
                                 },
                             }
                         },
                         y1: {
                             type: 'linear',
                             display: true,
                             position: 'right',
                             min: 0, // Set minimum value for the right y-axis
                             max: 4, // Set maximum value for the right y-axis
                             ticks: {
                                 callback: (value) => {
                                     if (value === 0 || value === 4) return 'N';
                                     if (value === 1) return 'E';
                                     if (value === 2) return 'S';
                                     if (value === 3) return 'W';
                                     return ''; // Hide other ticks
                                 },
                             },
                         },
                     }
                 }
             }
     
             const {{ $id }} = new Chart(
                 this.$refs.{{ $id }},
                 config
             );
     
             Livewire.on('update{{ $id }}', (data) => {
                 {{-- console.log(data.latestInfo); --}}
                 {{ $id }}.data.datasets[0].label = data.latestInfo['title'];
                 {{ $id }}.data.labels = data.latestInfo['labels'];
                 {{ $id }}.data.datasets[0].data = data.latestInfo['dataset'];
                 {{ $id }}.data.datasets[0].fill = data.latestInfo['fill'];
                 {{ $id }}.update();
             })
     
         }
     }">
    <div class="w-full h-full p-2">
        <canvas x-ref="{{ $id }}"> </canvas>
    </div>
</div>
