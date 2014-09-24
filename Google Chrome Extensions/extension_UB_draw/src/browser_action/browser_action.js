$(function(){
   $('#b1').click(function(){
     chrome.tabs.create({url: "http://www.buffalo.edu/" });
     return false;
   });
   $('#b2').click(function(){
     chrome.tabs.create({url: "https://ubmail.buffalo.edu/" });
     return false;
   });
   $('#b3').click(function(){
     chrome.tabs.create({url: "https://myub.buffalo.edu/" });
     return false;
   });
   $('#b4').click(function(){
     chrome.tabs.create({url: "http://ub-careers.buffalo.edu/" });
     return false;
   });
});