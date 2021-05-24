<?php
	
	//API-Key zum ziehen der Wetterdaten aus Openweathermap.org		
	$api_id= "get your own api";
	$ort= $_GET["ort"];
	$wetter_url= "https://api.openweathermap.org/data/2.5/weather?q=".$ort."&APPID=".$api_id."&lang=de&units=metric";

	//Anfrage auf Inhalt der Json-Datei
	$json_wetter= file_get_contents($wetter_url);
	//Json Datei dekodieren
	$jsondata= json_decode($json_wetter);

	//Zugriff auf Daten der JSON-Datei	
	$temper= $jsondata -> main -> temp;
	$temp= round($temper,0);
	$regen= $jsondata -> weather[0] -> description;
	$standort= $jsondata -> name;
	
	//Datum und Zeit in eine Variable setzen
	$datum= date('d.m.Y');
	$zeit= date('H:i');

	//Datenbankobjekt erstellen
	$db = new SQLite3('wetter.db');
	//Tabelle in der Datenbank erstellen, falls nicht vorhanden
	$db->query('CREATE TABLE IF NOT EXISTS wetter (Wetter_Id INTEGER NOT NULL, City STRING NOT NULL, Datum STRING NOT NULL, Uhrzeit STRING NOT NULL, Temperatur INTEGER NOT NULL, Regenwahrscheinlichkeit INTEGER NOT NULL, PRIMARY KEY("Wetter_Id") )');
	//Tabellenwerte eintragen
	$db->exec("INSERT INTO wetter (City, Datum, Uhrzeit, Temperatur, Regenwahrscheinlichkeit) VALUES ('$standort', '$datum', '$zeit', '$temp', '$regen')");

	/* Zum Testen: Eingetragene Tabellenwerte anzeigen
	$q=$db -> query("select * from wetter");
	while ($w=$q->fetchArray()){

		echo $w['Wetter_Id']."=>".$w['City']."=>".$w['Datum']."=>".$w['Uhrzeit']."=>".$w['Temperatur']."=>".$w['Regenwahrscheinlichkeit']."<br/>";
	}
	*/

	//header legt fest, dass ein json zurueck gegeben werden soll (Mehr Informationen: https://www.php.net/manual/de/function.header.php)
	header('Content-type: application/json'); 
	
	//Nachdem wir die Werte fuer das Wetter herausgefunden haben, moechten wir das in Form von einer JSON ausgeben
	//Wenn das PHPSkript ein JSON zurueck geben soll, dann nutzen wir json_encode.
	//json_encode benoetigt ein Array, um zu funktionieren. Also erstellen wir aus den Wetterdaten zunaechst ein Array
$array_wetterdaten = array('datum' => $datum, 'uhr' => $zeit, 'temperatur' => $temp, 'regenwahrscheinlichkeit' => $regen, 'ort' => $standort);
	
	//Wir haben unser PHP-Array mit der Funktion json_encode in einen JSON-String codiert
	$row_data = array();
	$antwort = json_encode($array_wetterdaten);
	
	//Zum Schluss muessen wir das JSON zuruecksenden:
	//Quasi das "return" in Java
	echo $antwort;
?>
		
