!function(){"use strict";window.addEventListener("load",(function(){!function(){const e=(e,t)=>({url:"api.mapbox.com"===e.slice(8,22)||"tiles.mapbox.com"===e.slice(10,26)?e.replace("?","?pluginName=sheetMapper&"):e});mapboxgl.accessToken=token.text;const t=new mapboxgl.Map({container:"map",style:"mapbox://styles/mapbox/satellite-v9",center:[30,20],zoom:2,maxZoom:2,minZoom:2,transformRequest:e,projection:"globe"});if(t.addControl(new mapboxgl.FullscreenControl),t.on("style.load",(()=>{t.setFog({})})),$(window).width()>750){const n=120,s=5,a=3;let i=!1,r=!0;function c(){const e=t.getZoom();if(r&&!i&&e<s){let o=360/n;if(e>a){o*=(s-e)/(s-a)}const i=t.getCenter();i.lng-=o,t.easeTo({center:i,duration:1e3,easing:e=>e})}}t.on("mousedown",(()=>{i=!0})),t.on("mouseup",(()=>{i=!1,c()})),t.on("dragend",(()=>{i=!1,c()})),t.on("pitchend",(()=>{i=!1,c()})),t.on("rotateend",(()=>{i=!1,c()})),t.on("moveend",(()=>{c()})),document.getElementById("btn-spin").addEventListener("click",(e=>{r=!r,r?(c(),e.target.innerHTML="Pause"):(t.stop(),e.target.innerHTML="Spin")})),c()}const o=document.getElementById("menu").getElementsByTagName("input");for(const l of o)l.onclick=e=>{const o=e.target.value;console.log(o),t.setProjection(o)};$(document).ready((function(){function e(e){csv2geojson.csv2geojson(e,{latfield:"Latitude",lonfield:"Longitude",delimiter:","},(function(e,o){t.on("load",(function(){t.addLayer({id:"csvData",type:"circle",source:{type:"geojson",data:o},paint:{"circle-radius":10,"circle-color":"#c0ddf2"}}),t.on("click","csvData",(function(e){const o=e.features[0].geometry.coordinates.slice(),n="<h3>"+e.features[0].properties.Main_Header+'</h3><div class="flex-containter"><div class="boxlist"><ul><li><b>Location: </b>'+e.features[0].properties.Location+"</li><li><b>"+e.features[0].properties.Category2+"</b> "+e.features[0].properties.ListItem2+'</li><li><b><a href="'+e.features[0].properties.Link+'">'+e.features[0].properties.Link_Text+'</a></li></b></ul></div><div class="thumbnailpic"><img src="'+e.features[0].properties.Image+'" alt="'+e.features[0].properties.AltText+'"></div></div><p>'+e.features[0].properties.Description+"</p>";for(;Math.abs(e.lngLat.lng-o[0])>180;)o[0]+=e.lngLat.lng>o[0]?360:-360;(new mapboxgl.Popup).setLngLat(o).setHTML(n).addTo(t)})),t.on("mouseenter","csvData",(function(){t.getCanvas().style.cursor="pointer"})),t.on("mouseleave","places",(function(){t.getCanvas().style.cursor=""}));const e=turf.bbox(o);t.fitBounds(e,{padding:50})}))}))}$.ajax({type:"GET",url:spreadsheet.text,dataType:"text",success(t){e(t)}})}))}()}))}();