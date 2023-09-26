<html>
<head>
<style>
table {
    width:100%;
    border-collapse:collapse;
}

th {
    background:grey;
}

td,th {
    text-align:center;
    padding:3px;
    border:1px solid black;
    height:20px;
}
.Red {
    background:red;
}
.Green {
    background:green;
}
</style>
</head>



<body>
<table >
<thead>
    <tr>
    @php
    // print seatnames
    foreach($seatNames as $seatname)
    {
        echo "<th>$seatname</th>";
    }
    @endphp
    </tr>
</thead>

<tbody>
@php
$row = 1;
foreach($seats as $seat)
{
    echo "<tr>";

    foreach($seatNames as $sn)
    {
        $color = $seat->{$sn} ? 'Red' : 'Green';
        echo "<td class='$color'>$row</td>";
    }
    echo "</tr>";
    $row ++;
}
@endphp
</tbody>
</table>
</body>

</html>

