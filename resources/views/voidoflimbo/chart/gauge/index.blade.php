<div class="container select-none flex flex-col items-center text-black justify-start gap-2 h-full"
     x-data="{
         digits: 5,
         delay: 250,
         value: 0,
         decimals: 1,
         countTo: [],
         DOM: {},
         unit: '...',
         updated: 'loading...',
         title: '...',
         build(selector) {
             const scopeElm = typeof selector === 'string' ?
                 document.querySelector(selector) :
                 selector ? selector :
                 this.DOM.scope;
     
             this.DOM = {
                 scope: scopeElm,
                 digits: scopeElm.querySelectorAll('b')
             };
         },
         create(htmlStr) {
             const frag = document.createDocumentFragment();
             const temp = document.createElement('div');
             temp.innerHTML = htmlStr;
             while (temp.firstChild) {
                 frag.appendChild(temp.firstChild);
             }
             return frag;
         },
         count(newVal) {
             if (this.value == newVal) return;
             this.value = newVal || parseInt(this.DOM.scope.dataset.value) | 0;
             if (!this.value) return;
     
             this.countTo = (this.value + '').replace(/\D/g, '').split('');
     
             this.DOM.scope.innerHTML = Array(this.digits + 1)
                 .join('<div><b data-value=\'0\'></b></div>');
     
             if (this.decimals > 0) {
                 dot = document.createElement('span');
                 dot.style.color = 'white';
                 dot.style.fontSize = '2.1em';
                 dot.innerHTML = '.';
                 this.DOM.scope.insertBefore(dot, this.DOM.scope.querySelector('div:nth-last-child(' + this.decimals + ')'));
             }
     
             this.DOM.scope.querySelectorAll('b').forEach((item, i) => {
                 if (item.dataset.value !== parseInt(this.countTo[i]) && parseInt(this.countTo[i]) >= 0) {
                     setTimeout(j => {
                         const diff = Math.abs(parseInt(this.countTo[j]) - item.dataset.value);
                         item.dataset.value = parseInt(this.countTo[j]);
                         {{-- if (diff > 3) item.className = 'blur'; --}}
                     }, i * this.delay, i);
                 }
             });
         },
         init() {
             this.build('#{{ $id }}', {
                 delay: 500,
             });
     
             {{-- this.DOM.scope.addEventListener('transitionend', e => {
                 if (e.pseudoElement === '::before' && e.propertyName == 'margin-top') {
                     e.target.classList.remove('blur')
                 }
             }); --}}
     
             Livewire.on('update{{ $id }}', (data) => {
                 value = data.latestInfo['value'];
                 this.digits = (value + '').replace('.', '').length;
                 this.decimals = (value + '').split('.')[1]?.length || 0;
                 this.title = data.latestInfo['title'];
                 this.unit = data.latestInfo['unit'];
                 this.updated = data.latestInfo['updated'];
                 this.count(value);
             })
     
         }
     }">
    <div class="w-full">
        <div class=" text-xl font-bold"
             x-text="title"></div>
        <div class="text-sm">
            <div class="grid grid-cols-2">
                <div>
                    {{ __('Last Updated:') }}
                </div>
                <div>
                    <span x-text="updated"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-end justify-center flex-wrap gap-2"
         x-show="unit != '...'">
        <div class="numCounter inline-block h-16"
             id="{{ $id }}">
        </div>
        <div class="text-xl"
             x-text="unit">
        </div>
    </div>

    <div class="flex items-center justify-center align-middle text-center gap-2 text-2xl"
         x-show="unit == '...'">
        <div>
            {{ 'Requesting Latest Data' }}
        </div>
    </div>

    <style>
        .numCounter {
            display: inline-block;
            /* height: 73px; */
            line-height: 50px;
            font-weight: bold;
            font-size: 23px;
            overflow: hidden;
            padding: 0.4em;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-left: 0.5px solid rgba(255, 255, 255, 0.01);
            border-radius: 16px;
            background: linear-gradient(330deg, #0e3747, #2f00ff);
            /* box-shadow: -20px -20px 60px #308fc6, 20px 20px 60px #7dcbf9; */
        }

        .numCounter>div {
            display: inline-block;
            vertical-align: top;
            height: 100%;
        }

        .numCounter>div>b {
            display: inline-block;
            width: 30px;
            height: 100%;
            margin: 0 0.1em;
            border-radius: 8px;
            text-align: center;
            overflow: hidden;
            background: linear-gradient(-30deg, #0f232e, #000000);
            color: white;
        }

        .numCounter>div>b::before {
            content: " 0 1 2 3 4 5 6 7 8 9 ";
            display: block;
            word-break: break-all;
            word-break: break-word;
            transition: 0.5s cubic-bezier(0.75, 0.15, 0.6, 1.15), text-shadow 150ms;
        }

        .numCounter>div>b.blur {
            opacity: 0.8;
            text-shadow: 2px 1px 3px rgba(0, 0, 0, 0.2), 0 0.1em 2px rgba(255, 255, 255, 0.6), 0 0.3em 3px rgba(255, 255, 255, 0.3), 0 -0.1em 2px rgba(255, 255, 255, 0.6), 0 -0.3em 3px rgba(255, 255, 255, 0.3);
        }

        .numCounter>div>b[data-value="1"]::before {
            margin-top: -50px;
        }

        .numCounter>div>b[data-value="2"]::before {
            margin-top: -100px;
        }

        .numCounter>div>b[data-value="3"]::before {
            margin-top: -150px;
        }

        .numCounter>div>b[data-value="4"]::before {
            margin-top: -200px;
        }

        .numCounter>div>b[data-value="5"]::before {
            margin-top: -250px;
        }

        .numCounter>div>b[data-value="6"]::before {
            margin-top: -300px;
        }

        .numCounter>div>b[data-value="7"]::before {
            margin-top: -350px;
        }

        .numCounter>div>b[data-value="8"]::before {
            margin-top: -400px;
        }

        .numCounter>div>b[data-value="9"]::before {
            margin-top: -450px;
        }
    </style>
</div>
