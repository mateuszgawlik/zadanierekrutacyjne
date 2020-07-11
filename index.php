<?php
$apiKey = "15b93cd369070d6ccc89a4a948d36153";
$cityName = "Wrocław";
$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . $cityName . "&appid=" . $apiKey . "&units=metric";

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$dataa = json_decode($response);

$stopnie = $dataa->wind->deg;

function toTextualDescription($degree){
    if ($degree>337.5) return 'N';
    if ($degree>292.5) return 'NW';
    if($degree>247.5) return 'W';
    if($degree>202.5) return 'Sw';
    if($degree>157.5) return 'S';
    if($degree>122.5) return 'SE';
    if($degree>67.5) return 'E';
    if($degree>22.5){return 'NE';}
    return 'N';
}
?>
<?php
$cache_file = 'data.json';
if(file_exists($cache_file)){
  $data = json_decode(file_get_contents($cache_file));
}else{
  $api_url = 'https://content.api.nytimes.com/svc/weather/v2/current-and-seven-day-forecast.json	';
  $data = file_get_contents($api_url);
  file_put_contents($cache_file, $data);
  $data = json_decode($data);
}
$current = $data->results->current[0];
$forecast = $data->results->seven_day_forecast;
?>

<style>
  body{
    background-color:#aaa!important;
  }
  .wrapper .single{
    color:#fff;
    width:100%;
    padding:10px;
    text-align:center;
    margin-bottom:10px;
  }
  .aqi-value{
    font-family : "Noto Serif","Palatino Linotype","Book Antiqua","URW Palladio L";
    font-size:40px;
    font-weight:bold;
  }
  h1{
    text-align: center;
    font-size:3em;
  }
  .forecast-block{
    background-color: #3b463d!important;
    width:20% !important;
  }
  .title{
    background-color:#673f3f;
    width: 100%;
    color:#fff;
    margin-bottom:0px;
    padding-top:10px;
    padding-bottom: 10px;
  }
  .bordered{
    border:1px solid #fff;
  }
  .weather-icon{
    width:40%;
    font-weight: bold;
    background-color: #673f3f;
    padding:10px;
    border: 1px solid #fff;
  }
</style>
<?php
  function convert2cen($value,$unit){
    if($unit=='C'){
      return $value;
    }else if($unit=='F'){
      $cen = ($value - 32) / 1.8;
        return round($cen,2);
      }
  }
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous" />
<div class="container wrapper">
  <br>
  
  <div class="row">
    <h3 class="title text-center bordered">Pogoda dla <?php echo $current->city.' ('.$current->country.')';?></h3>
    <div class="col-md-12" style="padding-left:0px;padding-right:0px;">
      <div class="single bordered" style="padding-bottom:25px;background:url('back.jpg') no-repeat ;border-top:0px;background-size: cover;">
        <div class="row">
          <div class="col-sm-9" style="font-size:19px;text-align:left;padding-left:70px;">
            </p>
            <div class="weather-icon">
				<div>Aktualna temperatura: <?php echo convert2cen($current->temp,$current->temp_unit); ?>&deg;C</div>
			<div>Wschód słońca: <?php echo date("g:i a", $dataa->sys->sunrise);?></div>
			<div>Prędkość wiatru: <?php echo $dataa->wind->speed; ?> km/h</div>
			<div>Kierunek wiatru: <?php echo toTextualDescription($stopnie); ?></div>
            </div>
          </div>
        </div>
          </div>
    </div>
  </div>
  <br><br>
  <div class="row">
    <h3 class="title text-center bordered">Temperatura na najbliższe 5 dni <?php echo $current->city.' ('.$current->country.')';?></h3>
    <?php $loop=0; foreach($forecast as $f){ $loop++;?>
      <div class="single forecast-block bordered">
        <h3><?php echo $f->day;?></h3>	
        <p style="font-size:1em;" class="aqi-value"><?php echo convert2cen($f->low,$f->low_unit);?> °C - <?php echo convert2cen($f->high,$f->high_unit);?> °C</p>
        <hr style="border-bottom:1px solid #fff;">
        <img src="<?php echo $f->image;?>">
      </div>
    <?php } ?>
  </div>
</div>
