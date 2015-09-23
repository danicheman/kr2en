<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8"> 
<html>
<head>
<title>Verb input form</title>

<style type="text/css">
div.content{

background: #998E76; 
padding: 5px;
color: black;

} 
input
{
color: #781351;
background: #fee3ad;
border: 1px solid #781351
margin-left: 4.5em;
}
label
{
width: 20em;
float: left;
text-align: right;
margin-right: 0.5em;
display: block
}

</style>
</head>
<body>
<h1>Input Korean Verbs</h1>
<h2>hada verbs go in unconjugated like 하다</h2>
<div class = "content"> 
<form action="verb_input.php" method="post"><label>Not Conjugated: <input type="text" size="10" name="knocon">
<label for="kcon">Cojugated:</label> <input type="text" size="10" name="kcon">
<label for="other">Other: </label><input type="text" size="10" name="other">
<label>Context: </label><input type="text" size="5" name="context">
<label>With Human</label>True<input type="radio" name="group1" id="r1" value="1" />
<label>False</label><input type="radio" name="group1" id="r2" value="0" />
<label>With Thing</label>True<input type="radio" name="group2" id="r1" value="1" />
<label>False</label><input type="radio" name="group2" id="r2" value="0" />
<label>With Place</label>True<input type="radio" name="group3" id="r1" value="1" />
<label>False</label><input type="radio" name="group3" id="r2" value="0" />
<label>Requires Object</label>True<input type="radio" name="group4" id="r1" value="1" />
<label>False</label><input type="radio" name="group4" id="r2" value="0" />
English <input type="text" size="10" name="english">
<p><input type="submit" /></p> 
</form> 
</div>

</body>