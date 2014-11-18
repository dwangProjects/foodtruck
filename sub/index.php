<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Search nearby food truck</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa05-V7o9R7lsUTXTaEyLChQG6zbzhenA"></script>
    <script type="text/javascript">
	var cityCircle;
	var map;
	

// initialize the map	
function initialize() {
	var mapOptions = {
		zoom: 14,
		center: new google.maps.LatLng(37.775819, -122.418055),
		mapTypeId: google.maps.MapTypeId.TERRAIN
	};

	map = new google.maps.Map(document.getElementById('map'),
		  mapOptions);

// if user click on the map, draw a circle to display the search range
  google.maps.event.addListener(map, 'click', function(e) {
    placeMarker(e.latLng, map);
	var populationOptions = {
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: map,
      center: e.latLng,
      radius: document.getElementById('radiusSelect').value *1000
    };
    cityCircle = new google.maps.Circle(populationOptions);	
	
	// doing search and display the result to map and sidebar
	searchLocationsNear(e.latLng);
  });	
}

// function to put a marker on map
function placeMarker(position, map) {
  var marker = new google.maps.Marker({
    position: position,
    map: map
  });
  map.panTo(position);
}

google.maps.event.addDomListener(window, 'load', initialize);
	
	// function to search and display the result to map and sidebar
   function searchLocationsNear(center) {
			var radius = document.getElementById('radiusSelect').value;
			var searchUrl = 'xml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
			 xmlhttp=new XMLHttpRequest();
			}
			else
			{
			 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() //This part is actually executed last
			{
				 if (xmlhttp.readyState==4 && xmlhttp.status==200) // if the request processed successfully
				{
					var xmlRes=xmlhttp.responseXML;
					var markers = xmlRes.getElementsByTagName("table1");
					var sidebar = document.getElementById('sidebar');
					sidebar.innerHTML = '';
					//if no matching result
					if (markers.length == 0) {
                    sidebar.innerHTML = 'No qualified results!';
                    map.setCenter(new google.maps.LatLng(37.775819, -122.418055), 14);
					}
					var bounds = new google.maps.LatLngBounds();
					for (var i = 0; i < markers.length; i++) {
						var idnum1 = markers[i].getAttribute('idnum');
						var name1 = markers[i].getAttribute('name');
						var type1 = markers[i].getAttribute('type');
						var add3 = markers[i].getAttribute('add1');
						var fooditem1 = markers[i].getAttribute('fooditem');
						var status1 = markers[i].getAttribute('status');
						var point = new google.maps.LatLng(parseFloat(markers[i].getAttribute('lat')),
										  parseFloat(markers[i].getAttribute('lng')));
						if(idnum1 != null){
							// add all results items to the map
							var marker = createMarker(point, idnum1, name1, type1, add3, fooditem1, status1);
							marker.setMap(map);
							// add all results items to the sidebar
							var sidebarEntry = createSidebarEntry(idnum1, name1, type1, add3, fooditem1, status1);
							sidebar.appendChild(sidebarEntry);
							bounds.extend(point);						
						}

					}
					map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
					
				}
			}
			//xmlhttp request data
			xmlhttp.open("GET",searchUrl);
			xmlhttp.send();

		map.clearOverlays();
	}

	//function to create marker and corresponding information window
        function createMarker(point, idnum, name, type, address, foodItems, status) {
            var html = 'ID:' + idnum + '</b> <br/>' + 'Applicant:' + name + '</b> <br/>' + 'Type:' + type + '</b> <br/>' + 'Address:' + address + '</b> <br/>' + 'FoodItems:' + foodItems + '</b> <br/>' + 'Status:' + status;		
			var infowindow = new google.maps.InfoWindow({
				content: html
			});
			var marker = new google.maps.Marker({
			  position: point,
			  map: map,
			});
            google.maps.event.addListener(marker, 'click', function () {
				infowindow.open(map,marker);
            });

            return marker;
        }

		// function to create sidebar entries
		function createSidebarEntry(idnum, name, type, address, foodItems, status) {
            var div = document.createElement('div');
            var html = 'ID:' + idnum + '</b> <br/>' + 'Applicant:' + name + '</b> <br/>' + 'Type:' + type + '</b> <br/>' + 'Address:' + address + '</b> <br/>' + 'FoodItems:' + foodItems + '</b> <br/>' + 'Status:' + status + '<br />';
            div.innerHTML = html;
            div.style.cursor = 'pointer';
            div.style.marginBottom = '5px';
            google.maps.event.addDomListener(div, 'click', function () {
                google.maps.event.trigger(marker, 'click');
            });
            google.maps.event.addDomListener(div, 'mouseover', function () {
                div.style.backgroundColor = '#eee';
            });
            google.maps.event.addDomListener(div, 'mouseout', function () {
                div.style.backgroundColor = '#fff';
            });
            return div;
        }

// UI design
  </script>
  </head>

  <body onload="load()" onunload="GUnload()">
    <p><a href="../index.php">Return to criteria query</a></p>
    
    <p>Please choose searching radius, and then click on the map to place a center point:
    <select id="radiusSelect"> 
      <option value="0.1" selected>0.1 Kilometer</option>
      <option value="0.2">0.2 Kilometer</option>
      <option value="0.5">0.5 Kilometer</option>
      <option value="1">1 Kilometer</option>
      <option value="2">2 Kilometer</option>
      <option value="5">5 Kilometer</option>
      <option value="10">10 Kilometer</option>
      <option value="20">20 Kilometer</option>
      <option value="50">50 Kilometer</option>
      <option value="100">100 Kilometer</option>
      <option value="150">150 Kilometer</option>
      <option value="200">200 Kilometer</option>
    </select>
    <br/>    
    </p>
    <div style="width:1555px; font-family:Arial, 
sans-serif; font-size:11px; border:1px solid black">
  <table> 
    <tbody> 
      <tr id="cm_mapTR">
        <td width="255" valign="top"> <div id="sidebar" style="overflow: auto; height: 660px; font-size: 15px; color: #000"></div>
        </td>
        <td> <div id="map" style="overflow: hidden; width:1300px; height:660px"></div> </td>
      </tr> 
    </tbody>
  </table>
</div>    
  </body>
</html>