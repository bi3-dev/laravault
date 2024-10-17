<div class="w-full">
    <div class="min-h-full min-w-full"
         id="map"
         x-data
         wire:ignore
         x-init="initGoogleMap()"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&loading=async&libraries=maps&v=beta"
            async
            defer></script>
    <script>
        async function initGoogleMap() {
            const {
                Map
            } = await google.maps.importLibrary("maps");

            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");

            const map = new Map(document.getElementById("map"), {
                center: {
                    lat: 51.5388041,
                    lng: -0.00979,
                },
                zoom: 15.2,
                mapId: "4504f8b37365c3d0",
                gestureHandling: "cooperative",
                mapTypeId: 'satellite',
            });


            const locations = @json($locations);

            locations.forEach(location => {

                const parser = new DOMParser();
                let title = "";

                /**
                 * A customized popup on the map.
                 */
                class Popup extends google.maps.OverlayView {
                    position;
                    containerDiv;
                    constructor(position, content) {
                        super();
                        this.position = position;
                        content.classList.add("popup-bubble");

                        // This zero-height div is positioned at the bottom of the bubble.
                        const bubbleAnchor = document.createElement("div");

                        bubbleAnchor.classList.add("popup-bubble-anchor");
                        bubbleAnchor.appendChild(content);
                        // This zero-height div is positioned at the bottom of the tip.
                        this.containerDiv = document.createElement("div");
                        this.containerDiv.classList.add("popup-container");
                        this.containerDiv.appendChild(bubbleAnchor);
                        // Optionally stop clicks, etc., from bubbling up to the map.
                        Popup.preventMapHitsAndGesturesFrom(this.containerDiv);
                    }
                    /** Called when the popup is added to the map. */
                    onAdd() {
                        this.getPanes().floatPane.appendChild(this.containerDiv);
                    }
                    /** Called when the popup is removed from the map. */
                    onRemove() {
                        if (this.containerDiv.parentElement) {
                            this.containerDiv.parentElement.removeChild(this.containerDiv);
                        }
                    }
                    /** Called each frame when the popup needs to draw itself. */
                    draw() {
                        const divPosition = this.getProjection().fromLatLngToDivPixel(
                            this.position,
                        );
                        // Hide the popup when it is far out of view.
                        const display =
                            Math.abs(divPosition.x) < 4000 && Math.abs(divPosition.y) < 4000 ?
                            "block" :
                            "none";

                        if (display === "block") {
                            this.containerDiv.style.left = divPosition.x + "px";
                            this.containerDiv.style.top = divPosition.y + "px";
                        }

                        if (this.containerDiv.style.display !== display) {
                            this.containerDiv.style.display = display;
                        }
                    }
                }

                if (location.pin == "venue") {
                    title = "Event Venue";
                } else if (location.pin == "north") {
                    title = "North Car Park";
                } else if (location.pin == "south") {
                    title = "South Car Park";
                }

                var newDiv = document.createElement("div");
                newDiv.id = location.pin;
                newDiv.innerHTML = title;
                newDiv.classList.add("text-black", "bold", "text-lg");
                popuplat = parseFloat(location.lat) + 0.00035;

                popup = new Popup(
                    new google.maps.LatLng(popuplat, location.lng),
                    newDiv,
                );
                popup.setMap(map);

                if (location.pin != "venue") {
                    const pinSvgString =
                        `<svg xmlns="http://www.w3.org/2000/svg" width="45" height="40" viewBox="0 0 576 512" fill="none"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 128C0 92.7 28.7 64 64 64l256 0c35.3 0 64 28.7 64 64l0 256c0 35.3-28.7 64-64 64L64 448c-35.3 0-64-28.7-64-64L0 128zM559.1 99.8c10.4 5.6 16.9 16.4 16.9 28.2l0 256c0 11.8-6.5 22.6-16.9 28.2s-23 5-32.9-1.6l-96-64L416 337.1l0-17.1 0-128 0-17.1 14.2-9.5 96-64c9.8-6.5 22.4-7.2 32.9-1.6z" stroke="white" stroke-width="25" stroke-linecap="round" stroke-linejoin="round" fill="blue"></path></svg>`;


                    const pinSvg = parser.parseFromString(pinSvgString, "image/svg+xml").documentElement;
                    const marker = new AdvancedMarkerElement({
                        map,
                        position: {
                            lat: parseFloat(location.lat),
                            lng: parseFloat(location.lng)
                        },
                        content: pinSvg,
                    });
                }
            });
        }
    </script>

    <style>
        /* The popup bubble styling. */
        .popup-bubble {
            /* Position the bubble centred-above its parent. */
            position: absolute;
            top: 0;
            left: 0;
            transform: translate(-50%, -100%);
            /* Style the bubble. */
            background-color: white;
            padding: 5px;
            border-radius: 5px;
            font-family: sans-serif;
            overflow-y: auto;
            max-height: 60px;
            box-shadow: 0px 2px 10px 1px rgba(0, 0, 0, 0.5);
        }

        /* The parent of the bubble. A zero-height div at the top of the tip. */
        .popup-bubble-anchor {
            /* Position the div a fixed distance above the tip. */
            position: absolute;
            width: 100%;
            bottom: 8px;
            left: 0;
        }

        /* This element draws the tip. */
        .popup-bubble-anchor::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            /* Center the tip horizontally. */
            transform: translate(-50%, 0);
            /* The tip is a https://css-tricks.com/snippets/css/css-triangle/ */
            width: 0;
            height: 0;
            /* The tip is 8px high, and 12px wide. */
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid white;
        }

        /* JavaScript will position this div at the bottom of the popup tip. */
        .popup-container {
            cursor: auto;
            height: 0;
            position: absolute;
            /* The max width of the info window. */
            width: 200px;
        }
    </style>
</div>
