<head>
    <style>
        img{
            width:15px;
        }
        .fila:nth-child(2n+1){
            background: #b8860b;
        }
        .fila:nth-child(even){
            background: #7fffd4;
        }
    </style>
    <body>
        <?php
    // function to generate ratings
    function generate_ratings($rating) {
        $movie_rating = '';
        for ($i = 0; $i < $rating; $i++) {
            $movie_rating .= '<img src="star.png" alt="star"/>';
        }
        return $movie_rating;
    }

    // take in the id of a director and return his/her full name
    function get_director($director_id) {

        global $db;

        $query = 'SELECT 
                people_fullname 
        FROM
            people
        WHERE
            people_id = ' . $director_id;
        $result = mysqli_query($db, $query) or die(mysqli_error($db));

        $row = mysqli_fetch_assoc($result);
        extract($row);

        return $people_fullname;
    }

    // take in the id of a lead actor and return his/her full name
    function get_leadactor($leadactor_id) {

        global $db;

        $query = 'SELECT
                people_fullname
            FROM
                people 
            WHERE
                people_id = ' . $leadactor_id;
        $result = mysqli_query($db, $query) or die(mysqli_error($db));

        $row = mysqli_fetch_assoc($result);
        extract($row);

        return $people_fullname;
    }

    // take in the id of a movie type and return the meaningful textual
    // description
    function get_movietype($type_id) {

        global $db;

        $query = 'SELECT 
                movietype_label
        FROM
            movietype
        WHERE
            movietype_id = ' . $type_id;
        $result = mysqli_query($query, $db) or die(mysqli_error($db));

        $row = mysqli_fetch_assoc($result);
        extract($row);

        return $movietype_label;
    }

    // function to calculate if a movie made a profit, loss or just broke even
    function calculate_differences($takings, $cost) {

        $difference = $takings - $cost;

        if ($difference < 0) {     
            $color = 'red';
            $difference = '$' . abs($difference) . ' million';
        } elseif ($difference > 0) {
            $color ='green';
            $difference = '$' . $difference . ' million';
        } else {
            $color = 'blue';
            $difference = 'broke even';
        }

        return '<span style="color:' . $color . ';">' . $difference . '</span>';
    }

    //connect to MySQL
    $db = mysqli_connect('localhost', 'root', 'root') or 
        die ('Unable to connect. Check your connection parameters.');
    mysqli_select_db($db, 'moviesite') or die(mysqli_error($db));

    // retrieve information
    $query = 'SELECT
            movie_name, movie_year, movie_director, movie_leadactor,
            movie_type, movie_running_time, movie_cost, movie_takings
        FROM
            movie
        WHERE
            movie_id = ' . $_GET['movie_id'];
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    //echo $query;
    $row = mysqli_fetch_assoc($result);
    $movie_name         = $row['movie_name'];
    $movie_director     = get_director($row['movie_director']);
    $movie_leadactor    = get_leadactor($row['movie_leadactor']);
    $movie_year         = $row['movie_year'];
    $movie_running_time = $row['movie_running_time'] .' mins';
    $movie_takings      = $row['movie_takings'] . ' million';
    $movie_cost         = $row['movie_cost'] . ' million';
    $movie_health       = calculate_differences($row['movie_takings'],
                            $row['movie_cost']);

    // display the information
    echo <<<ENDHTML
    <html>
    <head>
    <title>Details and Reviews for: $movie_name</title>
    </head>
    <body>
    <div style="text-align: center;">
    <h2>$movie_name</h2>
    <h3><em>Details</em></h3>
    <table cellpadding="2" cellspacing="2"
        style="width: 70%; margin-left: auto; margin-right: auto;">
        <tr>
        <td><strong>Title</strong></strong></td>
        <td>$movie_name</td>
        <td><strong>Release Year</strong></strong></td>
        <td>$movie_year</td>
        </tr><tr>
        <td><strong>Movie Director</strong></td>
        <td>$movie_director</td>
        <td><strong>Cost</strong></td>
        <td>$$movie_cost<td/>
        </tr><tr>
        <td><strong>Lead Actor</strong></td>
        <td>$movie_leadactor</td>
        <td><strong>Takings</strong></td>
        <td>$$movie_takings<td/>
        </tr><tr>
        <td><strong>Running Time</strong></td>
        <td>$movie_running_time</td>
        <td><strong>Health</strong></td>
        <td>$movie_health<td/>
        </tr>
    </table>
    ENDHTML;

    //if para que me cambie el orden la tabla 
    $c = $_GET['orden'];
    if($c == 'ASC'){
        $c = 'DESC';
    }
    else{
        $c = 'ASC';
    }
    // retrieve reviews for this movie
    $query = 'SELECT
            review_movie_id, review_date, reviewer_name, review_comment,
            review_rating
        FROM
            reviews
        WHERE
            review_movie_id = ' . $_GET['movie_id'] . '
        ORDER BY
            review_date '. $c;

    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $a = $_GET['movie_id'];
    // display the reviews
    echo <<< ENDHTML
    <h3><em>Reviews</em></h3>
    <table cellpadding="2" cellspacing="2"
        style="width: 90%; margin-left: auto; margin-right: auto;">
        <tr class="fila">
        <th style="width: 7em;"><a href="N4P110form.php?movie_id=$a&columna=review_date&orden=$c">Date</a></th>
        <th style="width: 10em;"><a href="N4P110form.php?movie_id=$a&columna=review_name&orden=$c">Reviewer</a></th>
        <th><a href="N4P110form.php?movie_id=$a&columna=review_comment&orden=$c">Comments</a></th>
        <th style="width: 5em;"><a href="N4P110form.php?movie_id=$a&columna=review_rating&orden=$c">Rating</a></th>
        </tr>
    ENDHTML;
    $media_estrellas = 0;
    $lineas = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $date = $row['review_date'];
        $name = $row['reviewer_name'];
        $comment = $row['review_comment'];
        $rating = generate_ratings($row['review_rating']);

        $media_estrellas += ($row['review_rating']);
        $lineas = $lineas +1;
        echo <<<ENDHTML
        <tr class="fila">
        <td style="vertical-align:top; text-align: center;">$date</td>
        <td style="vertical-align:top;">$name</td>
        <td style="vertical-align:top;">$comment</td>
        <td style="vertical-align:top;">$rating</td>
        </tr>
    ENDHTML;
    }
    $media_estrellas = $media_estrellas/$lineas;
    echo $media_estrellas;

    
    echo <<<ENDHTML
    </div>
    </body>
    </html>
    ENDHTML;
    ?>
    </body>
</head>
<?php

$a = $_GET['movie_id'];
if(!isset($_GET['movie_id'])){
  $a = 1;
}else{
  $a = $_GET['movie_id'];
}

$db = mysqli_connect('localhost', 'root', 'root') or 
die ('Unable to connect. Check your connection parameters.');

//make sure you're using the correct database
mysqli_select_db($db,'moviesite') or die(mysqli_error($db));
echo <<<ENDHTML
<html>
<head>
  <title>Say My Name</title>
</head>
<body>
  <form action="N4P111formprocess.php?movie_id=$a" method="post">
    <table>
      <tr>
        <td><label for="fname">Nombre Usuario</label></td>
        <td><input type="text" id="fname" name="fname"></td>
        <td><label for="rango">Valoraci??n</label></td>
        <td><input type="range" id="rango" name="rango" min="1" max="5"></td>
        <td><label for="comentario">Comentario</label></td>
        <td><input type="text" id="comentario" name="comentario"></td>
        <td><label for="fname">Fecha</label></td>
        <td><input type="date" id="fecha" name="fecha"></td>
      </tr>
      <td>
        <td colspan="2" style="display: block">
          <input type="submit" name="submit" value="Submit">
        </td>
      </td>
    </table>
  </form>
</body>
</html>
ENDHTML;
?>
