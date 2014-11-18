<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Food Trucks</title>
    <script src="https://maps.google.com/maps?file=api&v;=3&key;=AIzaSyCa05-V7o9R7lsUTXTaEyLChQG6zbzhenA"
            type="text/javascript"></script>
   
    <script type="text/javascript">

        var map;
		// initial the map 
        function load() {
            if (GBrowserIsCompatible()) {
                map = new google.maps.Map(document.getElementById('map'));
                map.setCenter(new GLatLng(37.775819, -122.418055), 14);
                map.setUIToDefault();
            }
        }
		// do search according to user input
        function searchLocationsNear() {
            var idnum = document.getElementById('idnumInput').value;
            var add1 = document.getElementById('addInput1').value;
            var add2 = document.getElementById('addInput2').value;
            var name = document.getElementById('nameInput').value;
            var type = document.getElementById('typeInput').value;
            var status = document.getElementById('statusInput').value;
            var fooditem = document.getElementById('fooditemInput').value;
			
            var searchUrl = 'xml.php?idnum=' + idnum + '&add1=' + add1 + '&add2=' + add2 + '&name=' + name + '&type=' + type + '&status=' + status + '&fooditem=' + fooditem;
            
			//get matching result from database
            GDownloadUrl(searchUrl, function (data) {
                var xml = GXml.parse(data);
                var markers = xml.documentElement.getElementsByTagName('table1');
                map.clearOverlays();

                var sidebar = document.getElementById('sidebar');
                sidebar.innerHTML = '';
				
				// display no result if no matching items.
                if (markers.length == 0) {
                    sidebar.innerHTML = 'No qualified results!';
                    map.setCenter(new GLatLng(37.775819, -122.418055), 14);
                    return;
                }
                var bounds = new GLatLngBounds();
                for (var i = 0; i < markers.length; i++) {
                    var idnum1 = markers[i].getAttribute('idnum');
                    var name1 = markers[i].getAttribute('name');
                    var type1 = markers[i].getAttribute('type');
                    var add3 = markers[i].getAttribute('add1');
                    var fooditem1 = markers[i].getAttribute('fooditem');
                    var status1 = markers[i].getAttribute('status');
                    var point = new GLatLng(parseFloat(markers[i].getAttribute('lat')),
                                      parseFloat(markers[i].getAttribute('lng')));
					
					// add all results items to the map
                    var marker = createMarker(point, idnum1, name1, type1, add3, fooditem1, status1);
                    map.addOverlay(marker);
					// add all results items to the sidebar
                    var sidebarEntry = createSidebarEntry(idnum1, name1, type1, add3, fooditem1, status1);
                    sidebar.appendChild(sidebarEntry);
                    bounds.extend(point);
                }
                map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
            });
        }
		
		//function to create marker and corresponding information window
        function createMarker(point, idnum, name, type, address, foodItems, status) {
            var marker = new GMarker(point);
            var html = 'ID:' + idnum + '</b> <br/>' + 'Applicant:' + name + '</b> <br/>' + 'Type:' + type + '</b> <br/>' + 'Address:' + address + '</b> <br/>' + 'FoodItems:' + foodItems + '</b> <br/>' + 'Status:' + status;
            GEvent.addListener(marker, 'click', function () {
                marker.openInfoWindowHtml(html);
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
            GEvent.addDomListener(div, 'click', function () {
                GEvent.trigger(marker, 'click');
            });
            GEvent.addDomListener(div, 'mouseover', function () {
                div.style.backgroundColor = '#eee';
            });
            GEvent.addDomListener(div, 'mouseout', function () {
                div.style.backgroundColor = '#fff';
            });
            return div;
        }

		
// UI design
  </script>
  </head>

  <body onload="load()" onunload="GUnload()">

    <p><a href="sub/index.php">Switch to area search</a></p>
    <p>Location ID:
      <input type="text" id="idnumInput"/>
    Applicant: 
    <input type="text" id="nameInput"/>
    FacilityType: 
    <input type="text" id="typeInput"/>
    <br/>		  
    Address:   
    <input type="text" id="addInput1"/>
    Blocklot: 
    <input type="text" id="addInput2"/>
    <br/>
    Status: 
    <input type="text" id="statusInput"/>
    FoodItems: 
    <input type="text" id="fooditemInput"/>
    <input type="hidden" id="HMMapdata" />
    <input type="hidden" name="HmImageURL" id="HmImageURL" />
    <input type="button" onclick="searchLocationsNear()" value="Search Food"/>
    <br/>
	</p>
    <div style="width:1555px; font-family:Arial, 
sans-serif; font-size:11px; border:1px solid black">
  <table> 
    <tbody> 
      <tr id="cm_mapTR">

        <td width="255" valign="top"> <div id="sidebar" style="overflow: auto; height:610px; font-size: 15px; color: #000"></div>

        </td>
        <td> <div id="map" style="overflow: hidden; width:1300px; height:610px"></div> </td>

      </tr> 
    </tbody>
  </table>
</div>    
  </body>
</html>