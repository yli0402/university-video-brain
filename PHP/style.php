<?php
header("Content-type: text/css");

$font_family = 'Arial';
$font_size = '0.7em';
$border = '1px solid';
?>
.box {
  width: 35%;
  border: 15px solid #f1f1f1;
  padding: 50px;
  margin: 20px;
  float: left;
  border-style: outset;
}
.bg-image {
  background-image: url("UBC.jpeg");
  filter: blur(2px);
  -webkit-filter: blur(1.5px);
  height: 200%;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-origin:border-box;
}
.bg-text {
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0, 0.4);
  color: white;
  font-weight: bold;
  border: 3px solid #f1f1f1;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
  width: 80%;
  height: 100%;
  padding: 20px;
  text-align: center;
}
.button{
	background-color: #ffffff;
	-webkit-transition-duration: 0.4s;
	transition-duration: 0.4s;
	border-radius: 8px;
    border: none;
    padding: 10px 26px;
    text-align: center;
    display: inline-block;
    font-size: 15px;
}
.button:hover{
	background-color: #a6a6a6;
}
.c_button{
	background-color: #ffffff;
	width: 100;
	height: 100;
	margin:20px;
	-webkit-transition-duration: 0.4s;
	transition-duration: 0.4s;
	border-radius: 50%;
    border: none;
    padding: 10px 26px;
    text-align: center;
    display: inline-block;
    font-size: 15px;
}
.c_button:hover{
	background-color: #a6a6a6;
}
.text{
 text-align: center;
}
.topBar {
 background-color: #333;
 overflow: hidden;
 display: inline-block;
}
.topBar a {
 float: left;
 color: #f2f2f2;
 text-align: center;
 padding: 14px 16px;
 text-decoration: none;
 font-size: 17px;
}
.topBar a:hover {
 background-color: #999999;
 color: black;
}
.topBar a.active {
 background-color: #b54d3f;
 color: white;
}

a:link {
  color: white;
  background-color: transparent;
  text-decoration: none;
}
a:visited {
  color: green;
  background-color: transparent;
  text-decoration: none;
}
a:hover {
  color: red;
  background-color: transparent;
  text-decoration: underline;
}
a:active {
  color: green;
  background-color: transparent;
  text-decoration: underline;
}