<?php

$nowday = $day_names[$today];
$text = <<<TXT
	<table class='table-center'>
	<colgroup>
		<col span="4" class="col4">
	</colgroup>
	<thead>
		<tr>
			<th>Долгопрудная</th>
			<th>Новодачная</th>
			<th>Марк</th>
			<th>Тимирязевская</th>
		</tr>
	</thead>
	<tbody>
		<tr class='tutu1'>
		<td>
			<span class='tutur1'><img src='img/loading.gif'></span>
			<span class='tutur11 pomegranate'>(<span></span><span>мин</span>)</span>
		</td>
		<td><span class='tutur2'></span> <span class='tutur21 pomegranate'>(<span></span><span>мин</span>)</span></td>
		<td class='tutur3'></td>
		<td class='tutur4'></td>
		</tr>
		<tr class='tutu2'>
		<td>
			<span class='tutur1'><img src='img/loading.gif'></span>
			<span class='tutur11 pomegranate'>(<span></span><span>мин</span>)</span>
		</td>
		<td><span class='tutur2'></span> <span class='tutur21 pomegranate'>(<span></span><span>мин</span>)</span></td>
		<td class='tutur3'></td>
		<td class='tutur4'></td>
		</tr>
		<tr class='tutu3'>
		<td>
			<span class='tutur1'><img src='img/loading.gif'></span>
			<span class='tutur11 pomegranate'>(<span></span><span>мин</span>)</span>
		</td>
		<td><span class='tutur2'></span> <span class='tutur21 pomegranate'>(<span></span><span>мин</span>)</span></td>
		<td class='tutur3'></td>
		<td class='tutur4'></td>
		</tr>
		<tr class='tutu4'>
		<td>
			<span class='tutur1'><img src='img/loading.gif'></span>
			<span class='tutur11 pomegranate'>(<span></span><span>мин</span>)</span>
		</td>
		<td><span class='tutur2'></span> <span class='tutur21 pomegranate'>(<span></span><span>мин</span>)</span></td>
		<td class='tutur3'></td>
		<td class='tutur4'></td>
		</tr>
		<tr><td class='copyright' colspan='4'></td></tr>
	</tbody>
	</table>
TXT;
echo json_encode(array(
	html => $text
));
?>
