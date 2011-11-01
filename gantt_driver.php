<html>
	<head>
		<title>Gantt Test</title>
		<style type="text/css">
/*GANTT*/
#gantt {
	border-collapse: collapse;
	border: 1px solid black;
}

#gantt th {
	font-weight: bold;
	border: 1px solid black;
}

#gantt tr {

}

#gantt td {
	border: 1px solid black;
}

#gantt td.event {
	font-weight: bold;
	border: 1px solid black;
}
#gantt td.duration {
	text-align: right;
	border: 1px solid black;
}

#gantt td.blank {
	width: 50px;
	background-color: #fff;
}

#gantt td.colored {
	width: 50px;
	border-left: 0;
	border-right: 0;
}
		</style>
	</head>
	<body>


<?php 

include_once('Gantt.php');

$gnt = new Gantt();

$gnt->add_event('Event 1',1,1);
$gnt->add_event('Event 2',3,3);
$gnt->add_event('Event 3',3,4);

echo $gnt->render_html();

 ?>


 	</body>
 </html>