/**
 * SCRIPTS
 *
 * @package uri-global-map
 */



var transformRequest = (url, resourceType) => {
  var isMapboxRequest =
    url.slice(8, 22) === "api.mapbox.com" ||
    url.slice(10, 26) === "tiles.mapbox.com";
  return {
    url: isMapboxRequest
      ? url.replace("?", "?pluginName=sheetMapper&")
      : url
  };
};
//YOUR TURN: add your Mapbox token

mapboxgl.accessToken = ''. $attributes["token"] .''; //Mapbox token 
var map = new mapboxgl.Map({
  container: 'map', // container id
  style: 'mapbox://styles/mapbox/satellite-v9', // YOUR TURN: choose a style: https://docs.mapbox.com/api/maps/#styles
  center: [30, 20], // starting position [lng, lat]
  zoom: 2.00,// starting zoom
  maxZoom: 3,
  minZoom: 2,
  transformRequest: transformRequest,
  projection: 'globe'
});

//Add full screen control
map.addControl(new mapboxgl.FullscreenControl());


map.on('style.load', () => {
// Set the default atmosphere style
map.setFog({});
});

//spin
// The following values can be changed to control rotation speed:

// At low zooms, complete a revolution every two minutes.
const secondsPerRevolution = 120;
// Above zoom level 5, do not rotate.
const maxSpinZoom = 5;
// Rotate at intermediate speeds between zoom levels 3 and 5.
const slowSpinZoom = 3;

let userInteracting = false;
let spinEnabled = true;

function spinGlobe() {
const zoom = map.getZoom();
if (spinEnabled && !userInteracting && zoom < maxSpinZoom) {
let distancePerSecond = 360 / secondsPerRevolution;
if (zoom > slowSpinZoom) {
// Slow spinning at higher zooms
const zoomDif =
(maxSpinZoom - zoom) / (maxSpinZoom - slowSpinZoom);
distancePerSecond *= zoomDif;
}
const center = map.getCenter();
center.lng -= distancePerSecond;
// Smoothly animate the map over one second.
// When this animation is complete, it calls a 'moveend' event.
map.easeTo({ center, duration: 1000, easing: (n) => n });
}
}

// Pause spinning on interaction
map.on('mousedown', () => {
userInteracting = true;
});

// Restart spinning the globe when interaction is complete
map.on('mouseup', () => {
userInteracting = false;
spinGlobe();
});

// These events account for cases where the mouse has moved
// off the map, so 'mouseup' will not be fired.
map.on('dragend', () => {
userInteracting = false;
spinGlobe();
});
map.on('pitchend', () => {
userInteracting = false;
spinGlobe();
});
map.on('rotateend', () => {
userInteracting = false;
spinGlobe();
});

// When animation is complete, start spinning if there is no ongoing interaction
map.on('moveend', () => {
spinGlobe();
});

document.getElementById('btn-spin').addEventListener('click', (e) => {
spinEnabled = !spinEnabled;
if (spinEnabled) {
spinGlobe();
e.target.innerHTML = 'Pause rotation';
} else {
map.stop(); // Immediately end ongoing animation
e.target.innerHTML = 'Start rotation';
}
});

spinGlobe();

//end of spin

//Map View Toggle Switch
/*const toggle = document.querySelector('.switch input')

toggle.addEventListener('click', () => {
const mapview = toggle.parentNode.querySelector('.mapview')
mapview.textContent = toggle.checked ? 'Globe View' : 'Flat Map'
}) */
//end of toggle switch


//Map View Radio buttons
const viewList = document.getElementById('menu');
const inputs = viewList.getElementsByTagName('input');

for (const input of inputs) {
    input.onclick = (view) => {
        const viewValue = view.target.value;
        console.log(viewValue)
        map.setProjection(viewValue);
    };
} 
//end of map view radio buttons

$(document).ready(function () {
  $.ajax({
    type: "GET",
    //YOUR TURN: Replace with csv export link
    url: 'https://docs.google.com/spreadsheets/d/' .$attributes["attributes"]. '/gviz/tq?tqx=out:csv&sheet=Sheet1',
    dataType: "text",
    success: function (csvData) { makeGeoJSON(csvData); }
  });



  function makeGeoJSON(csvData) {
    csv2geojson.csv2geojson(csvData, {
      latfield: 'Latitude',
      lonfield: 'Longitude',
      delimiter: ','
    }, function (err, data) {
      map.on('load', function () {


        //Add the the layer to the map
        map.addLayer({
          'id': 'csvData',
          'type': 'circle',
          'source': {
            'type': 'geojson',
            'data': data
          },
          'paint': {
            'circle-radius': 12,
            'circle-color': "#c0ddf2"
          }
        });

        


        // When a click event occurs on a feature in the csvData layer, open a popup at the
        // location of the feature, with description HTML from its properties.
        map.on('click', 'csvData', function (e) {
          var coordinates = e.features[0].geometry.coordinates.slice();

          //set popup text
          //You can adjust the values of the popup to match the headers of your CSV.
          // For example: e.features[0].properties.Name is retrieving information from the field Name in the original CSV.
          var description = `<h3>` + e.features[0].properties.Main_Header + `</h3>` + 
          `<div class="flex-containter">` +
          `<div class="boxlist">` + 
          `<ul>` + `<li><b>Location: </b>` + e.features[0].properties.Location + `</li>` + 
            `<li><b>` + e.features[0].properties.Category2 + '</b> ' + e.features[0].properties.ListItem2 + `</li>` + 
            `<li>` + `<b>` + '<a href="' + e.features[0].properties.Link  + '">' + e.features[0].properties.Link_Text  + `</a></li>` + `</b>` + `</ul></div>` +
            `<div class="thumbnailpic">` + `<img src="` + e.features[0].properties.Image + `" alt="` + e.features[0].properties.AltText + `"></div>` + `</div>` +
            
            `<p>`  + e.features[0].properties.Description + `</p>`;

            

          // Ensure that if the map is zoomed out such that multiple
          // copies of the feature are visible, the popup appears
          // over the copy being pointed to.
          while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
          }

          //add Popup to map

          new mapboxgl.Popup()
            .setLngLat(coordinates)
            .setHTML(description)
            .addTo(map);
        });

        // Change the cursor to a pointer when the mouse is over the places layer.
        map.on('mouseenter', 'csvData', function () {
          map.getCanvas().style.cursor = 'pointer';
        });

        // Change it back to a pointer when it leaves.
        map.on('mouseleave', 'places', function () {
          map.getCanvas().style.cursor = '';
        });

        var bbox = turf.bbox(data);
        map.fitBounds(bbox, { padding: 50 });

      });

    });
  };
});

/*
function toggleGlobe() {
var checkBox = document.getElementById("myToggle");
var text = document.getElementById("text");
if (checkBox.checked == true){
text.style.display = "block";
} else {
 text.style.display = "none";
}
} */


