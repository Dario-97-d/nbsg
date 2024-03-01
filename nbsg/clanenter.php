<?php include("headeron.php");
extract(mysqli_fetch_assoc(sql_query($conn, "SELECT * FROM clan WHERE id=$uid")));
if($style!=''){exit(header("Location: clan"));}
?>
<h1>Clan Style</h1>
<form action="clanenterskills" method="POST">
	<select class="select-clan" name="xc">
		<option hidden>Select clan</option>
		<option>Tameru</option>
		<option>Tayuga</option>
		<option>Kensou</option>
		<option>Surike</option>
		<option>Geniru</option>
		<option>Faruni</option>
		<option>Wyroni</option>
		<option>Raiyni</option>
		<option>Rokuni</option>
		<option>Watoni</option>
	</select>
	<br /><br />
	<input type="submit" value="Choose">
</form>
<table align="center" style="padding:16px;">
	<tr><th title="Taijutsu: Melee">Tameru</th><td>Close combat, no chakra, extreme body</td></tr>
	<tr><th title="Taijutsu: Hyuuga">Tayuga</th><td>Close combat using chakra to enhance damage</td></tr>
	<tr><th title="Kenjutsu / Sword">Kensou</th><td>Sword fight, can chanel chakra through blade</td></tr>
	<tr><th title="Shuriken">Surike</th><td>Throw shuriken and other small weapons precisely</td></tr>
	<tr><th title="Genjutsu: Illusion">Geniru</th><td>Grab oponents in illusion</td></tr>
	<tr><th title="Fire Ninjutsu">Faruni</th><td>Elemental ninjutsu, fire element</td></tr>
	<tr><th title="Wind Ninjutsu">Wyroni</th><td>Elemental ninjutsu, air element</td></tr>
	<tr><th title="Ray Ninjutsu">Raiyni</th><td>Elemental ninjutsu, ligthning element</td></tr>
	<tr><th title="Rock Ninjutsu">Rokuni</th><td>Elemental ninjutsu, rock element</td></tr>
	<tr><th title="Water Ninjutsu">Watoni</th><td>Elemental ninjutsu, water element</td></tr>
</table>
<p style="padding:0 64px;">
	Generally, all nin can develop any style.
	<br />
	It's just that each clan favours specific skills.
	<br />
	Geniru will be much more skilled to perform Genjutsu
	<br />
	than any other clan, while Tameru can't.
	<br />
	But Tameru are the most physically suited
	<br />
	to open the chakra gates, while Geniru can't.
</p>
Clan names: hover
<?php include("footer.php");?>