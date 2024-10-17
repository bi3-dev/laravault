<div class="h-full"
     wire:ignore
     x-data="{
         init() {
             const config = {
                 type: 'line',
                 options: {
                     plugins: {
                         tooltip: {
                             intersect: false,
                         },
                         title: {
                             display: true,
                             text: '',
                             font: {
                                 size: 22
                             }
                         },
                         filler: {
                             propagate: true
                         },
                         zoom: {
                             pan: {
                                 enabled: true,
                                 mode: 'x',
                             },
                             zoom: {
                                 wheel: {
                                     enabled: true,
                                     modifierKey: 'ctrl',
                                 },
                                 mode: 'x',
                             },
                             limits: {
                                 x: { min: 'original', max: 'original', minRange: 5 },
                             },
                         },
                     },
                     scales: {
                         x: {
                             stacked: true,
                             ticks: {
                                 autoSkip: true,
                                 maxTicksLimit: 5,
                             }
                         },
                     },
                     elements: {
                         point: {
                             pointStyle: false,
                         },
                         bar: {
                             borderWidth: 2,
                         }
                     }
                 }
             }
     
             const {{ $id }} = new Chart(
                 this.$refs.{{ $id }},
                 config
             );
     
             Livewire.on('update{{ $id }}', (data) => {
                 var reset = true;
                 {{-- console.log(data.latestInfo); --}}
                 {{ $id }}.data.labels = data.latestInfo['labels'];
                 {{ $id }}.options.plugins.title.text = data.latestInfo['title'];
     
                 let previousDataset = {{ $id }}.data.datasets.map(dataset => dataset.data);
     
                 var newDataset = Object.keys(data.latestInfo.dataset).map(key => ({
                     label: key,
                     data: data.latestInfo.dataset[key],
                     fill: 'origin',
                 }));
     
                 {{-- console.log(data.latestInfo); --}}
     
                 if (newDataset.map(dataset => dataset.data).some((datao, index) => {
                         return JSON.stringify(datao) !== JSON.stringify(previousDataset[index])
                     })) {
                     if (data.latestInfo.ylabel) {
                         if (data.latestInfo.type == 'direction') {
     
                             {{ $id }}.options.scales.y = {
                                 min: 0,
                                 max: 360,
                                 ticks: {
                                     stepSize: 90,
                                     callback: (value) => {
                                         return value + '°';
                                     },
                                 },
                                 title: {
                                     display: true,
                                     text: data.latestInfo.ylabel,
                                 },
                                 stacked: true,
                             };
     
                             {{ $id }}.options.scales.y1 = {
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
                                 title: {
                                     display: true,
                                     text: 'Direction',
                                 },
                                 stacked: true,
                             };
                         } else {
                             {{ $id }}.options.scales.y = {
     
                                 title: {
                                     display: true,
                                     text: data.latestInfo.ylabel,
                                 },
                                 stacked: true,
                             }
                         }
                     }
                     {{ $id }}.data.datasets = newDataset;
                 }
     
                 {{-- {{ $id }}.options.scales.y = {}; --}}
                 {{ $id }}.update();
             })
     
         }
     }">
    <div class="w-full h-full p-2">
        <canvas x-ref="{{ $id }}"> </canvas>
    </div>
</div>
