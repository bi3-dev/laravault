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
                                 {{-- drag: {
                                     enabled: true,
                                 }, --}}
                                 mode: 'x',
                             },
                             limits: {
                                 x: { min: 'original', max: 'original', minRange: 10 },
                                 {{-- y: { min: 'original', max: 'original', minRange: 1 } --}}
                             },
                         },
                         {{-- dragData: {
                             round: 1,
                             onDragStart: function(e, datasetIndex, index, value) {
                                 console.log(e, datasetIndex, index, value);
                             },
                             onDrag: function(e, datasetIndex, index, value) {
                                 console.log(e, datasetIndex, index, value);
                             },
                             onDragEnd: function(e, datasetIndex, index, value) {
                                 console.log(e, datasetIndex, index, value);
                             }
                         },
      --}}
                     },
                     scales: {
                         x: {
                             ticks: {
                                 autoSkip: true,
                                 maxTicksLimit: 5,
                             }
                         },
                     },
                     elements: {
                         point: {
                             pointStyle: false,
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
     
                 {{-- {{ $id }}.data.datasets[0].label = 'x'; --}}
                 var newDataset = [{
                     label: 'x',
                     data: data.latestInfo.dataset.x,
                 }, {
                     label: 'y',
                     data: data.latestInfo.dataset.y,
                 }, {
                     label: 'z',
                     data: data.latestInfo.dataset.z,
                 }]
     
     
     
                 if (newDataset.map(dataset => dataset.data).some((data, index) => {
                         return JSON.stringify(data) !== JSON.stringify(previousDataset[index])
                     })) {
                     if (data.latestInfo.ylabel) {
                         {{ $id }}.options.scales.y = {
                             title: {
                                 display: true,
                                 text: data.latestInfo.ylabel,
                             }
                         }
                     }
                     {{ $id }}.data.datasets = newDataset;
                 }
     
                 {{ $id }}.update();
             })
     
         }
     }">
    <div class="w-full h-full p-2">
        <canvas x-ref="{{ $id }}"> </canvas>
    </div>
</div>
