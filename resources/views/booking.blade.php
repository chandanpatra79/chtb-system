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
<style>
.form
{
  width:40%;
  border:1px solid grey;
  margin:0 auto;
  padding: 20px;
}

.parent{
    width:100%;
    text-align:center;
    padding:20px;
}
.alert-success
{
    color:green;
}
.alert {
    font-weight:bold;
}
</style>
<div class='parent'>

        <div class="col-12">
        @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        </div>

    <form class="form" method="post" action="{{url('/booking')}}">
    @csrf
    <input maxlength="3" type="text" name="chosenSeat" value="{{old('chosenSeat')}}" placeholder="Enter Seat Number" title="Enter Seat Number" required/>
    <input maxlength="1" type="text" name="reqTicket" value="{{old('reqTicket')}}" placeholder="Enter How many Seats required" title="Enter How many Seats required" required/>
    <input type="submit" value="Submit" />
    </form>
</div>
</body>

</html>

