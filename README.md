foodtruck
=========
Hi:

I made an application for the topic of FoodTruck, which create a service that tells the user what types of food trucks might be found near a specific location on a map. 
My solution is to build a website for users to search food trucks. I developed both front end and back end. I used JS and HTML for front end. PHP and MySQL are used for the back end.
The website has 2 main functions. The first function is searching facilities by properties and the second function is searching facilities by distance.

Searching facilities by properties
The first function is searching food trucks by Location ID,  Applicant, FacilityType, Address, Blocklot, Status and FoodItems. User can input any number of criterias. For example, if user input Applicant as “Cheese Gone Wild” only, the result will be shown as follows:
 

It also supports user to input several criterias to limit the search scope. For example, if user input FacilityType as “Truck”  and FoodItems as “Everything” , the result will be shown as follows:
 

If user move the mouse to marker in map, it will display marker’s details as the following figure:
 
 
Searching facilities by distance
User can switch to distance search by clicking the left top link named “switch to area search”. In this way, user can select a searching radius first then select the center on map to launch searching.  For example, if user select 1 kilometer as the searching radius, and select one point from the map, the result will be shown as follows:
 

In this function, user can see the searching area as the red circle and all the searching result will be shown both in map and sidebar. If user select one marker on map, the details will be shown as follows:
 

That’s the 2 main functions I designed for this project. 


