<div class="w-full h-full flex justify-center">
    <div class="click-ticker bg-white relative overflow-hidden w-4/5 h-3/5 text-black"
         data-ticks="100"
         style="box-shadow: inset 0 0 8px 0px #000;">
        <div class="absolute grid grid-rows-3 inset-0">
            <div class="bg-gradient-to-b from-slate-500 from-10%">
            </div>
            <div>
            </div>
            <div class="bg-gradient-to-t from-slate-500 from-10% ">
            </div>
        </div>
        <ul class="click-ticker-wrapper"
            id="counter"
            x-ref="counter">
            {{-- <li class="ticks text-center">
                <span class="number">0</span>
            </li>
            <li class="ticks text-center">
                <span class="number">-1</span>
            </li> --}}
        </ul>
    </div>
    <style>
        .click-ticker:after {
            content: "\A";
            display: block;
            position: absolute;
            top: 45%;
            left: 0;
            border: 10px solid #F86060;
            z-index: 20;
            border-bottom-color: transparent;
            border-top-color: transparent;
            border-right-color: transparent;
            border-left-width: 20px;
        }

        .click-ticker .ticks {
            width: 50%;
            /* background: #A5A5A5; */
            /* border-left: 5px solid #71ADC9; */
            /* line-height: 86.5px; */
            display: block;
            color: black;
            /* font-size: 4rem; */
            margin: auto;
        }

        .click-ticker .tick {
            float: left;
            margin-left: -3px;
            color: #71ADC9;
        }

        /* .click-ticker .click-ticker-wrapper {
            /* bottom: 552.375px;
            position: relative;
            opacity: 0;
        } */
        .click-ticker .click-ticker-wrapper.animate {
            -moz-transition: all 0.6s ease-in-out;
            -webkit-transition: all 0.6s ease-in-out;
            -ms-transition: all 0.6s ease-in-out;
            transition: all 0.6s ease-in-out;
            opacity: 1;
        }
    </style>
    <script type="module">
        document.querySelectorAll(".click-ticker").forEach(function(element) {
            var steps = 10;
            var ticks = element.dataset.ticks;
            var viewHeight = element.scrollHeight;
            var textHeight = viewHeight / 3;
            console.log(viewHeight);
            // var tickHeight = element.querySelector(".ticks").offsetHeight;
            // var tickWrapper = element.querySelector(".click-ticker-wrapper");

            function create(htmlStr) {
                const frag = document.createDocumentFragment();
                const temp = document.createElement('div');
                temp.innerHTML = htmlStr;
                while (temp.firstChild) {
                    frag.appendChild(temp.firstChild);
                }
                return frag;
            }

            function createCounters() {
                for (var i = 0; i <= ticks; i += steps) {
                    var li = document.createElement('li');
                    li.style.height = viewHeight + "px";
                    li.setAttribute('class', 'ticks text-center');

                    var span = document.createElement('span');
                    span.setAttribute('class', 'number');
                    span.setAttribute('style', 'fost-size:' + viewHeight + 'px');
                    span.innerHTML = i;

                    li.appendChild(span);

                    var scale = create("<li id='" + i + "' class='ticks text-center'> <span class='number text-black'>" + i + "</span> </li>");
                    document.getElementById('counter').appendChild(li);
                }
            }

            createCounters();


            // if (ticks >= 0) {
            //     var tickElem = element.querySelector(".ticks").cloneNode(true);
            //     var difference = ticks;
            //     var extra = Math.ceil(difference / steps) + 1;

            //     for (var i = 1; i <= extra; i++) {
            //         var newElem = tickElem.cloneNode(true);
            //         newElem.querySelector(".number").textContent = i * steps;
            //         tickWrapper.insertBefore(newElem, tickWrapper.firstChild);
            //     }

            //     // Reset the starting point
            //     var newHeight = tickWrapper.offsetHeight;
            //     // var offset = newHeight - (viewHeight + (viewHeight / 4)) - 5;
            //     var offset = newHeight - tickHeight * 3;
            //     tickWrapper.style.bottom = offset + "px";

            //     console.log(newHeight, tickHeight, viewHeight, offset);
            // }

            // Add the "animate" class to let CSS handle the animation

            // Bounce effect
            // setTimeout(function() {
            //     var wrapperHeight = tickWrapper.offsetHeight;
            //     var currentOffset = parseFloat(getComputedStyle(tickWrapper).bottom);
            //     var delta = currentOffset - (ticks / steps) * tickHeight;

            //     tickWrapper.style.bottom = delta - 100 + "px";
            //     setTimeout(function() {
            //         tickWrapper.style.bottom = delta + 50 + "px";
            //     }, 600);
            //     setTimeout(function() {
            //         tickWrapper.style.bottom = delta - 20 + "px";
            //     }, 1200);
            //     setTimeout(function() {
            //         tickWrapper.style.bottom = delta + "px";
            //     }, 1800);
            // }, 1200);
        });
    </script>

</div>
