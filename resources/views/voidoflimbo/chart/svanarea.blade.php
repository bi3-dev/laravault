<div class="h-full"
     wire:ignore
     x-data="{
         init() {
             const config = {
                 type: 'bar',
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
     
     
     
                 if (newDataset.map(dataset => dataset.data).some((datao, index) => {
                         return JSON.stringify(datao) !== JSON.stringify(previousDataset[index])
                     })) {
                     if (data.latestInfo.ylabel) {
                         {{ $id }}.options.scales.y = {
                             title: {
                                 display: true,
                                 text: data.latestInfo.ylabel,
                             },
                             stacked: true,
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
